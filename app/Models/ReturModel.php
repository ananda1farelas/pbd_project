<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ReturModel
{
    // 🔹 Ambil semua data retur
    public static function getAll()
    {
        return DB::select("SELECT * FROM view_retur_lengkap");
    }

    // 🔹 Ambil retur berdasarkan ID
    public static function getById($id)
    {
        $result = DB::select("SELECT * FROM retur_barang WHERE idretur = ?", [$id]);
        return $result ? $result[0] : null;
    }

    // 🔹 Buat retur baru (pakai SP)
    public static function create($idpenerimaan, $iduser)
    {
        DB::statement("CALL sp_buat_retur(?, ?, @id_retur)", [
            $idpenerimaan,
            $iduser
        ]);
        
        $result = DB::select("SELECT @id_retur as idretur");
        return $result[0]->idretur;
    }

    // 🔹 Tambah detail retur (pakai SP)
    public static function addDetail($idretur, $iddetail_penerimaan, $jumlah, $alasan)
    {
        try {
            DB::statement("CALL sp_tambah_detail_retur(?, ?, ?, ?)", [
                $idretur,
                $iddetail_penerimaan,
                $jumlah,
                $alasan
            ]);
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    // 🔹 Ambil detail retur
    public static function getDetails($idretur)
    {
        return DB::select("
            SELECT * FROM view_detail_retur_lengkap
            WHERE idretur = ?
        ", [$idretur]);
    }

    // 🔹 Ambil penerimaan yang bisa di-retur
// Di ReturModel.php

    public static function getPenerimaanBisaRetur()
    {
        return \DB::select("
            SELECT 
                p.idpenerimaan,
                p.created_at AS tanggal_penerimaan,
                pg.idpengadaan,
                v.nama_vendor,
                p.status
            FROM penerimaan p
            JOIN pengadaan pg ON p.idpengadaan = pg.idpengadaan
            JOIN vendor v ON pg.vendor_idvendor = v.idvendor
            WHERE p.status = 'A'  -- Hanya tampilkan penerimaan yang sudah diterima
            AND EXISTS (
                -- Pastikan ada barang yang bisa diretur (belum diretur semua)
                SELECT 1 
                FROM detail_penerimaan dp
                WHERE dp.idpenerimaan = p.idpenerimaan
                AND dp.jumlah_terima > COALESCE(
                    (SELECT SUM(dr.jumlah) 
                    FROM detail_retur dr
                    JOIN retur_barang r ON dr.idretur = r.idretur
                    WHERE dr.iddetail_penerimaan = dp.iddetail_penerimaan),
                    0
                )
            )
            ORDER BY p.created_at DESC
        ");
    }

    // 🔹 Ambil detail barang dari penerimaan (untuk form retur)
    public static function getBarangPenerimaan($idpenerimaan)
    {
        return DB::select("
            SELECT 
                dp.iddetail_penerimaan,
                b.idbarang,
                b.nama as nama_barang,
                s.nama_satuan,
                CAST(dp.jumlah_terima AS UNSIGNED) as jumlah_terima,
                COALESCE(SUM(CAST(dr.jumlah AS UNSIGNED)), 0) as total_retur,
                (CAST(dp.jumlah_terima AS UNSIGNED) - COALESCE(SUM(CAST(dr.jumlah AS UNSIGNED)), 0)) as sisa_bisa_retur
            FROM detail_penerimaan dp
            JOIN barang b ON dp.barang_idbarang = b.idbarang
            JOIN satuan s ON b.idsatuan = s.idsatuan
            LEFT JOIN detail_retur dr ON dr.iddetail_penerimaan = dp.iddetail_penerimaan
            WHERE dp.idpenerimaan = ?
            GROUP BY dp.iddetail_penerimaan, b.idbarang, b.nama, s.nama_satuan, dp.jumlah_terima
            HAVING sisa_bisa_retur > 0
        ", [$idpenerimaan]);
    }

    // 🔹 Hapus retur
    public static function deleteData($id)
    {
        DB::delete("DELETE FROM detail_retur WHERE idretur = ?", [$id]);
        return DB::delete("DELETE FROM retur_barang WHERE idretur = ?", [$id]);
    }
}