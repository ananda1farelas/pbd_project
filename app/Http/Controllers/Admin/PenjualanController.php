<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    // List penjualan (read only)
    public function index()
    {
        $penjualan = DB::select("
            SELECT 
                p.idpenjualan,
                p.created_at,
                u.username,
                mp.persen as margin,
                CAST(p.subtotal_nilai AS DECIMAL(15,2)) as subtotal_nilai,
                CAST(p.ppn AS DECIMAL(15,2)) as ppn,
                CAST(p.total_nilai AS DECIMAL(15,2)) as total_nilai
            FROM penjualan p
            JOIN user u ON p.iduser = u.iduser
            JOIN margin_penjualan mp ON p.idmargin_penjualan = mp.idmargin_penjualan
            ORDER BY p.created_at DESC
        ");
        
        return view('admin.penjualan.index', compact('penjualan'));
    }
    
    // Detail penjualan
    public function show($id)
    {
        $penjualan = DB::select("SELECT * FROM penjualan WHERE idpenjualan = ?", [$id]);
        
        if (!$penjualan) {
            return redirect()->route('admin.penjualan.index')
                ->with('error', 'Penjualan tidak ditemukan');
        }
        
        $details = DB::select("
            SELECT 
                dp.iddetail_penjualan,
                b.nama as nama_barang,
                s.nama_satuan,
                b.harga_satuan as harga_asli,
                CAST(dp.harga_satuan AS UNSIGNED) as harga_jual,
                CAST(dp.jumlah AS UNSIGNED) as jumlah,
                CAST(dp.subtotal AS UNSIGNED) as subtotal
            FROM detail_penjualan dp
            JOIN barang b ON dp.idbarang = b.idbarang
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE dp.idpenjualan = ?
        ", [$id]);
        
        // User & Margin
        $user = DB::select("SELECT username FROM user WHERE iduser = ?", [$penjualan[0]->iduser]);
        $margin = DB::select("SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?", [$penjualan[0]->idmargin_penjualan]);
        
        return view('admin.penjualan.show', [
            'penjualan' => $penjualan[0],
            'user' => $user[0] ?? null,
            'margin' => $margin[0] ?? null,
            'details' => $details
        ]);
    }
}