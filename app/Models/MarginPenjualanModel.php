<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class MarginPenjualanModel
{
    public static function getAll()
    {
        return DB::select("SELECT * FROM margin_penjualan");
    }

    public static function getById($id)
    {
        return DB::select("SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?", [$id]);
    }

    public static function create($data)
    {
        return DB::insert("
            INSERT INTO margin_penjualan (persen, status, iduser)
            VALUES (?, ?, ?)
        ", [$data['persen'], $data['status'], $data['iduser']]);
    }

    public static function updateData($id, $data)
    {
        return DB::update("
            UPDATE margin_penjualan SET persen = ?, status = ?, iduser = ?
            WHERE idmargin_penjualan = ?
        ", [$data['persen'], $data['status'], $data['iduser'], $id]);
    }

    public static function deleteData($id)
    {
        return DB::delete("DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?", [$id]);
    }
}
