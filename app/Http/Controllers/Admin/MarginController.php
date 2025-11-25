<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MarginController extends Controller
{
    // List margin (read only)
    public function index()
    {
        $margin = DB::select("
            SELECT 
                mp.idmargin_penjualan,
                mp.persen,
                mp.status,
                u.username AS dibuat_oleh,
                mp.created_at,
                mp.updated_at
            FROM margin_penjualan mp
            JOIN user u ON mp.iduser = u.iduser
            ORDER BY mp.created_at DESC
        ");
        
        return view('admin.margin.index', compact('margin'));
    }
    
    // Detail margin
    public function show($id)
    {
        $margin = DB::select("
            SELECT 
                mp.*,
                u.username AS dibuat_oleh
            FROM margin_penjualan mp
            JOIN user u ON mp.iduser = u.iduser
            WHERE mp.idmargin_penjualan = ?
        ", [$id]);
        
        if (!$margin) {
            return redirect()->route('admin.margin.index')
                ->with('error', 'Margin tidak ditemukan');
        }
        
        // Penjualan yang menggunakan margin ini
        $penjualan = DB::select("
            SELECT 
                p.idpenjualan,
                p.created_at,
                u.username,
                CAST(p.total_nilai AS DECIMAL(15,2)) as total_nilai
            FROM penjualan p
            JOIN user u ON p.iduser = u.iduser
            WHERE p.idmargin_penjualan = ?
            ORDER BY p.created_at DESC
            LIMIT 10
        ", [$id]);
        
        return view('admin.margin.show', [
            'margin' => $margin[0],
            'penjualan' => $penjualan
        ]);
    }
}