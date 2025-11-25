<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class KartuStokModel
{
    // 🔹 Ambil semua data kartu stok dengan info lengkap
    public static function getAll()
    {
        return DB::select("
            SELECT 
                ks.idkartu_stok,
                ks.jenis_transaksi,
                CASE 
                    WHEN ks.jenis_transaksi = 'M' THEN 'Masuk'
                    WHEN ks.jenis_transaksi = 'K' THEN 'Keluar'
                    ELSE 'Unknown'
                END AS jenis_transaksi_label,
                CAST(ks.masuk AS UNSIGNED) AS masuk,
                CAST(ks.keluar AS UNSIGNED) AS keluar,
                CAST(ks.stock AS UNSIGNED) AS stock,
                ks.created_at,
                ks.idtransaksi,
                b.idbarang,
                b.nama AS nama_barang,
                s.nama_satuan
            FROM kartu_stok ks
            JOIN barang b ON ks.idbarang = b.idbarang
            JOIN satuan s ON b.idsatuan = s.idsatuan
            ORDER BY ks.created_at DESC
        ");
    }

    // 🔹 Ambil kartu stok per barang (history lengkap)
    public static function getByBarang($idbarang)
    {
        return DB::select("
            SELECT 
                ks.idkartu_stok,
                ks.jenis_transaksi,
                CASE 
                    WHEN ks.jenis_transaksi = 'M' THEN 'Masuk'
                    WHEN ks.jenis_transaksi = 'K' THEN 'Keluar'
                    ELSE 'Unknown'
                END AS jenis_transaksi_label,
                CAST(ks.masuk AS UNSIGNED) AS masuk,
                CAST(ks.keluar AS UNSIGNED) AS keluar,
                CAST(ks.stock AS UNSIGNED) AS stock,
                ks.created_at,
                ks.idtransaksi,
                b.idbarang,
                b.nama AS nama_barang,
                s.nama_satuan
            FROM kartu_stok ks
            JOIN barang b ON ks.idbarang = b.idbarang
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE ks.idbarang = ?
            ORDER BY ks.created_at DESC
        ", [$idbarang]);
    }

    // 🔹 Ambil stok terkini semua barang
// 🔹 Ambil stok terkini semua barang
    public static function getStokTerkini()
    {
        return DB::select("
            SELECT 
                b.idbarang,
                b.nama AS nama_barang,
                s.nama_satuan,
                COALESCE(CAST(ks.stock AS UNSIGNED), 0) AS stok_tersedia,
                ks.created_at AS update_terakhir
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan
            LEFT JOIN (
                SELECT 
                    idbarang,
                    stock,
                    created_at
                FROM kartu_stok ks1
                WHERE created_at = (
                    SELECT MAX(created_at)
                    FROM kartu_stok ks2
                    WHERE ks2.idbarang = ks1.idbarang
                )
            ) ks ON ks.idbarang = b.idbarang
            WHERE b.status = '1' OR b.status = 'Aktif'
            ORDER BY b.nama
        ");
    }

    // 🔹 Ambil stok barang tertentu (latest)
    public static function getStokBarang($idbarang)
    {
        $result = DB::select("
            SELECT 
                b.idbarang,
                b.nama AS nama_barang,
                s.nama_satuan,
                COALESCE(CAST(ks.stock AS UNSIGNED), 0) AS stok_tersedia,
                ks.created_at AS update_terakhir
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan
            LEFT JOIN kartu_stok ks ON ks.idbarang = b.idbarang
                AND ks.created_at = (
                    SELECT MAX(k2.created_at)
                    FROM kartu_stok k2
                    WHERE k2.idbarang = b.idbarang
                )
            WHERE b.idbarang = ?
        ", [$idbarang]);
        
        return $result ? $result[0] : null;
    }

    // 🔹 Filter kartu stok berdasarkan tanggal
    public static function getByDateRange($start_date, $end_date)
    {
        return DB::select("
            SELECT 
                ks.idkartu_stok,
                ks.jenis_transaksi,
                CASE 
                    WHEN ks.jenis_transaksi = 'M' THEN 'Masuk'
                    WHEN ks.jenis_transaksi = 'K' THEN 'Keluar'
                    ELSE 'Unknown'
                END AS jenis_transaksi_label,
                CAST(ks.masuk AS UNSIGNED) AS masuk,
                CAST(ks.keluar AS UNSIGNED) AS keluar,
                CAST(ks.stock AS UNSIGNED) AS stock,
                ks.created_at,
                ks.idtransaksi,
                b.idbarang,
                b.nama AS nama_barang,
                s.nama_satuan
            FROM kartu_stok ks
            JOIN barang b ON ks.idbarang = b.idbarang
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE DATE(ks.created_at) BETWEEN ? AND ?
            ORDER BY ks.created_at DESC
        ", [$start_date, $end_date]);
    }

    // 🔹 Ambil summary stok (total masuk/keluar per barang)
    public static function getSummary()
    {
        return DB::select("
            SELECT 
                b.idbarang,
                b.nama AS nama_barang,
                s.nama_satuan,
                COALESCE(SUM(CAST(ks.masuk AS UNSIGNED)), 0) AS total_masuk,
                COALESCE(SUM(CAST(ks.keluar AS UNSIGNED)), 0) AS total_keluar,
                COALESCE(MAX(CAST(ks.stock AS UNSIGNED)), 0) AS stok_akhir
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan
            LEFT JOIN kartu_stok ks ON ks.idbarang = b.idbarang
            WHERE b.status = '1' OR b.status = 'Aktif'
            GROUP BY b.idbarang, b.nama, s.nama_satuan
            ORDER BY b.nama
        ");
    }

    // 🔹 Ambil semua barang (untuk dropdown filter)
    public static function getAllBarang()
    {
        return DB::select("
            SELECT 
                idbarang,
                nama AS nama_barang
            FROM barang
            WHERE status = '1' OR status = 'Aktif'
            ORDER BY nama
        ");
    }
}