<?php
$fullDate = date('d F Y', strtotime($data['tanggal']));
// Indonesia Month Translation
$months = [
    'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April',
    'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus',
    'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
];
$enMonth = date('F', strtotime($data['tanggal']));
$idMonth = $months[$enMonth] ?? $enMonth;
$formattedDate = date('d', strtotime($data['tanggal'])) . ' ' . $idMonth . ' ' . date('Y', strtotime($data['tanggal']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keterangan Pindah Sekolah - <?= $data['nama_siswa'] ?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; line-height: 1.5; color: #000; margin: 0; padding: 0.5cm 2cm; background: white; font-size: 11pt; }
        .kop-container { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .kop-img { width: 100%; display: block; }
        .title { text-align: center; font-weight: bold; text-decoration: underline; font-size: 14pt; margin-top: 10px; }
        .nomor { text-align: center; font-weight: bold; font-size: 11pt; margin-bottom: 25px; }
        .content-section { margin-top: 20px; font-size: 12pt; }
        table.data-table td { padding: 4px 0; vertical-align: top; }
        .signature-section { margin-top: 50px; display: table; width: 100%; text-align: center; }
        .sig-col { display: table-cell; width: 60%; vertical-align: top; }
        .sig-space { height: 90px; }
        @media print { body { padding: 0 1cm; } }
    </style>
</head>
<body>
    <div class="kop-container">
        <img src="../../frontend/assets/img/kop.jpeg" class="kop-img" alt="Kop Surat">
    </div>

    <div class="title">KETERANGAN PINDAH SEKOLAH</div>
    <div class="nomor"><?= htmlspecialchars($data['nomor_surat'] ?: '.../SMK-TI/BK/'.date('Y')) ?></div>

    <div class="content-section">
        <p>Yang bertandatangan di bawah ini Kepala SMK TI BALI GLOBAL Denpasar, kecamatan Denpasar Selatan, Kota Denpasar, Provinsi Bali, Menerangkan bahwa :</p>

        <table class="data-table" style="margin-left: 40px; margin-bottom: 20px;">
            <tr><td style="width: 180px;">Nama Siswa</td><td style="width: 15px;">:</td><td style="font-weight: bold;"><?= htmlspecialchars($data['nama_siswa']) ?></td></tr>
            <tr><td>Kelas / Program</td><td>:</td><td><?= htmlspecialchars($data['kelas']) ?> / <?= htmlspecialchars($data['program_keahlian'] ?? '-') ?></td></tr>
            <tr><td>NIS</td><td>:</td><td><?= htmlspecialchars($data['nis']) ?></td></tr>
            <tr><td>Jenis Kelamin</td><td>:</td><td><?= htmlspecialchars($data['jk_siswa'] == 'L' ? 'Laki-laki' : 'Perempuan') ?></td></tr>
            <tr><td>Alamat</td><td>:</td><td><?= htmlspecialchars($data['alamat_siswa'] ?? '-') ?></td></tr>
        </table>

        <p>Sesuai dengan surat permohonan pindah sekolah dari Orang Tua/Wali siswa :</p>
        <table class="data-table" style="margin-left: 40px; margin-bottom: 20px;">
            <tr><td style="width: 180px;">Nama</td><td style="width: 15px;">:</td><td style="font-weight: bold;"><?= htmlspecialchars($data['nama_ortu'] ?? '-') ?></td></tr>
            <tr><td>Alamat</td><td>:</td><td><?= htmlspecialchars($data['alamat_ortu'] ?? '-') ?></td></tr>
        </table>

        <p style="text-align: justify;">
            Telah mengajukan surat permohonan pindah ke <strong><?= htmlspecialchars($data['tujuan_sekolah'] ?? '..........................') ?></strong>, dengan alasan <strong><?= htmlspecialchars($data['alasan'] ?? '..........................') ?></strong> untuk kelengkapan administrasi sudah diselesaikan.
            <br>Demikian surat pindah ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>

        <div class="signature-section">
            <div style="float: right; width: 50%; text-align: center;">
                <p>Denpasar, <?= $formattedDate ?></p>
                <p>Kepala SMK TI Bali Global Denpasar</p>
                <div class="sig-space"></div>
                <p style="font-weight: bold; text-decoration: underline;">Drs. I Gusti Made Murjana, M.Pd.</p>
                <p style="font-size: 10pt; margin-top: -10px;">NIP. 19651231 199003 1 123</p>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <script>window.print();</script>
</body>
</html>
