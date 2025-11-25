<?php

namespace App\Http\Controllers\Superadmin\Datamaster;

use Illuminate\Http\Request;
use App\Models\MarginPenjualanModel;
use Illuminate\Support\Facades\DB;

class MarginController
{
    // 📋 Menampilkan daftar margin penjualan (default: aktif)
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'aktif');

        if ($filter === 'aktif') {
            // 🔹 Ambil dari view_margin_penjualan_aktif
            $margin = DB::select("SELECT * FROM view_margin_penjualan_aktif ORDER BY created_at DESC");
        } else {
            // 🔹 Ambil dari view_margin_penjualan
            $margin = DB::select("SELECT * FROM view_margin_penjualan ORDER BY created_at DESC");
        }

        return view('superadmin.datamaster.margin.index', compact('margin', 'filter'));
    }

    // 🆕 Form tambah margin
    public function create()
    {
        // Ambil user yang bisa dipilih untuk pencatat margin
        $user = DB::select("SELECT iduser, username FROM view_user ORDER BY username ASC");
        return view('superadmin.datamaster.margin.create', compact('user'));
    }

    // 💾 Simpan margin baru
    public function store(Request $request)
    {
        $request->validate([
            'persen' => 'required|numeric|min:0|max:100',
            'status' => 'required',
            'iduser' => 'required'
        ]);

        MarginPenjualanModel::create($request->all());
        return redirect()->route('superadmin.datamaster.margin.index')->with('success', 'Margin penjualan berhasil ditambahkan!');
    }

    // ✏️ Form edit margin
    public function edit($id)
    {
        $margin = MarginPenjualanModel::getById($id);
        if (empty($margin)) {
            return redirect()->route('superadmin.datamaster.margin.index')->with('error', 'Data margin tidak ditemukan!');
        }

        $user = DB::select("SELECT iduser, username FROM view_user ORDER BY username ASC");

        return view('superadmin.datamaster.margin.edit', [
            'margin' => $margin[0],
            'user' => $user
        ]);
    }

    // 🔄 Update margin
    public function update(Request $request, $id)
    {
        $request->validate([
            'persen' => 'required|numeric|min:0|max:100',
            'status' => 'required',
            'iduser' => 'required'
        ]);

        MarginPenjualanModel::updateData($id, $request->all());
        return redirect()->route('superadmin.margin.index')->with('success', 'Data margin berhasil diperbarui!');
    }

    // ❌ Hapus margin
    public function destroy($id)
    {
        try {
            MarginPenjualanModel::deleteData($id);
            return redirect()->route('superadmin.datamaster.margin.index')->with('success', 'Data margin berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.datamaster.margin.index')->with('error', 'Gagal menghapus margin: ' . $e->getMessage());
        }
    }
}
