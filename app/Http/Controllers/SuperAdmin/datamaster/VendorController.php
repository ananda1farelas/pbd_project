<?php

namespace App\Http\Controllers\Superadmin\Datamaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VendorModel;
use Illuminate\Support\Facades\DB;

class VendorController
{
    // 📋 Tampilkan daftar vendor (default: aktif)
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'aktif');

        if ($filter === 'aktif') {
            $vendor = DB::select("SELECT * FROM view_vendor_aktif ORDER BY nama_vendor ASC");
        } else {
            $vendor = DB::select("SELECT * FROM view_vendor ORDER BY nama_vendor ASC");
        }

        return view('superadmin.datamaster.vendor.index', compact('vendor', 'filter'));
    }

    // 🆕 Form tambah vendor
    public function create()
    {
        return view('superadmin.datamaster.vendor.create');
    }

    // 💾 Simpan vendor baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|string|max:100',
            'status' => 'required|in:0,1'
        ]);

        VendorModel::create([
            'nama_vendor' => $request->nama_vendor,
            'badan_hukum' => $request->badan_hukum,
            'status' => $request->status
        ]);

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan!');
    }

    // ✏️ Form edit vendor
    public function edit($id)
    {
        $vendorData = VendorModel::getById($id);
        $vendor = $vendorData[0] ?? null;

        if (!$vendor) {
            return redirect()->route('vendor.index')->with('error', 'Vendor tidak ditemukan!');
        }

        return view('superadmin.datamaster.vendor.edit', compact('vendor'));
    }

    // 🔄 Update vendor
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|string|max:100',
            'status' => 'required|in:0,1'
        ]);

        VendorModel::updateData($id, [
            'nama_vendor' => $request->nama_vendor,
            'badan_hukum' => $request->badan_hukum,
            'status' => $request->status
        ]);

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diperbarui!');
    }

    // ❌ Hapus vendor
    public function destroy($id)
    {
        try {
            VendorModel::deleteData($id);
            return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('vendor.index')->with('error', 'Gagal menghapus vendor: ' . $e->getMessage());
        }
    }
}
