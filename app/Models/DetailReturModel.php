<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class DetailReturModel
{
    public static function getAll()
    {
        return DB::select("SELECT * FROM detail_retur");
    }

    public static function getById($id)
    {
        return DB::select("SELECT * FROM detail_retur WHERE iddetail_retur = ?", [$id]);
    }

    public static function create($data)
    {
        return DB::insert("
            INSERT INTO detail_retur (jumlah, alasan, idretur, iddetail_penerimaan)
            VALUES (?, ?, ?, ?)
        ", [$data['jumlah'], $data['alasan'], $data['idretur'], $data['iddetail_penerimaan']]);
    }

    public static function updateData($id, $data)
    {
        return DB::update("
            UPDATE detail_retur SET jumlah = ?, alasan = ?, idretur = ?, iddetail_penerimaan = ?
            WHERE iddetail_retur = ?
        ", [$data['jumlah'], $data['alasan'], $data['idretur'], $data['iddetail_penerimaan'], $id]);
    }

    public static function deleteData($id)
    {
        return DB::delete("DELETE FROM detail_retur WHERE iddetail_retur = ?", [$id]);
    }
}
