<?php
require_once '../../../../backend/config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['nis'])) {
    die("Akses tidak valid.");
}

$nis = $_POST['nis'];
$nomor_surat = $_POST['nomor_surat'] ?? '................';
$sekolah_tujuan = $_POST['sekolah_tujuan'] ?? '................';
$alasan = $_POST['alasan'] ?? '................';

try {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT * FROM siswa WHERE nis = :nis AND deleted_at IS NULL");
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
    <title>Surat Pindah Sekolah - <?php echo htmlspecialchars($data['nama']); ?></title>
    <style>
        @page { size: A4; margin: 0; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11.5pt; line-height: 1.5; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 800px; margin: 0 auto; padding: 40px; }
        .kop-surat img { width: 100%; }
        .title { text-align: center; margin-top: 30px; font-weight: bold; }
        .content { margin-top: 30px; text-align: justify; }
        .footer { margin-top: 80px; float: right; text-align: center; width: 300px; }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="kop-surat"><img src="../../../../assets/img/kop.jpeg"></div>
        <div class="title">
            <div style="text-decoration: underline; font-size: 14pt;">SURAT KETERANGAN PINDAH SEKOLAH</div>
            <div style="font-size: 11pt; margin-top: 5px;">NOMOR : <?php echo htmlspecialchars($nomor_surat); ?></div>
        </div>
        
        <div class="content">
            <p>Menerangkan bahwa :</p>
            <table style="margin-left: 30px;">
                <tr><td style="width:150px;">Nama Siswa</td><td style="width:20px;">:</td><td style="font-weight:bold;"><?php echo htmlspecialchars($data['nama']); ?></td></tr>
                <tr><td>NIS</td><td>:</td><td><?php echo htmlspecialchars($data['nis']); ?></td></tr>
                <tr><td>Kelas</td><td>:</td><td><?php echo htmlspecialchars($data['kelas']); ?></td></tr>
            </table>

            <p style="margin-top: 20px;">Terhitung sejak tanggal surat ini dibuat, siswa tersebut di atas dinyatakan telah <strong>pindah</strong> ke sekolah: <strong><?php echo htmlspecialchars($sekolah_tujuan); ?></strong> dengan alasan <strong><?php echo htmlspecialchars($alasan); ?></strong>.</p>
            
            <p>Demikian surat keterangan ini kami buat untuk dapat dipergunakan sebagaimana mestinya.</p>

            <div class="footer">
                <p>Denpasar, <?php echo $tanggal_sekarang; ?></p>
                <p>Kepala SMK TI Bali Global,</p>
                <div style="height: 100px;"></div>
                <p style="font-weight:bold; text-decoration: underline; text-transform: uppercase;">Drs. I Gusti Made Murjana, M.Pd.</p>
                <p style="font-size: 10pt; margin-top: -15px;">NIP. 19651231 199003 1 123</p>
            </div>
        </div>
    </div>
</body>
</html>
