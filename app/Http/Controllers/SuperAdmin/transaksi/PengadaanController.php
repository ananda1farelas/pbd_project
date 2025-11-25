<?php

namespace App\Http\Controllers\Superadmin\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengadaanModel;
use Illuminate\Support\Facades\Auth;

class PengadaanController extends Controller
{
    // 🔹 Tampilkan halaman list pengadaan
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        if ($status) {
            $pengadaan = PengadaanModel::getByStatus($status);
        } else {
            $pengadaan = PengadaanModel::getAll();
        }
        
        return view('superadmin.transaksi.pengadaan.index', compact('pengadaan', 'status'));
    }

    // 🔹 Tampilkan form tambah pengadaan
    public function create()
    {
        $vendors = PengadaanModel::getAllVendors();
        $barang = PengadaanModel::getAllBarang();
        return view('superadmin.transaksi.pengadaan.create', compact('vendors', 'barang'));
    }

    // 🔹 Proses simpan pengadaan baru (AUTO APPROVED)
    public function store(Request $request)
    {
        $request->validate([
            'vendor_idvendor' => 'required',
            'barang' => 'required|array|min:1',
            'barang.*.idbarang' => 'required',
            'barang.*.jumlah' => 'required|integer|min:1'
        ]);

        try {
            $user_iduser = Auth::id() ?? $request->user_iduser ?? '1';
            $vendor_idvendor = $request->vendor_idvendor;
            
            // Create otomatis approve
            $idpengadaan = PengadaanModel::create($user_iduser, $vendor_idvendor);

            foreach ($request->barang as $item) {
                PengadaanModel::addDetail(
                    $idpengadaan,
                    $item['idbarang'],
                    $item['jumlah']
                );
            }

            return redirect()->route('pengadaan.show', $idpengadaan)
                ->with('success', 'Pengadaan berhasil dibuat dan disetujui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat pengadaan: ' . $e->getMessage())->withInput();
        }
    }

    // 🔹 Tampilkan detail pengadaan
    public function show($id)
    {
        $pengadaan = PengadaanModel::getById($id);
        $details = PengadaanModel::getDetails($id);
        
        if (!$pengadaan) {
            return redirect()->route('pengadaan.index')
                ->with('error', 'Pengadaan tidak ditemukan');
        }

        return view('superadmin.transaksi.pengadaan.show', compact('pengadaan', 'details'));
    }

    // 🔹 Approve pengadaan (opsional, karena sudah auto approve)
    public function approve($id)
    {
        try {
            PengadaanModel::updateStatus($id, 'A');
            return back()->with('success', 'Pengadaan berhasil disetujui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui pengadaan: ' . $e->getMessage());
        }
    }

    // 🔹 Cancel pengadaan (hanya untuk yang sudah approved)
    public function cancel($id)
    {
        try {
            PengadaanModel::updateStatus($id, 'C');
            return redirect()->route('pengadaan.index')
                ->with('success', 'Pengadaan berhasil dibatalkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan pengadaan: ' . $e->getMessage());
        }
    }
}