<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class BarangModel
{
    protected $table = 'barang';

    // 🔹 Ambil semua data barang
    public static function getAll()
    {
        return DB::select("SELECT * FROM barang");
    }

    // 🔹 Ambil data barang berdasarkan ID
    public static function getById($id)
    {
        return DB::select("SELECT * FROM barang WHERE idbarang = ?", [$id]);
    }

    // 🔹 Tambah data barang baru
    public static function create($data)
    {
        return DB::insert("
            INSERT INTO barang (idbarang, jenis, nama, idsatuan, status, harga_satuan)
            VALUES (?, ?, ?, ?, ?, ?)
        ", [
            $data['idbarang'],
            $data['jenis'],
            $data['nama'],
            $data['idsatuan'],
            $data['status'],
            $data['harga_satuan'],
        ]);
    }
    
    // 🔹 Update data barang
    public static function updateData($id, $data)
    {
        return DB::update("
            UPDATE barang 
            SET jenis = ?, 
                nama = ?, 
                idsatuan = ?, 
                status = ?, 
                harga_satuan = ?
            WHERE idbarang = ?
        ", [
            $data['jenis'],
            $data['nama'],
            $data['idsatuan'],
            $data['status'],
            $data['harga_satuan'],
            $id
        ]);
    }

    // 🔹 Hapus data barang
    public static function deleteData($id)
    {
        return DB::delete("DELETE FROM barang WHERE idbarang = ?", [$id]);
    }
}
