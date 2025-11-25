<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil statistik utama
        $stats = DB::select("SELECT * FROM view_dashboard_stats LIMIT 1");
        $stats = $stats ? $stats[0] : null;

        // Ambil barang stok menipis
        $barangStokMenipis = DB::select("SELECT * FROM view_barang_stok_menipis LIMIT 5");

        // Ambil transaksi terbaru
        $transaksiTerbaru = DB::select("SELECT * FROM view_transaksi_terbaru LIMIT 10");

        // Ambil stok barang (top 5 stok terbanyak)
        $topStok = DB::select("
            SELECT 
                b.nama AS nama_barang,
                COALESCE(
                    (SELECT CAST(stock AS UNSIGNED) 
                     FROM kartu_stok 
                     WHERE idbarang = b.idbarang 
                     ORDER BY created_at DESC 
                     LIMIT 1), 
                    0
                ) AS stok_tersedia
            FROM barang b
            WHERE b.status IN ('1', 'Aktif')
            ORDER BY stok_tersedia DESC
            LIMIT 5
        ");

        // Data untuk chart penjualan (7 hari terakhir)
        $chartPenjualan = DB::select("
            SELECT 
                DATE(created_at) as tanggal,
                COUNT(*) as jumlah_transaksi,
                SUM(CAST(total_nilai AS DECIMAL(15,2))) as total_nilai
            FROM penjualan
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY tanggal ASC
        ");

        return view('superadmin.dashboard', compact(
            'stats',
            'barangStokMenipis',
            'transaksiTerbaru',
            'topStok',
            'chartPenjualan'
        ));
    }
}