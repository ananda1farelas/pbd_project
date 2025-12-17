<?php

namespace App\Http\Controllers\Superadmin\Transaksi;

use Illuminate\Http\Request;
use App\Models\PenerimaanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        // Ambil pengadaan yang approved atau dalam proses (A dan P)
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
            'barang.*.jumlah_terima' => 'required|integer|min:0'
        ]);

    try {
        DB::beginTransaction();

        $user_iduser = Auth::id() ?? $request->user_iduser ?? '1';
        $idpengadaan = $request->idpengadaan;
        
        // 1. Buat header penerimaan
        $idpenerimaan = PenerimaanModel::create($idpengadaan, $user_iduser);

        // flag untuk cek apakah ada barang diterima
        $adaTerima = false;

        // 2. Tambah detail barang yang diterima
        foreach ($request->barang as $item) {
            if (isset($item['jumlah_terima']) && $item['jumlah_terima'] > 0) {
                PenerimaanModel::addDetail(
                    $idpenerimaan,
                    $item['idbarang'],
                    $item['jumlah_terima']
                );
                $adaTerima = true; // 🔥 ada minimal 1 barang masuk
            }
        }

        // 3. Kalau ada barang diterima → set status pengadaan jadi P
        if ($adaTerima) {
            DB::update("
                UPDATE pengadaan
                SET status = 'P'
                WHERE idpengadaan = ?
                  AND status = 'A'
            ", [$idpengadaan]);
        }

        // 4. Update status penerimaan (B / S / L)
        PenerimaanModel::hitungStatusPenerimaan($idpenerimaan);
        PenerimaanModel::cekDanUpdateStatusPengadaan($idpengadaan);

        DB::commit();

        return redirect()->route('penerimaan.show', $idpenerimaan)
            ->with('success', 'Penerimaan barang berhasil dicatat!');

    } catch (\Exception $e) {
        DB::rollBack();
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
        $pengadaan = DB::select("
            SELECT pg.*, v.nama_vendor,
                (CASE 
                    WHEN pg.status = 'A' THEN 'Approved'
                    WHEN pg.status = 'P' THEN 'Proses Penerimaan'
                    WHEN pg.status = 'S' THEN 'Selesai'
                    WHEN pg.status = 'C' THEN 'Cancelled'
                    ELSE pg.status
                END) as status_label
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

    // 🔹 Hapus penerimaan (dengan update status pengadaan)
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $deleted = PenerimaanModel::deleteData($id);
            
            if ($deleted) {
                DB::commit();
                return redirect()->route('superadmin.transaksi.penerimaan.index')
                    ->with('success', 'Penerimaan berhasil dihapus');
            } else {
                DB::rollBack();
                return back()->with('error', 'Gagal menghapus penerimaan');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus penerimaan: ' . $e->getMessage());
        }
    }
}