<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class PengadaanModel
{
    // 🔹 Ambil semua data pengadaan
    public static function getAll()
    {
        return DB::select("
            SELECT 
                pg.idpengadaan,
                pg.timestamp,
                pg.status,  -- ✅ Status asli pengadaan
                pg.subtotal_nilai,
                pg.ppn,
                pg.total_nilai,
                v.nama_vendor,
                u.username,
                (CASE 
                    WHEN pg.status = 'P' THEN 'Pending'
                    WHEN pg.status = 'A' THEN 'Approved'
                    WHEN pg.status = 'C' THEN 'Cancelled'
                    WHEN pg.status = 'S' THEN 'Selesai'
                    ELSE 'Unknown'
                END) as status_label
            FROM pengadaan pg
            JOIN vendor v ON pg.vendor_idvendor = v.idvendor
            JOIN user u ON pg.user_iduser = u.iduser
            ORDER BY pg.timestamp DESC
        ");
    }

    // 🔹 Ambil pengadaan berdasarkan status
    public static function getByStatus($status)
    {
        return DB::select("
            SELECT 
                pg.idpengadaan,
                pg.timestamp,
                pg.status,
                pg.subtotal_nilai,
                pg.ppn,
                pg.total_nilai,
                v.nama_vendor,
                u.username,
                (CASE 
                    WHEN pg.status = 'P' THEN 'Pending'
                    WHEN pg.status = 'A' THEN 'Approved'
                    WHEN pg.status = 'C' THEN 'Cancelled'
                    WHEN pg.status = 'S' THEN 'Selesai'
                    ELSE 'Unknown'
                END) as status_label
            FROM pengadaan pg
            JOIN vendor v ON pg.vendor_idvendor = v.idvendor
            JOIN user u ON pg.user_iduser = u.iduser
            WHERE pg.status = ?
            ORDER BY pg.timestamp DESC
        ", [$status]);
    }

    // 🔹 Ambil data pengadaan berdasarkan ID
    public static function getById($id)
    {
        $result = DB::select("SELECT * FROM pengadaan WHERE idpengadaan = ?", [$id]);
        return $result ? $result[0] : null;
    }

    // 🔹 Buat pengadaan baru (pakai SP) - AUTO APPROVED
    public static function create($user_iduser, $vendor_idvendor, $autoApprove = true)
    {
        // Panggil SP untuk buat pengadaan
        DB::statement("CALL sp_buat_pengadaan(?, ?, @id_pengadaan)", [
            $user_iduser,
            $vendor_idvendor
        ]);
        
        $result = DB::select("SELECT @id_pengadaan as idpengadaan");
        $idpengadaan = $result[0]->idpengadaan;
        
        // 🔥 Auto approve jika diminta (default true)
        if ($autoApprove) {
            self::updateStatus($idpengadaan, 'A');
        }
        
        return $idpengadaan;
    }

    // 🔹 Tambah detail pengadaan (pakai SP)
    public static function addDetail($idpengadaan, $idbarang, $jumlah)
    {
        DB::statement("CALL sp_tambah_detail_pengadaan(?, ?, ?)", [
            $idpengadaan,
            $idbarang,
            $jumlah
        ]);
    }

    // 🔹 Ambil detail pengadaan
    public static function getDetails($idpengadaan)
    {
        return DB::select("
            SELECT * FROM view_detail_pengadaan
            WHERE idpengadaan = ?
        ", [$idpengadaan]);
    }

    // 🔹 Update status pengadaan (dengan validasi)
    public static function updateStatus($id, $status)
    {
        // Validasi status yang diizinkan
        $allowedStatus = ['P', 'A', 'C', 'S'];
        
        if (!in_array($status, $allowedStatus)) {
            throw new \Exception("Status tidak valid: {$status}");
        }
        
        return DB::update("
            UPDATE pengadaan
            SET status = ?
            WHERE idpengadaan = ?
        ", [$status, $id]);
    }

    // 🔹 Cek apakah pengadaan bisa di-cancel (hanya yang approved)
    public static function canBeCancelled($id)
    {
        $pengadaan = self::getById($id);
        return $pengadaan && $pengadaan->status === 'A';
    }

    // 🔹 Ambil semua vendor aktif (untuk dropdown)
    public static function getAllVendors()
    {
        return DB::select("SELECT * FROM view_vendor_aktif");
    }

    // 🔹 Ambil semua barang aktif (untuk dropdown)
    public static function getAllBarang()
    {
        return DB::select("SELECT * FROM view_barang_aktif");
    }

    // 🔹 Hapus detail pengadaan (tambah validasi)
    public static function deleteDetail($iddetail)
    {
        // Cek dulu apakah detail ada
        $exists = DB::select("SELECT 1 FROM detail_pengadaan WHERE iddetail_pengadaan = ?", [$iddetail]);
        
        if (!$exists) {
            throw new \Exception("Detail pengadaan tidak ditemukan");
        }
        
        return DB::delete("DELETE FROM detail_pengadaan WHERE iddetail_pengadaan = ?", [$iddetail]);
    }
}