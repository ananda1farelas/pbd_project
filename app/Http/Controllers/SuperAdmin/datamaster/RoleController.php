<?php

namespace App\Http\Controllers\Superadmin\Datamaster;

use Illuminate\Http\Request;
use App\Models\RoleModel;
use Illuminate\Support\Facades\DB;

class RoleController
{
    // 📋 Tampilkan semua role
    public function index()
    {
        // ambil dari view_role biar rapi
        $role = DB::select("SELECT * FROM view_role ORDER BY idrole ASC");
        return view('superadmin.datamaster.role.index', compact('role'));
    }

    // 🆕 Form tambah role
    public function create()
    {
        return view('superadmin.datamaster.role.create');
    }

    // 💾 Simpan role baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|string|max:100'
        ]);

        RoleModel::create([
            'nama_role' => $request->nama_role
        ]);

        return redirect()->route('superadmin.datamaster.role.index')->with('success', 'Role berhasil ditambahkan!');
    }

    // ✏️ Form edit role
    public function edit($id)
    {
        $role = RoleModel::find($id);
        if (!$role) {
            return redirect()->route('superadmin.datamaster.role.index')->with('error', 'Role tidak ditemukan!');
        }

        return view('superadmin.datamaster.role.edit', compact('role'));
    }

    // 🔄 Update role
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_role' => 'required|string|max:100'
        ]);

        $role = RoleModel::find($id);
        if (!$role) {
            return redirect()->route('superadmin.datamaster.role.index')->with('error', 'Role tidak ditemukan!');
        }

        $role->update(['nama_role' => $request->nama_role]);

        return redirect()->route('superadmin.datamaster.role.index')->with('success', 'Role berhasil diperbarui!');
    }

    // ❌ Hapus role
    public function destroy($id)
    {
        try {
            $role = RoleModel::find($id);
            if (!$role) {
                return redirect()->route('superadmin.datamaster.role.index')->with('error', 'Role tidak ditemukan!');
            }

            $role->delete();
            return redirect()->route('superadmin.datamaster.role.index')->with('success', 'Role berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.datamaster.role.index')->with('error', 'Gagal menghapus role: ' . $e->getMessage());
        }
    }
}
