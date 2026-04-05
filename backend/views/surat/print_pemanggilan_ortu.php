<?php
$fullDate = date('d F Y', strtotime($data['tanggal_pengiriman']));
// Indonesia Month Translation
$months = [
    'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April',
    'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus',
    'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
];
$enMonth = date('F', strtotime($data['tanggal_pengiriman']));
$idMonth = $months[$enMonth] ?? $enMonth;
$formattedDate = date('d', strtotime($data['tanggal_pengiriman'])) . ' ' . $idMonth . ' ' . date('Y', strtotime($data['tanggal_pengiriman']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pemanggilan Orang Tua - <?= htmlspecialchars($data['nama_siswa']) ?></title>
    <style>
        @page { size: A4; margin: 0; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; color: #000; margin: 0; padding: 2cm 2.5cm; background: #fff; }
        .kop-container { text-align: center; margin-bottom: 10px; padding-bottom: 5px; }
        .kop-img { width: 100%; display: block; }
        .head-info { margin-bottom: 20px; }
        .head-info table td { padding: 1px 0; vertical-align: top; }
        .content-section { margin-top: 20px; }
        .signature-section { margin-top: 40px; width: 100%; }
        .sig-table { width: 100%; text-align: center; }
        .name-line { text-decoration: underline; font-weight: bold; }
        @media print { .no-print { display: none !important; } }
        .no-print { position: fixed; bottom: 20px; right: 20px; }
        .btn { padding: 10px 20px; background: #054030; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn">CETAK</button>
        <button onclick="window.close()" class="btn" style="background:#666">TUTUP</button>
    </div>

    <div class="kop-container">
        <img src="../assets/img/kop.jpeg" class="kop-img" onerror="this.onerror=null; this.src='https://placehold.co/800x150?text=SMK+TI+BALI+GLOBAL+DENPASAR'">
    </div>

    <div class="head-info">
        <table style="width: 100%;">
            <tr><td style="width: 80px;">No.</td><td style="width: 15px;">:</td><td><?= htmlspecialchars($data['nomor_surat'] ?: '.../SMK-TI/BK/'.date('Y')) ?></td></tr>
            <tr><td>Lamp.</td><td>:</td><td>-</td></tr>
            <tr><td>Perihal</td><td>:</td><td style="font-weight: bold; text-decoration: underline;">Pemanggilan Orang Tua / Wali Siswa</td></tr>
        </table>
    </div>

    <div class="content-section">
        <p>Kepada<br>Yth. Bapak/ Ibu</p>
        <table style="margin-left: 40px; margin-bottom: 20px;">
            <tr><td style="width: 180px;">Orang Tua / Wali dari</td><td style="width: 15px;">:</td><td style="font-weight: bold;"><?= htmlspecialchars($data['nama_siswa']) ?></td></tr>
            <tr><td>Kelas / Nis</td><td>:</td><td style="font-weight: bold;"><?= htmlspecialchars($data['kelas']) ?> / <?= htmlspecialchars($data['nis']) ?></td></tr>
        </table>

        <p>Dengan hormat,</p>
        <p>Bersama surat ini, kami mengharapkan kehadiran Bapak / Ibu pada :</p>

        <table style="margin-left: 40px; margin-bottom: 20px;">
            <tr><td style="width: 180px;">Hari / Tanggal</td><td style="width: 15px;">:</td><td><?= htmlspecialchars($data['hari_tanggal'] ?? '-') ?></td></tr>
            <tr><td>Pukul</td><td>:</td><td><?= htmlspecialchars($data['pukul'] ?? '-') ?> WITA</td></tr>
            <tr><td>Tempat</td><td>:</td><td><?= htmlspecialchars($data['tempat'] ?? '-') ?></td></tr>
            <tr><td>Keperluan</td><td>:</td><td><?= htmlspecialchars($data['keperluan'] ?? '-') ?></td></tr>
        </table>

        <p style="text-indent: 1cm; text-align: justify;">
            Demikian surat ini kami sampaikan, besar harapan kami pertemuan ini agar tidak diwakilkan. Atas perhatian dan kerjasamanya, kami ucapkan terimakasih.
        </p>

        <div class="signature-section">
            <table class="sig-table">
                <tr>
                    <td style="width: 50%;">Mengetahui,<br>Waka Kesiswaan</td>
                    <td style="width: 50%;">Denpasar, <?= $formattedDate ?><br>Guru BK</td>
                </tr>
                <tr style="height: 80px;"><td></td><td></td></tr>
                <tr>
                    <td><span class="name-line">Bagus Putu Eka Wijaya, S.Kom.</span></td>
                    <td><span class="name-line">Ida Gusti Ayu Rinjani, M.Pd.</span></td>
                </tr>
            </table>
        </div>
    </div>
    <script>window.print();</script>
</body>
</html>
