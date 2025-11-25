<?php

namespace App\Http\Controllers\Superadmin\Datamaster;

use Illuminate\Http\Request;
use App\Models\SatuanModel;
use Illuminate\Support\Facades\DB;

class SatuanController
{
    // 📋 Menampilkan daftar satuan (default: aktif)
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'aktif');

        if ($filter === 'aktif') {
            $satuan = DB::select("SELECT * FROM view_satuan_aktif ORDER BY nama_satuan ASC");
        } else {
            $satuan = DB::select("SELECT * FROM view_satuan ORDER BY nama_satuan ASC");
        }

        return view('superadmin.datamaster.satuan.index', compact('satuan', 'filter'));
    }

    // 🆕 Form tambah satuan
    public function create()
    {
        return view('superadmin.datamaster.satuan.create');
    }

    // 💾 Simpan satuan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:45',
            'status' => 'required'
        ]);

        SatuanModel::create([
            'nama_satuan' => $request->nama_satuan,
            'status' => $request->status
        ]);

        return redirect()->route('superadmin.datamaster.satuan.index')->with('success', 'Satuan berhasil ditambahkan!');
    }

    // ✏️ Form edit satuan
    public function edit($id)
    {
        $satuan = SatuanModel::find($id);
        if (!$satuan) {
            return redirect()->route('superadmin.datamaster.satuan.index')->with('error', 'Satuan tidak ditemukan!');
        }

        return view('superadmin.datamaster.satuan.edit', compact('satuan'));
    }

    // 🔄 Update satuan
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:45',
            'status' => 'required'
        ]);

        $satuan = SatuanModel::find($id);
        if (!$satuan) {
            return redirect()->route('superadmin.datamaster.satuan.index')->with('error', 'Satuan tidak ditemukan!');
        }

        $satuan->update([
            'nama_satuan' => $request->nama_satuan,
            'status' => $request->status
        ]);

        return redirect()->route('superadmin.datamaster.satuan.index')->with('success', 'Satuan berhasil diperbarui!');
    }

    // ❌ Hapus satuan
    public function destroy($id)
    {
        try {
            $satuan = SatuanModel::find($id);
            if (!$satuan) {
                return redirect()->route('superadmin.datamaster.satuan.index')->with('error', 'Satuan tidak ditemukan!');
            }

            $satuan->delete();
            return redirect()->route('superadmin.datamaster.satuan.index')->with('success', 'Satuan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.datamaster.satuan.index')->with('error', 'Gagal menghapus satuan: ' . $e->getMessage());
        }
    }
}
