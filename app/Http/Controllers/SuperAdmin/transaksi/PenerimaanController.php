<?php

namespace App\Http\Controllers\Superadmin\Transaksi;

use Illuminate\Http\Request;
use App\Models\PenerimaanModel;
use Illuminate\Support\Facades\Auth;

class PenerimaanController
{
    // 🔹 Tampilkan halaman list penerimaan
    public function index()
    {
        $penerimaan = PenerimaanModel::getAll();
        return view('superadmin.transaksi.penerimaan.index', compact('penerimaan'));
    }

    public function create()
    {
        // Ambil semua pengadaan yang approved
        $pengadaan = PenerimaanModel::getPengadaanApproved();
        
        return view('superadmin.transaksi.penerimaan.create', compact('pengadaan'));
    }

    // 🔹 API: Ambil barang dari pengadaan (untuk AJAX)
    public function getBarangPengadaan($idpengadaan)
    {
        try {
            $barang = PenerimaanModel::getBarangPengadaan($idpengadaan);
            
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

    // 🔹 Proses simpan penerimaan
    public function store(Request $request)
    {
        $request->validate([
            'idpengadaan' => 'required',
            'barang' => 'required|array|min:1',
            'barang.*.idbarang' => 'required',
            'barang.*.jumlah_terima' => 'required|integer|min:1'
        ]);

        try {
            $user_iduser = Auth::id() ?? $request->user_iduser ?? '1';
            $idpengadaan = $request->idpengadaan;
            
            $idpenerimaan = PenerimaanModel::create($idpengadaan, $user_iduser);

            // 2. Tambah detail barang yang diterima
            foreach ($request->barang as $item) {
                if (isset($item['jumlah_terima']) && $item['jumlah_terima'] > 0) {
                    PenerimaanModel::addDetail(
                        $idpenerimaan,
                        $item['idbarang'],
                        $item['jumlah_terima']
                    );
                }
            }

            return redirect()->route('penerimaan.show', $idpenerimaan)
                ->with('success', 'Penerimaan barang berhasil dicatat!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencatat penerimaan: ' . $e->getMessage())->withInput();
        }
    }

    // 🔹 Tampilkan detail penerimaan
    public function show($id)
    {
        $penerimaan = PenerimaanModel::getById($id);
        $details = PenerimaanModel::getDetails($id);
        
        if (!$penerimaan) {
            return redirect()->route('superadmin.transaksi.penerimaan.index')
                ->with('error', 'Penerimaan tidak ditemukan');
        }

        // Ambil info pengadaan
        $pengadaan = \DB::select("
            SELECT pg.*, v.nama_vendor 
            FROM pengadaan pg
            JOIN vendor v ON pg.vendor_idvendor = v.idvendor
            WHERE pg.idpengadaan = ?
        ", [$penerimaan->idpengadaan]);

        // Ambil progress penerimaan
        $progress = PenerimaanModel::getProgressPenerimaan($penerimaan->idpengadaan);

        return view('superadmin.transaksi.penerimaan.show', [
            'penerimaan' => $penerimaan,
            'pengadaan' => $pengadaan[0] ?? null,
            'details' => $details,
            'progress' => $progress
        ]);
    }
}