<?php

namespace App\Http\Controllers\Superadmin\Transaksi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BarangModel;

class DetailPengadaanController extends Controller
{
    // 💾 Simpan detail barang baru ke pengadaan tertentu
    public function store(Request $request, $idpengadaan)
    {
        $request->validate([
            'idbarang' => 'required',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0'
        ]);

        $subtotal = $request->jumlah * $request->harga_satuan;

        DB::insert("
            INSERT INTO detail_pengadaan (idpengadaan, idbarang, jumlah, harga_satuan, subtotal)
            VALUES (?, ?, ?, ?, ?)
        ", [$idpengadaan, $request->idbarang, $request->jumlah, $request->harga_satuan, $subtotal]);

        // Update total pengadaan
        DB::update("
            UPDATE pengadaan 
            SET total = (SELECT SUM(subtotal) FROM detail_pengadaan WHERE idpengadaan = ?)
            WHERE idpengadaan = ?
        ", [$idpengadaan, $idpengadaan]);

        return redirect()->route('superadmin.transaksi.pengadaan.show', $idpengadaan)
            ->with('success', 'Barang berhasil ditambahkan ke pengadaan!');
    }

    // ❌ Hapus detail barang
    public function destroy($idpengadaan, $iddetail)
    {
        DB::delete("DELETE FROM detail_pengadaan WHERE iddetail = ?", [$iddetail]);

        // Update total pengadaan
        DB::update("
            UPDATE pengadaan 
            SET total = (SELECT IFNULL(SUM(subtotal), 0) FROM detail_pengadaan WHERE idpengadaan = ?)
            WHERE idpengadaan = ?
        ", [$idpengadaan, $idpengadaan]);

        return redirect()->route('superadmin.transaksi.pengadaan.show', $idpengadaan)
            ->with('success', 'Barang berhasil dihapus dari pengadaan!');
    }
}
