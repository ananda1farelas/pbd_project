<?php

namespace App\Http\Controllers\Superadmin\Transaksi;

use Illuminate\Http\Request;
use App\Models\KartuStokModel;

class KartuStokController
{
    // 🔹 Tampilkan halaman list kartu stok (semua transaksi)
    public function index(Request $request)
    {
        // Ambil filter dari request
        $idbarang = $request->get('idbarang');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        // Ambil data berdasarkan filter
        if ($idbarang) {
            $kartuStok = KartuStokModel::getByBarang($idbarang);
        } elseif ($start_date && $end_date) {
            $kartuStok = KartuStokModel::getByDateRange($start_date, $end_date);
        } else {
            $kartuStok = KartuStokModel::getAll();
        }

        // Ambil list barang untuk dropdown filter
        $barang = KartuStokModel::getAllBarang();

        return view('superadmin.transaksi.kartustok.index', compact('kartuStok', 'barang', 'idbarang', 'start_date', 'end_date'));
    }

    // 🔹 Tampilkan stok terkini (dashboard)
    public function stokTerkini()
    {
        $stok = KartuStokModel::getStokTerkini();
        return view('superadmin.transaksi.kartustok.stok-terkini', compact('stok'));
    }

    // 🔹 Tampilkan detail kartu stok per barang
    public function show($idbarang)
    {
        $barang = KartuStokModel::getStokBarang($idbarang);
        
        if (!$barang) {
            return redirect()->route('superadmin.transaksi.kartustok.index')
                ->with('error', 'Barang tidak ditemukan');
        }

        $history = KartuStokModel::getByBarang($idbarang);

        return view('superadmin.transaksi.kartustok.show', compact('barang', 'history'));
    }

    // 🔹 Tampilkan summary stok
    public function summary()
    {
        $summary = KartuStokModel::getSummary();
        return view('superadmin.transaksi.kartustok.summary', compact('summary'));
    }

    // 🔹 Export ke Excel/PDF (optional - belum dibuat)
    public function export(Request $request)
    {
        return back()->with('info', 'Fitur export sedang dalam pengembangan');
    }
}