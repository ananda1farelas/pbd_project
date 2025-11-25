<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class DetailPengadaanModel
{
    public static function getAll()
    {
        return DB::select("SELECT * FROM detail_pengadaan");
    }

    public static function getById($id)
    {
        return DB::select("SELECT * FROM detail_pengadaan WHERE iddetail_pengadaan = ?", [$id]);
    }

    public static function create($data)
    {
        return DB::insert("
            INSERT INTO detail_pengadaan (harga_satuan, jumlah, sub_total, idbarang, idpengadaan)
            VALUES (?, ?, ?, ?, ?)
        ", [$data['harga_satuan'], $data['jumlah'], $data['sub_total'], $data['idbarang'], $data['idpengadaan']]);
    }

    public static function updateData($id, $data)
    {
        return DB::update("
            UPDATE detail_pengadaan SET harga_satuan = ?, jumlah = ?, sub_total = ?, idbarang = ?, idpengadaan = ?
            WHERE iddetail_pengadaan = ?
        ", [$data['harga_satuan'], $data['jumlah'], $data['sub_total'], $data['idbarang'], $data['idpengadaan'], $id]);
    }

    public static function deleteData($id)
    {
        return DB::delete("DELETE FROM detail_pengadaan WHERE iddetail_pengadaan = ?", [$id]);
    }
}
