<?php
require_once '../../../../backend/config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['nis'])) {
    die("Akses tidak valid.");
}

$nis = $_POST['nis'];
$pernyataan = $_POST['pernyataan'] ?? '................';

try {
    $db = getDBConnection();
    $stmt = $db->prepare("
        SELECT s.*, 
               o.nama as ortu_nama, o.pekerjaan as ortu_pekerjaan, o.no_telp as ortu_no_telp, o.alamat as ortu_alamat
        FROM siswa s 
        LEFT JOIN ortu_wali o ON s.id_ortuWali = o.id
        WHERE s.nis = :nis AND s.deleted_at IS NULL
    ");
    $stmt->execute([':nis' => $nis]);
    $data = $stmt->fetch();

    if (!$data) die("Data tidak ditemukan.");

    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $tanggal_sekarang = date('d') . ' ' . $months[(int) date('m')] . ' ' . date('Y');

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pernyataan Orang Tua - <?php echo htmlspecialchars($data['nama']); ?></title>
    <style>
        @page { size: A4; margin: 0; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11.5pt; line-height: 1.5; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 800px; margin: 0 auto; padding: 40px; }
        .kop-surat img { width: 100%; }
        .title { text-align: center; margin-top: 30px; font-weight: bold; text-decoration: underline; text-transform: uppercase; }
        .content { margin-top: 30px; text-align: justify; }
        .footer { margin-top: 80px; float: right; text-align: center; width: 250px; }
        table { margin-bottom: 20px; }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="kop-surat"><img src="../../../../assets/img/kop.jpeg"></div>
        <div class="title">SURAT PERNYATAAN ORANG TUA</div>
        
        <div class="content">
            <p>Yang bertandatangan di bawah ini :</p>
            <table>
                <tr><td style="width:150px;">Nama</td><td style="width:20px;">:</td><td style="font-weight:bold;"><?php echo htmlspecialchars($data['ortu_nama'] ?? '-'); ?></td></tr>
                <tr><td>Pekerjaan</td><td>:</td><td><?php echo htmlspecialchars($data['ortu_pekerjaan'] ?? '-'); ?></td></tr>
                <tr><td>Alamat Rumah</td><td>:</td><td><?php echo htmlspecialchars($data['ortu_alamat'] ?? '-'); ?></td></tr>
            </table>

            <p>Menyatakan memang benar sanggup membina anak kami yang bernama <strong><?php echo htmlspecialchars($data['nama']); ?></strong> (Kelas: <?php echo htmlspecialchars($data['kelas']); ?>) untuk lebih disiplin mengikuti proses pembelajaran terkait <strong><?php echo htmlspecialchars($pernyataan); ?></strong>.</p>
            
            <p>Demikian pernyataan ini kami buat, dan jika tidak sesuai dengan janji di atas, anak kami bersedia diberikan sanksi sesuai ketentuan sekolah.</p>

            <div class="footer">
                <p>Denpasar, <?php echo $tanggal_sekarang; ?></p>
                <p>Orang Tua / Wali Siswa,</p>
                <div style="height: 100px;"></div>
                <p style="font-weight:bold;">( <?php echo htmlspecialchars($data['ortu_nama'] ?? '....................'); ?> )</p>
            </div>
        </div>
    </div>
</body>
</html>
