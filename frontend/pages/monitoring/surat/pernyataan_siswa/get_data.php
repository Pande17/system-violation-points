<?php
header('Content-Type: application/json');
require_once '../../../../../backend/config/db.php';

if (!isset($_GET['nis'])) {
    echo json_encode(['status' => 'error', 'message' => 'NIS tidak ditemukan']);
    exit;
}

try {
    $db = getDBConnection();
    $nis = $_GET['nis'];

    $stmt = $db->prepare("
        SELECT s.*, 
               o.nama as ortu_nama, o.pekerjaan as ortu_pekerjaan, o.no_telp as ortu_no_telp, o.alamat as ortu_alamat
        FROM siswa s 
        LEFT JOIN ortu_wali o ON s.id_ortuWali = o.id
        WHERE s.nis = :nis AND s.deleted_at IS NULL
    ");
    $stmt->execute([':nis' => $nis]);
    $siswa = $stmt->fetch();

    if (!$siswa) {
        echo json_encode(['status' => 'error', 'message' => 'Data siswa tidak ditemukan']);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'data' => [
            'nama_siswa' => $siswa['nama'],
            'nis' => $siswa['nis'],
            'kelas' => $siswa['kelas'],
            'jurusan' => $siswa['jurusan'],
            'nama_ortu' => $siswa['ortu_nama'] ?? '-',
            'pekerjaan_ortu' => $siswa['ortu_pekerjaan'] ?? '-',
            'alamat_ortu' => $siswa['ortu_alamat'] ?? '-',
            'no_telp_ortu' => $siswa['ortu_no_telp'] ?? '-'
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Kesalahan database: ' . $e->getMessage()]);
}