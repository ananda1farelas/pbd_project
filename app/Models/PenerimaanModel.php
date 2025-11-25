<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class PenerimaanModel
{
    // 🔹 Ambil semua data penerimaan dengan info lengkap
    public static function getAll()
    {
        return DB::select("SELECT * FROM view_penerimaan_rekap");
    }

    // 🔹 Ambil data penerimaan berdasarkan ID
    public static function getById($id)
    {
        $result = DB::select("SELECT * FROM penerimaan WHERE idpenerimaan = ?", [$id]);
        return $result ? $result[0] : null; 
    }

    // 🔹 Buat penerimaan baru (pakai SP)
    public static function create($idpengadaan, $iduser)
    {
        // Panggil SP untuk buat penerimaan
        DB::statement("CALL sp_buat_penerimaan(?, ?, @id_penerimaan)", [
            $idpengadaan,
            $iduser
        ]);
        
        // Ambil ID yang di-generate
        $result = DB::select("SELECT @id_penerimaan as idpenerimaan");
        return $result[0]->idpenerimaan;
    }

    // 🔹 Tambah detail penerimaan (pakai SP)
    public static function addDetail($idpenerimaan, $idbarang, $jumlah_terima)
    {
        try {
            DB::statement("CALL sp_tambah_detail_penerimaan(?, ?, ?)", [
                $idpenerimaan,
                $idbarang,
                $jumlah_terima
            ]);
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    // 🔹 Ambil detail penerimaan
    public static function getDetails($idpenerimaan)
    {
        return DB::select("
            SELECT * FROM view_detail_penerimaan
            WHERE idpenerimaan = ?
        ", [$idpenerimaan]);
    }

    // 🔹 Ambil pengadaan yang bisa diterima (status Approved)
    public static function getPengadaanApproved()
    {
        return DB::select("
            SELECT 
                pg.idpengadaan,
                pg.timestamp,
                v.nama_vendor,
                pg.total_nilai,
                pg.status
            FROM pengadaan pg
            JOIN vendor v ON pg.vendor_idvendor = v.idvendor
            WHERE pg.status = 'A'
            ORDER BY pg.timestamp DESC
        ");
    }

    // 🔹 Ambil detail barang dari pengadaan (untuk form penerimaan)
    public static function getBarangPengadaan($idpengadaan)
    {
        return DB::select("
            SELECT 
                dp.idbarang,
                b.nama as nama_barang,
                s.nama_satuan,
                CAST(dp.jumlah AS UNSIGNED) as jumlah_pesan,
                CAST(dp.harga_satuan AS UNSIGNED) as harga_satuan,
                COALESCE(SUM(CAST(dpr.jumlah_terima AS UNSIGNED)), 0) as total_diterima,
                (CAST(dp.jumlah AS UNSIGNED) - COALESCE(SUM(CAST(dpr.jumlah_terima AS UNSIGNED)), 0)) as sisa_belum_terima
            FROM detail_pengadaan dp
            JOIN barang b ON dp.idbarang = b.idbarang
            JOIN satuan s ON b.idsatuan = s.idsatuan
            LEFT JOIN penerimaan pr ON pr.idpengadaan = dp.idpengadaan
            LEFT JOIN detail_penerimaan dpr ON dpr.idpenerimaan = pr.idpenerimaan 
                AND dpr.barang_idbarang = dp.idbarang
            WHERE dp.idpengadaan = ?
            GROUP BY dp.idbarang, b.nama, s.nama_satuan, dp.jumlah, dp.harga_satuan
            HAVING sisa_belum_terima > 0
        ", [$idpengadaan]);
    }

    // 🔹 Cek progress penerimaan pengadaan
    public static function getProgressPenerimaan($idpengadaan)
    {
        return DB::select("
            SELECT * FROM view_progress_penerimaan
            WHERE idpengadaan = ?
        ", [$idpengadaan]);
    }

    // 🔹 Update status penerimaan
    public static function updateStatus($id, $status)
    {
        return DB::update("
            UPDATE penerimaan
            SET status = ?
            WHERE idpenerimaan = ?
        ", [$status, $id]);
    }

    // 🔹 Hapus penerimaan
    public static function deleteData($id)
    {
        // Hapus detail dulu
        DB::delete("DELETE FROM detail_penerimaan WHERE idpenerimaan = ?", [$id]);
        
        // Baru hapus header
        return DB::delete("DELETE FROM penerimaan WHERE idpenerimaan = ?", [$id]);
    }

    // 🔹 Hapus detail penerimaan
    public static function deleteDetail($iddetail)
    {
        return DB::delete("DELETE FROM detail_penerimaan WHERE iddetail_penerimaan = ?", [$iddetail]);
    }
}