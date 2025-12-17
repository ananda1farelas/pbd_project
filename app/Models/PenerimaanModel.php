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

    // 🔹 Ambil pengadaan yang bisa diterima (status Approved atau Proses)
    public static function getPengadaanApproved()
    {
        return DB::select("
            SELECT 
                pg.idpengadaan,
                pg.timestamp,
                v.nama_vendor,
                pg.total_nilai,
                pg.status,
                (CASE 
                    WHEN pg.status = 'A' THEN 'Approved - Belum ada penerimaan'
                    WHEN pg.status = 'P' THEN 'Proses - Penerimaan sebagian'
                    ELSE pg.status
                END) as status_label
            FROM pengadaan pg
            JOIN vendor v ON pg.vendor_idvendor = v.idvendor
            WHERE pg.status IN ('A', 'P')
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
            HAVING sisa_belum_terima >= 0
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

    // 🔹 Update status pengadaan berdasarkan progress penerimaan
    public static function updateStatusPengadaan($idpengadaan)
    {
        // Cek progress penerimaan
        $progress = DB::select("
            SELECT 
                SUM(CAST(dp.jumlah AS UNSIGNED)) as total_pesan,
                COALESCE(SUM(CAST(dpr.jumlah_terima AS UNSIGNED)), 0) as total_diterima
            FROM detail_pengadaan dp
            LEFT JOIN penerimaan pr ON pr.idpengadaan = dp.idpengadaan
            LEFT JOIN detail_penerimaan dpr ON dpr.idpenerimaan = pr.idpenerimaan 
                AND dpr.barang_idbarang = dp.idbarang
            WHERE dp.idpengadaan = ?
        ", [$idpengadaan]);

        if (!empty($progress)) {
            $total_pesan = $progress[0]->total_pesan;
            $total_diterima = $progress[0]->total_diterima;

            // Tentukan status pengadaan
            if ($total_diterima == 0) {
                // Belum ada penerimaan sama sekali -> tetap A
                $new_status = 'A';
            } elseif ($total_diterima >= $total_pesan) {
                // Semua barang sudah diterima -> S (Selesai)
                $new_status = 'S';
            } else {
                // Ada penerimaan tapi belum lengkap -> P (Proses)
                $new_status = 'P';
            }

            // Update status pengadaan
            DB::update("
                UPDATE pengadaan
                SET status = ?
                WHERE idpengadaan = ?
            ", [$new_status, $idpengadaan]);

            return $new_status;
        }

        return null;
    }

    // 🔹 Hitung status penerimaan (B/S/L)
public static function hitungStatusPenerimaan($idpenerimaan)
{
    $penerimaan = self::getById($idpenerimaan);
    if (!$penerimaan) return null;

    // total diterima di penerimaan ini
    $total_penerimaan_ini = DB::selectOne("
        SELECT COALESCE(SUM(CAST(jumlah_terima AS UNSIGNED)),0) AS total
        FROM detail_penerimaan
        WHERE idpenerimaan = ?
    ", [$idpenerimaan])->total;

    // cek apakah masih ada barang yang belum lengkap
    $sisa = DB::selectOne("
        SELECT COUNT(*) AS sisa
        FROM view_progress_penerimaan
        WHERE idpengadaan = ?
          AND sisa_belum_terima > 0
    ", [$penerimaan->idpengadaan]);

    if ($total_penerimaan_ini == 0) {
        $status = 'B';
    } elseif ($sisa->sisa == 0) {
        $status = 'L';
    } else {
        $status = 'S';
    }

    self::updateStatus($idpenerimaan, $status);
    return $status;
}


    public static function cekDanUpdateStatusPengadaan($idpengadaan)
    {
        $cek = DB::selectOne("
            SELECT COUNT(*) AS sisa
            FROM (
                SELECT dp.idbarang,
                    dp.jumlah AS jumlah_pesan,
                    COALESCE(SUM(dpr.jumlah_terima),0) AS total_terima
                FROM detail_pengadaan dp
                LEFT JOIN penerimaan pr ON pr.idpengadaan = dp.idpengadaan
                LEFT JOIN detail_penerimaan dpr 
                    ON dpr.idpenerimaan = pr.idpenerimaan
                    AND dpr.barang_idbarang = dp.idbarang
                WHERE dp.idpengadaan = ?
                GROUP BY dp.idbarang, dp.jumlah
                HAVING jumlah_pesan > total_terima
            ) x
        ", [$idpengadaan]);

        if ($cek->sisa == 0) {
            DB::update("
                UPDATE pengadaan
                SET status = 'S'
                WHERE idpengadaan = ?
            ", [$idpengadaan]);
        } else {
            DB::update("
                UPDATE pengadaan
                SET status = 'P'
                WHERE idpengadaan = ?
            ", [$idpengadaan]);
        }
    }


    // 🔹 Hapus penerimaan
    public static function deleteData($id)
    {
        $penerimaan = self::getById($id);
        if (!$penerimaan) return false;

        // Hapus detail dulu
        DB::delete("DELETE FROM detail_penerimaan WHERE idpenerimaan = ?", [$id]);
        
        // Hapus header
        $result = DB::delete("DELETE FROM penerimaan WHERE idpenerimaan = ?", [$id]);

        // Update status pengadaan setelah hapus penerimaan
        self::updateStatusPengadaan($penerimaan->idpengadaan);

        return $result;
    }

    // 🔹 Hapus detail penerimaan
    public static function deleteDetail($iddetail)
    {
        return DB::delete("DELETE FROM detail_penerimaan WHERE iddetail_penerimaan = ?", [$iddetail]);
    }
}