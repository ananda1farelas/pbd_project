<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Barang
        $totalBarang = DB::select("SELECT COUNT(*) as total FROM barang WHERE status = '1' OR status = 'Aktif'");
        $totalBarang = $totalBarang[0]->total ?? 0;
        
        // Total Penjualan
        $totalPenjualan = DB::select("SELECT COUNT(*) as total FROM penjualan");
        $totalPenjualan = $totalPenjualan[0]->total ?? 0;
        
        // Margin Aktif
        $marginAktif = DB::select("SELECT COUNT(*) as total FROM margin_penjualan WHERE status = '1' OR status = 'aktif'");
        $marginAktif = $marginAktif[0]->total ?? 0;
        
        // Total Pendapatan
        $totalPendapatan = DB::select("SELECT COALESCE(SUM(CAST(total_nilai AS DECIMAL(15,2))), 0) as total FROM penjualan");
        $totalPendapatan = $totalPendapatan[0]->total ?? 0;
        
        // Penjualan Hari Ini
        $penjualanHariIni = DB::select("SELECT COUNT(*) as jumlah FROM penjualan WHERE DATE(created_at) = CURDATE()");
        $penjualanHariIni = $penjualanHariIni[0]->jumlah ?? 0;
        
        // Penjualan 7 Hari Terakhir (untuk chart)
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
        
        return view('admin.dashboard', compact(
            'totalBarang',
            'totalPenjualan',
            'marginAktif',
            'totalPendapatan',
            'penjualanHariIni',
            'chartPenjualan'
        ));
    }
}