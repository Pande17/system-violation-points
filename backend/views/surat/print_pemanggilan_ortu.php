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
    <title>Surat Pemanggilan Orang Tua - <?= $data['nama_siswa'] ?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; line-height: 1.5; color: #000; margin: 0; padding: 0.5cm 2cm; background: white; font-size: 11pt; }
        .kop-container { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .kop-img { width: 100%; display: block; }
        .head-info { margin-bottom: 25px; }
        .head-info table td { padding: 2px 0; }
        .content-section { margin-top: 20px; font-size: 12pt; }
        table.data-table td { padding: 4px 0; vertical-align: top; }
        .signature-section { margin-top: 50px; display: table; width: 100%; }
        .sig-col { display: table-cell; width: 50%; text-align: center; }
        .sig-space { height: 80px; }
        @media print { body { padding: 0 1cm; } }
    </style>
</head>
<body>
    <div class="kop-container">
        <img src="../../frontend/assets/img/kop.jpeg" class="kop-img" alt="Kop Surat">
    </div>

    <div class="head-info">
        <table style="width: 100%;">
            <tr><td style="width: 80px;">No.</td><td style="width: 10px;">:</td><td><?= htmlspecialchars($data['nomor_surat'] ?: '.../SMK-TI/BK/'.date('Y')) ?></td></tr>
            <tr><td>Lamp.</td><td>:</td><td>-</td></tr>
            <tr><td>Perihal</td><td>:</td><td style="font-weight: bold; text-decoration: underline;">Pemanggilan Orang Tua / Wali Siswa</td></tr>
        </table>
    </div>

    <div class="content-section">
        <p>Kepada Yth.</p>
        <p>Bapak / Ibu Orang Tua / Wali dari :</p>
        <table style="margin-left: 40px; margin-bottom: 20px;">
            <tr><td style="width: 180px;">Siswa</td><td style="width: 15px;">:</td><td style="font-weight: bold;"><?= htmlspecialchars($data['nama_siswa']) ?></td></tr>
            <tr><td>Kelas / NIS</td><td>:</td><td style="font-weight: bold;"><?= htmlspecialchars($data['kelas']) ?> / <?= htmlspecialchars($data['nis']) ?></td></tr>
        </table>

        <p>Dengan hormat,</p>
        <p>Bersama surat ini, kami mengharapkan kehadiran Bapak / Ibu pada :</p>

        <table class="data-table" style="margin-left: 40px; margin-bottom: 20px;">
            <tr><td style="width: 180px;">Hari / Tanggal</td><td style="width: 15px;">:</td><td><?= htmlspecialchars($data['hari_tanggal'] ?? '-') ?></td></tr>
            <tr><td>Pukul</td><td>:</td><td><?= htmlspecialchars($data['pukul'] ?? '-') ?></td></tr>
            <tr><td>Tempat</td><td>:</td><td><?= htmlspecialchars($data['tempat'] ?? '-') ?></td></tr>
            <tr><td>Keperluan</td><td>:</td><td style="font-weight: bold;"><?= htmlspecialchars($data['keperluan'] ?? '-') ?></td></tr>
        </table>

        <p style="text-indent: 1.2cm; text-align: justify;">
            Demikian surat ini kami sampaikan, besar harapan kami pertemuan ini agar tidak diwakilkan. Atas perhatian dan kerjasamanya, kami ucapkan terimakasih.
        </p>

        <div class="signature-section">
            <div class="sig-col">
                <p>Mengetahui,</p>
                <p>Waka Kesiswaan</p>
                <div class="sig-space"></div>
                <p style="font-weight: bold; text-decoration: underline;">( Bagus Putu Eka Wijaya, S.Kom. )</p>
            </div>
            <div class="sig-col">
                <p>Denpasar, <?= $formattedDate ?></p>
                <p>Guru BK</p>
                <div class="sig-space"></div>
                <p style="font-weight: bold; text-decoration: underline;">( <?= htmlspecialchars($data['nama_guru_bk'] ?? 'Ida Gusti Ayu Rinjani, M.Pd.') ?> )</p>
            </div>
        </div>
    </div>

    <script>window.print();</script>
</body>
</html>
