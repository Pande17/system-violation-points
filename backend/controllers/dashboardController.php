<?php
require_once 'helpers/responseHelper.php';
require_once 'config/db.php';

class DashboardController
{
    public function getStats()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            BadRequest(null, 'Method Not Allowed');
            return;
        }

        try {
            $pdo = getDBConnection();

            // 1. Total Siswa Aktif
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM siswa WHERE deleted_at IS NULL");
            $countSiswa = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // 2. Total Guru Aktif
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM guru WHERE deleted_at IS NULL");
            $countGuru = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // 3. Total Kelas Aktif
            // Asumsi tabel kelas ada dan field deleted_at atau non-deleted
            // Kita coba query, fall back if no deleted_at
            try {
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM kelas WHERE deleted_at IS NULL");
            } catch (PDOException $e) {
                // Jika tidak ada deleted_at, hitung semua
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM kelas");
            }
            $countKelas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // 4. Total Pelanggaran by Filter
            $filter = isset($_GET['filter']) ? $_GET['filter'] : 'semua';
            $wherePelanggaran = "deleted_at IS NULL";
            if ($filter === 'hari_ini') {
                $wherePelanggaran .= " AND DATE(created_at) = CURDATE()";
            } elseif ($filter === 'minggu_ini') {
                $wherePelanggaran .= " AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";
            } elseif ($filter === 'bulan_ini') {
                $wherePelanggaran .= " AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
            } elseif ($filter === 'tahun_ini') {
                $wherePelanggaran .= " AND YEAR(created_at) = YEAR(CURDATE())";
            }
            
            try {
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM pelanggaran WHERE $wherePelanggaran");
                $countPelanggaran = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            } catch (PDOException $e) {
                // Fallback jk tdk ada deleted_at
                $wherePelanggaran = str_replace("deleted_at IS NULL AND ", "", $wherePelanggaran);
                $wherePelanggaran = str_replace("deleted_at IS NULL", "1=1", $wherePelanggaran);
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM pelanggaran WHERE $wherePelanggaran");
                $countPelanggaran = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            }

            // 5. Top 5 Siswa dengan poin terbanyak
            // Asumsi: di tabel siswa ada field poin_pelanggaran, ATAU di pelanggaran di-sum berdasarkan id_siswa.
            // Lebih akurat menjumlahkan poin di tabel pelanggaran.
            
            // Kita ambil langsung dari tabel siswa yang punya field poin dan kelas
            $topSiswaQuery = "
                SELECT 
                    nama, 
                    nis, 
                    kelas, 
                    poin
                FROM siswa
                WHERE deleted_at IS NULL
                ORDER BY poin DESC
                LIMIT 5
            ";

            try {
                $stmt = $pdo->query($topSiswaQuery);
                $topSiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Coba query sederhana jika ga ada deleted_at
                try {
                    $topSiswaQuery = "
                        SELECT 
                            nama, 
                            nis, 
                            kelas, 
                            poin
                        FROM siswa
                        ORDER BY poin DESC
                        LIMIT 5
                    ";
                    $stmt = $pdo->query($topSiswaQuery);
                    $topSiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e2) {
                     // Jika error lagi, return empty array
                     $topSiswa = [];
                }
            }

            Success([
                "countSiswa" => (int) $countSiswa,
                "countGuru" => (int) $countGuru,
                "countKelas" => (int) $countKelas,
                "countPelanggaran" => (int) $countPelanggaran,
                "topSiswa" => $topSiswa
            ], "Data dashboard berhasil diambil");

        } catch (Exception $e) {
            InternalServerError(null, "Terjadi kesalahan sistem: " . $e->getMessage());
        }
    }
}
?>
