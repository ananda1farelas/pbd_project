<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class VendorModel
{
    public static function getAll()
    {
        return DB::select("SELECT * FROM vendor");
    }

    public static function getById($id)
    {
        return DB::select("SELECT * FROM view_vendor WHERE idvendor = ?", [$id]);
    }

    public static function create($data)
    {
        return DB::insert("
            INSERT INTO vendor (nama_vendor, badan_hukum, status)
            VALUES (?, ?, ?)
        ", [$data['nama_vendor'], $data['badan_hukum'], $data['status']]);
    }

    public static function updateData($id, $data)
    {
        return DB::update("
            UPDATE vendor SET nama_vendor = ?, badan_hukum = ?, status = ?
            WHERE idvendor = ?
        ", [$data['nama_vendor'], $data['badan_hukum'], $data['status'], $id]);
    }

    public static function deleteData($id)
    {
        return DB::delete("DELETE FROM vendor WHERE idvendor = ?", [$id]);
    }
}
