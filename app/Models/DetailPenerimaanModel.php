<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class DetailPenerimaanModel
{
    public static function getAll()
    {
        return DB::select("SELECT * FROM detail_penerimaan");
    }

    public static function getById($id)
    {
        return DB::select("SELECT * FROM detail_penerimaan WHERE iddetail_penerimaan = ?", [$id]);
    }

    public static function create($data)
    {
        return DB::insert("
            INSERT INTO detail_penerimaan (idpenerimaan, idbarang, jumlah_terima, harga_satuan_terima, sub_total_terima)
            VALUES (?, ?, ?, ?, ?)
        ", [$data['idpenerimaan'], $data['idbarang'], $data['jumlah_terima'], $data['harga_satuan_terima'], $data['sub_total_terima']]);
    }

    public static function updateData($id, $data)
    {
        return DB::update("
            UPDATE detail_penerimaan SET idpenerimaan = ?, idbarang = ?, jumlah_terima = ?, harga_satuan_terima = ?, sub_total_terima = ?
            WHERE iddetail_penerimaan = ?
        ", [$data['idpenerimaan'], $data['idbarang'], $data['jumlah_terima'], $data['harga_satuan_terima'], $data['sub_total_terima'], $id]);
    }

    public static function deleteData($id)
    {
        return DB::delete("DELETE FROM detail_penerimaan WHERE iddetail_penerimaan = ?", [$id]);
    }
}
