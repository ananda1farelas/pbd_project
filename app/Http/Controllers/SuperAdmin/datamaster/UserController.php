<?php

namespace App\Http\Controllers\Superadmin\Datamaster;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController
{
    // 📋 Menampilkan semua user
    public function index()
    {
        // Ambil dari view_user (sudah include nama role)
        $users = DB::select("SELECT * FROM view_user ORDER BY iduser ASC");
        return view('superadmin.datamaster.user.index', compact('users'));
    }

    // 🆕 Form tambah user
    public function create()
    {
        // Ambil role yang tersedia untuk dropdown
        $roles = DB::select("SELECT * FROM view_role ORDER BY idrole ASC");
        return view('superadmin.datamaster.user.create', compact('roles'));
    }

    // 💾 Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:45|unique:user,username',
            'password' => 'required|string|min:6|confirmed',
            'idrole' => 'required'
        ]);

        UserModel::create([
            'username' => $request->username,
            'password' => Hash::make($request->password), // Hash password
            'idrole'   => $request->idrole
        ]);

        return redirect()->route('superadmin.datamaster.user.index')->with('success', 'User berhasil ditambahkan!');
    }

    // ✏️ Form edit user
    public function edit($id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            return redirect()->route('superadmin.datamaster.user.index')->with('error', 'User tidak ditemukan!');
        }

        $roles = DB::select("SELECT * FROM view_role ORDER BY idrole ASC");

        // pastikan variabel $user langsung object, bukan array
        return view('superadmin.datamaster.user.edit', compact('user', 'roles'));
    }

    // 🔄 Update user
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:45|unique:user,username,' . $id . ',iduser',
            'password' => 'nullable|string|min:6|confirmed',
            'idrole' => 'required'
        ]);

        $user = UserModel::find($id);
        if (!$user) {
            return redirect()->route('superadmin.datamaster.user.index')->with('error', 'User tidak ditemukan!');
        }

        // Update data
        $user->username = $request->username;
        $user->idrole = $request->idrole;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }

    // ❌ Hapus user
    public function destroy($id)
    {
        try {
            $user = UserModel::find($id);
            if (!$user) {
                return redirect()->route('superadmin.datamaster.user.index')->with('error', 'User tidak ditemukan!');
            }

            $user->delete();
            return redirect()->route('superadmin.datamaster.user.index')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.datamaster.user.index')->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}
