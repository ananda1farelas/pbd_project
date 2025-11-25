<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class DetailPenjualanModel
{
    public static function getAll()
    {
        return DB::select("SELECT * FROM detail_penjualan");
    }

    public static function getById($id)
    {
        return DB::select("SELECT * FROM detail_penjualan WHERE iddetail_penjualan = ?", [$id]);
    }

    public static function create($data)
    {
        return DB::insert("
            INSERT INTO detail_penjualan (harga_satuan, jumlah, subtotal, idpenjualan, idbarang)
            VALUES (?, ?, ?, ?, ?)
        ", [$data['harga_satuan'], $data['jumlah'], $data['subtotal'], $data['idpenjualan'], $data['idbarang']]);
    }

    public static function updateData($id, $data)
    {
        return DB::update("
            UPDATE detail_penjualan SET harga_satuan = ?, jumlah = ?, subtotal = ?, idpenjualan = ?, idbarang = ?
            WHERE iddetail_penjualan = ?
        ", [$data['harga_satuan'], $data['jumlah'], $data['subtotal'], $data['idpenjualan'], $data['idbarang'], $id]);
    }

    public static function deleteData($id)
    {
        return DB::delete("DELETE FROM detail_penjualan WHERE iddetail_penjualan = ?", [$id]);
    }
}
