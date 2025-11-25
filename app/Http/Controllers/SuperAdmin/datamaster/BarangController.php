<?php

namespace App\Http\Controllers\Superadmin\Datamaster;

use Illuminate\Http\Request;
use App\Models\BarangModel;
use Illuminate\Support\Facades\DB;

class BarangController
{
    // 📋 Menampilkan daftar barang
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'aktif');
        $keyword = $request->query('keyword', '');

        // Jika ada keyword → gunakan Stored Procedure
        if (!empty($keyword)) {
            $barang = DB::select("CALL sp_daftar_barang(?)", [$keyword]);
        } else {
            // Jika tanpa keyword → gunakan view biasa
            $viewBarang = ($filter === 'aktif') ? 'view_barang_aktif' : 'view_barang';
            $barang = DB::select("SELECT * FROM $viewBarang ORDER BY nama_barang ASC");
        }

        // 🟩 Mapping jenis → kategori
        $kategoriMap = [
            'A' => 'Alat Tulis',
            'B' => 'Kertas',
            'C' => 'Buku',
            'D' => 'Peralatan Kantor',
            'E' => 'ATK Lain',
            'F' => 'Aksesoris',
            'G' => 'Elektronik Kecil',
            'H' => 'Kebersihan',
            'I' => 'Minuman'
        ];

        foreach ($barang as $item) {
            $item->kategori = $kategoriMap[$item->jenis] ?? 'Lainnya';
        }

        return view('superadmin.datamaster.barang.index', compact('barang', 'filter', 'keyword'));
    }

    // 🆕 Form tambah barang
    public function create()
    {
        $satuan = DB::select("SELECT * FROM view_satuan_aktif ORDER BY nama_satuan ASC");
        return view('superadmin.datamaster.barang.create', compact('satuan'));
    }

    // 💾 Simpan barang baru
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required',
            'nama' => 'required',
            'idsatuan' => 'required',
            'status' => 'required',
            'harga_satuan' => 'required|numeric'
        ]);

        BarangModel::create($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    // ✏️ Form edit barang
    public function edit($id)
    {
        $barang = BarangModel::getById($id);
        if (empty($barang)) {
            return redirect()->route('superadmin.datamaster.barang.index')->with('error', 'Barang tidak ditemukan!');
        }

        $satuan = DB::select("SELECT * FROM view_satuan_aktif ORDER BY nama_satuan ASC");
        return view('superadmin.datamaster.barang.edit', [
            'barang' => $barang[0],
            'satuan' => $satuan
        ]);
    }

    // 🔄 Update data barang
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required',
            'nama' => 'required',
            'idsatuan' => 'required',
            'status' => 'required',
            'harga_satuan' => 'required|numeric'
        ]);

        BarangModel::updateData($id, $request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    // ❌ Hapus barang
    public function destroy($id)
    {
        try {
            BarangModel::deleteData($id);
            return redirect()->route('superadmin.datamaster.barang.index')->with('success', 'Barang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.datamaster.barang.index')->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }
}
