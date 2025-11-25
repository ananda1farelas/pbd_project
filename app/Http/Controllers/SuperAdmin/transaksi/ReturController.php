<?php

namespace App\Http\Controllers\Superadmin\Transaksi;

use Illuminate\Http\Request;
use App\Models\ReturModel;
use Illuminate\Support\Facades\Auth;

class ReturController
{
    // 🔹 Tampilkan halaman list retur
    public function index()
    {
        $retur = ReturModel::getAll();
        return view('superadmin.transaksi.retur.index', compact('retur'));
    }

    // 🔹 Tampilkan form tambah retur
    public function create()
    {
        $penerimaan = ReturModel::getPenerimaanBisaRetur();
        return view('superadmin.transaksi.retur.create', compact('penerimaan'));
    }

    // 🔹 API: Ambil barang dari penerimaan (untuk AJAX)
    public function getBarangPenerimaan($idpenerimaan)
    {
        try {
            $barang = ReturModel::getBarangPenerimaan($idpenerimaan);
            
            return response()->json([
                'success' => true,
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // 🔹 Proses simpan retur
    public function store(Request $request)
    {
        $request->validate([
            'idpenerimaan' => 'required',
            'barang' => 'required|array|min:1',
            'barang.*.iddetail_penerimaan' => 'required',
            'barang.*.jumlah_retur' => 'required|integer|min:1',
            'barang.*.alasan' => 'required|string|max:200'
        ]);

        try {
            // 1. Buat header retur
            $user_iduser = Auth::id() ?? $request->user_iduser ?? '1';
            $idpenerimaan = $request->idpenerimaan;
            
            $idretur = ReturModel::create($idpenerimaan, $user_iduser);

            // 2. Tambah detail barang yang di-retur
            foreach ($request->barang as $item) {
                if (isset($item['jumlah_retur']) && $item['jumlah_retur'] > 0) {
                    ReturModel::addDetail(
                        $idretur,
                        $item['iddetail_penerimaan'],
                        $item['jumlah_retur'],
                        $item['alasan']
                    );
                }
            }

            return redirect()->route('retur.show', $idretur)
                ->with('success', 'Retur barang berhasil dicatat!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencatat retur: ' . $e->getMessage())->withInput();
        }
    }

    // 🔹 Tampilkan detail retur
    public function show($id)
    {
        $retur = ReturModel::getById($id);
        $details = ReturModel::getDetails($id);
        
        if (!$retur) {
            return redirect()->route('superadmin.transaksi.retur.index')
                ->with('error', 'Retur tidak ditemukan');
        }

        // Ambil info penerimaan & pengadaan
        $penerimaan = \DB::select("
            SELECT 
                p.idpenerimaan,
                p.created_at AS tanggal_penerimaan,
                pg.idpengadaan,
                v.nama_vendor
            FROM penerimaan p
            JOIN pengadaan pg ON p.idpengadaan = pg.idpengadaan
            JOIN vendor v ON pg.vendor_idvendor = v.idvendor
            WHERE p.idpenerimaan = ?
        ", [$retur->idpenerimaan]);

        // Ambil user
        $user = \DB::select("SELECT username FROM user WHERE iduser = ?", [$retur->iduser]);

        return view('superadmin.transaksi.retur.show', [
            'retur' => $retur,
            'penerimaan' => $penerimaan[0] ?? null,
            'user' => $user[0] ?? null,
            'details' => $details
        ]);
    }
}