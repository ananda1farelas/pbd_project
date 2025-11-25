<?php

namespace App\Http\Controllers\Superadmin\Transaksi;

use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use Illuminate\Support\Facades\Auth;

class PenjualanController
{
    // 🔹 Tampilkan halaman list penjualan
    public function index()
    {
        $penjualan = PenjualanModel::getAll();
        return view('superadmin.transaksi.penjualan.index', compact('penjualan'));
    }

    // 🔹 Tampilkan form tambah penjualan
// 🔹 Tampilkan form tambah penjualan
    public function create()
    {
        $barang = PenjualanModel::getBarangReadyStock();
        $marginAktif = PenjualanModel::getMarginAktifTerkini();  // ✅ Ambil margin aktif
        
        // Validasi: harus ada margin aktif
        if (!$marginAktif) {
            return redirect()->route('superadmin.transaksi.penjualan.index')
                ->with('error', 'Tidak ada margin penjualan yang aktif. Silakan aktifkan margin terlebih dahulu.');
        }
        
        return view('superadmin.transaksi.penjualan.create', compact('barang', 'marginAktif'));
    }

    // 🔹 API: Hitung harga jual (otomatis pakai margin aktif)
    public function hitungHargaJual(Request $request)
    {
        try {
            $idbarang = $request->idbarang;
            
            if (!$idbarang) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID barang tidak valid'
                ], 400);
            }
            
            $hargaJual = PenjualanModel::hitungHargaJualAuto($idbarang);  // ✅ Auto margin
            $stok = PenjualanModel::getStokBarang($idbarang);
            
            return response()->json([
                'success' => true,
                'harga_jual' => $hargaJual,
                'stok' => $stok
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // 🔹 Proses simpan penjualan
    public function store(Request $request)
    {
        $request->validate([
            'barang' => 'required|array|min:1',
            'barang.*.idbarang' => 'required',
            'barang.*.jumlah' => 'required|integer|min:1'
        ]);

        try {
            // 1. Buat header penjualan (margin otomatis dari SP)
            $user_iduser = Auth::id() ?? $request->user_iduser ?? '1';
            
            $result = PenjualanModel::create($user_iduser);  // ✅ Gak perlu kirim margin
            $idpenjualan = $result['idpenjualan'];

            // 2. Tambah detail barang
            foreach ($request->barang as $item) {
                if (isset($item['jumlah']) && $item['jumlah'] > 0) {
                    PenjualanModel::addDetail(
                        $idpenjualan,
                        $item['idbarang'],
                        $item['jumlah']
                    );
                }
            }

            return redirect()->route('penjualan.show', $idpenjualan)
                ->with('success', 'Penjualan berhasil dicatat!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencatat penjualan: ' . $e->getMessage())->withInput();
        }
    }

    // 🔹 Tampilkan detail penjualan
// 🔹 Tampilkan detail penjualan
    public function show($id)
    {
        $penjualan = PenjualanModel::getById($id);
        $details = PenjualanModel::getDetails($id);
        
        if (!$penjualan) {
            return redirect()->route('superadmin.transaksi.penjualan.index')
                ->with('error', 'Penjualan tidak ditemukan');
        }

        // Ambil info user & margin - FIX: ambil index [0] langsung
        $userResult = \DB::select("SELECT username FROM user WHERE iduser = ?", [$penjualan->iduser]);
        $marginResult = \DB::select("SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?", [$penjualan->idmargin_penjualan]);

        return view('superadmin.transaksi.penjualan.show', [
            'penjualan' => $penjualan,
            'user' => $userResult[0] ?? null,        // ✅ Langsung ambil index [0]
            'margin' => $marginResult[0] ?? null,    // ✅ Langsung ambil index [0]
            'details' => $details
        ]);
    }

    // 🔹 Hapus penjualan
    public function destroy($id)
    {
        try {
            PenjualanModel::deleteData($id);
            return redirect()->route('superadmin.transaksi.penjualan.index')
                ->with('success', 'Penjualan berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus penjualan: ' . $e->getMessage());
        }
    }
}