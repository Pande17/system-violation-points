<?php
require_once '../../../../backend/config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['nis'])) {
    die("Akses tidak valid. Harap gunakan form pencetakan.");
}

$nis = $_POST['nis'];
$meeting_date = $_POST['meeting_date'] ?? '................';
$meeting_time = $_POST['meeting_time'] ?? '................';
$meeting_place = $_POST['meeting_place'] ?? '................';
$meeting_purpose = $_POST['meeting_purpose'] ?? '................';

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

    if (!$data) die("Data siswa tidak ditemukan.");

    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $tanggal_sekarang = date('d') . ' ' . $months[(int) date('m')] . ' ' . date('Y');

} catch (PDOException $e) {
    die("Kesalahan database: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pemanggilan Orang Tua - <?php echo htmlspecialchars($data['nama']); ?></title>
    <style>
        @page { size: A4; margin: 0; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11.5pt; line-height: 1.5; color: #000; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 800px; margin: 0 auto; padding: 40px; box-sizing: border-box; }
        .kop-surat { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #000; padding-bottom: 5px; }
        .kop-surat img { width: 100%; max-width: 800px; }
        .content { margin-top: 30px; text-align: justify; }
        table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        td { vertical-align: top; padding: 2px 0; }
        .no-print { display: none !important; }
        @media print { .no-print { display: none !important; } .container { padding: 0; } body { margin: 1cm 2.54cm 2.54cm 2.54cm; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="position: fixed; bottom: 30px; right: 30px;">
        <button onclick="window.print()" style="padding:10px 20px; background:#054030; color:#fff; border:none; border-radius:5px; cursor:pointer;">Cetak</button>
    </div>

    <div class="container">
        <div class="kop-surat">
            <img src="../../../../assets/img/kop.jpeg" alt="Kop Surat">
        </div>

        <div class="content">
            <table>
                <tr><td style="width:100px;">Nomor</td><td style="width:20px;">:</td><td>.../SMK/2026</td></tr>
                <tr><td>Perihal</td><td>:</td><td style="font-bold underline; font-weight:bold;">Pemanggilan Orang Tua / Wali Siswa</td></tr>
            </table>

            <p style="margin-top:40px;">Kepada Yth.<br>Bapak/Ibu Orang Tua dari <strong><?php echo htmlspecialchars($data['nama']); ?></strong> (Kelas: <?php echo htmlspecialchars($data['kelas']); ?>)<br>di Tempat.</p>

            <p style="margin-top:20px;">Dengan hormat,</p>
            <p>Bersama surat ini, kami mengharapkan kehadiran Bapak/Ibu pada :</p>

            <table style="margin-left: 30px; margin-top: 20px;">
                <tr><td style="width:150px;">Hari / Tanggal</td><td style="width:20px;">:</td><td><?php echo htmlspecialchars($meeting_date); ?></td></tr>
                <tr><td>Pukul</td><td>:</td><td><?php echo htmlspecialchars($meeting_time); ?></td></tr>
                <tr><td>Tempat</td><td>:</td><td><?php echo htmlspecialchars($meeting_place); ?></td></tr>
                <tr><td>Keperluan</td><td>:</td><td style="font-weight:bold;"><?php echo htmlspecialchars($meeting_purpose); ?></td></tr>
            </table>

            <p style="margin-top:20px;">Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>

            <div style="margin-top: 100px; float: right; width: 300px; text-align: center;">
                <p>Denpasar, <?php echo $tanggal_sekarang; ?></p>
                <p>Waka Kesiswaan / Guru BK,</p>
                <div style="height: 100px;"></div>
                <p style="font-weight:bold; text-decoration: underline;">( ........................................ )</p>
            </div>
        </div>
    </div>
</body>
</html>
