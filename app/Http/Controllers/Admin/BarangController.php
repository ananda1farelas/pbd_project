<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    // List barang (read only)
    public function index()
    {
        $barang = DB::select("
            SELECT 
                b.idbarang,
                b.nama AS nama_barang,
                b.jenis,
                s.nama_satuan,
                b.status,
                b.harga_satuan,
                COALESCE(
                    (SELECT CAST(stock AS UNSIGNED) 
                     FROM kartu_stok 
                     WHERE idbarang = b.idbarang 
                     ORDER BY created_at DESC 
                     LIMIT 1), 
                    0
                ) AS stok_tersedia
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan
            ORDER BY b.nama ASC
        ");
        
        return view('admin.barang.index', compact('barang'));
    }
    
    // Detail barang
    public function show($id)
    {
        $barang = DB::select("
            SELECT 
                b.*,
                s.nama_satuan
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE b.idbarang = ?
        ", [$id]);
        
        if (!$barang) {
            return redirect()->route('admin.barang.index')
                ->with('error', 'Barang tidak ditemukan');
        }
        
        // History stok
        $historyStok = DB::select("
            SELECT 
                ks.*,
                CASE 
                    WHEN ks.jenis_transaksi = 'M' THEN 'Masuk'
                    WHEN ks.jenis_transaksi = 'K' THEN 'Keluar'
                    WHEN ks.jenis_transaksi = 'R' THEN 'Retur'
                    ELSE 'Unknown'
                END AS jenis_label
            FROM kartu_stok ks
            WHERE ks.idbarang = ?
            ORDER BY ks.created_at DESC
            LIMIT 10
        ", [$id]);
        
        return view('admin.barang.show', [
            'barang' => $barang[0],
            'historyStok' => $historyStok
        ]);
    }
}