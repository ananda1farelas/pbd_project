<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class PenjualanModel
{
    // 🔹 Ambil semua data penjualan dengan info lengkap
    public static function getAll()
    {
        return DB::select("
            SELECT 
                p.idpenjualan,
                p.created_at,
                u.username,
                mp.persen as margin,
                p.subtotal_nilai,
                p.ppn,
                p.total_nilai
            FROM penjualan p
            JOIN user u ON p.iduser = u.iduser
            JOIN margin_penjualan mp ON p.idmargin_penjualan = mp.idmargin_penjualan
            ORDER BY p.created_at DESC
        ");
    }

    // 🔹 Ambil data penjualan berdasarkan ID
    public static function getById($id)
    {
        $result = DB::select("SELECT * FROM penjualan WHERE idpenjualan = ?", [$id]);
        return $result ? $result[0] : null;
    }

    // 🔹 Buat penjualan baru (pakai SP)
    public static function create($iduser)
    {
        // Panggil SP untuk buat penjualan (gak perlu margin lagi)
        DB::statement("CALL sp_buat_penjualan(?, @id_penjualan, @id_margin)", [
            $iduser
        ]);
        
        // Ambil ID yang di-generate
        $result = DB::select("SELECT @id_penjualan as idpenjualan, @id_margin as idmargin");
        return [
            'idpenjualan' => $result[0]->idpenjualan,
            'idmargin' => $result[0]->idmargin
        ];
    }

    // 🔹 Tambah detail penjualan (pakai SP)
    public static function addDetail($idpenjualan, $idbarang, $jumlah)
    {
        try {
            DB::statement("CALL sp_tambah_detail_penjualan(?, ?, ?)", [
                $idpenjualan,
                $idbarang,
                $jumlah
            ]);
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    // 🔹 Ambil detail penjualan
    public static function getDetails($idpenjualan)
    {
        return DB::select("
            SELECT 
                dp.iddetail_penjualan,
                b.nama as nama_barang,
                s.nama_satuan,
                b.harga_satuan as harga_asli,
                CAST(dp.harga_satuan AS UNSIGNED) as harga_jual,
                CAST(dp.jumlah AS UNSIGNED) as jumlah,
                CAST(dp.subtotal AS UNSIGNED) as subtotal
            FROM detail_penjualan dp
            JOIN barang b ON dp.idbarang = b.idbarang
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE dp.idpenjualan = ?
        ", [$idpenjualan]);
    }

    // 🔹 Ambil margin aktif (untuk dropdown)
    public static function getMarginAktifTerkini()
    {
        $result = DB::select("
            SELECT * FROM margin_penjualan
            WHERE status = '1' OR status = 'aktif'
            ORDER BY created_at DESC
            LIMIT 1
        ");
        
        return $result ? $result[0] : null;
    }

    // 🔹 Ambil barang yang ready stock (untuk dropdown)
    public static function getBarangReadyStock()
    {
        return DB::select("
            SELECT 
                b.idbarang,
                b.nama as nama_barang,
                s.nama_satuan,
                b.harga_satuan,
                COALESCE(ks.stock, 0) as stok_tersedia,
                b.status
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan
            LEFT JOIN kartu_stok ks ON ks.idbarang = b.idbarang
                AND ks.created_at = (
                    SELECT MAX(k2.created_at)
                    FROM kartu_stok k2
                    WHERE k2.idbarang = b.idbarang
                )
            WHERE (b.status = '1' OR b.status = 'Aktif')
            AND COALESCE(ks.stock, 0) > 0
            ORDER BY b.nama
        ");
    }

    // 🔹 Cek stok barang (untuk validasi)
    public static function getStokBarang($idbarang)
    {
        $result = DB::select("
            SELECT COALESCE(stock, 0) as stok
            FROM kartu_stok
            WHERE idbarang = ?
            ORDER BY created_at DESC
            LIMIT 1
        ", [$idbarang]);
        
        return $result ? $result[0]->stok : 0;
    }

    // 🔹 Hitung harga jual dengan margin (untuk preview)
    public static function hitungHargaJualAuto($idbarang)
    {
        try {
            $result = DB::select("
                SELECT fn_hitung_harga_jual_auto(?) as harga_jual
            ", [$idbarang]);
            
            return $result && isset($result[0]->harga_jual) ? $result[0]->harga_jual : 0;
        } catch (\Exception $e) {
            \Log::error('Error hitungHargaJualAuto: ' . $e->getMessage());
            return 0;
        }
    }

    // 🔹 Hapus penjualan
    public static function deleteData($id)
    {
        // Hapus detail dulu
        DB::delete("DELETE FROM detail_penjualan WHERE idpenjualan = ?", [$id]);
        
        // Baru hapus header
        return DB::delete("DELETE FROM penjualan WHERE idpenjualan = ?", [$id]);
    }

    // 🔹 Hapus detail penjualan
    public static function deleteDetail($iddetail)
    {
        return DB::delete("DELETE FROM detail_penjualan WHERE iddetail_penjualan = ?", [$iddetail]);
    }
}