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
    <title>Surat Pernyataan Orang Tua - <?= $data['nama_siswa'] ?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; line-height: 1.5; color: #000; margin: 0; padding: 0.5cm 2cm; background: white; font-size: 11pt; }
        .kop-container { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .kop-img { width: 100%; display: block; }
        .title { text-align: center; font-weight: bold; text-decoration: underline; font-size: 14pt; margin-top: 20px; margin-bottom: 25px; }
        .content-section { margin-top: 20px; font-size: 12pt; }
        table.data-table td { padding: 4px 0; vertical-align: top; }
        .signature-section { margin-top: 60px; display: table; width: 100%; }
        .sig-col { display: table-cell; width: 50%; text-align: center; }
        .sig-space { height: 100px; }
        @media print { body { padding: 0 1cm; } }
    </style>
</head>
<body>
    <div class="kop-container">
        <img src="../../frontend/assets/img/kop.jpeg" class="kop-img" alt="Kop Surat">
    </div>

    <div class="title">SURAT PERNYATAAN ORANG TUA</div>

    <div class="content-section">
        <p>Yang bertandatangan di bawah ini Orang Tua / Wali siswa SMK TI Bali Global Denpasar :</p>

        <table class="data-table" style="margin-bottom: 25px;">
            <tr><td style="width: 180px;">Nama</td><td style="width: 15px;">:</td><td style="font-weight: bold;"><?= htmlspecialchars($data['nama_ortu'] ?? '-') ?></td></tr>
            <tr><td>Pekerjaan</td><td>:</td><td><?= htmlspecialchars($data['pekerjaan_ortu'] ?? '-') ?></td></tr>
            <tr><td>Alamat Rumah</td><td>:</td><td><?= htmlspecialchars($data['alamat_ortu'] ?? '-') ?></td></tr>
            <tr><td>No. Hp. / Telp.</td><td>:</td><td><?= htmlspecialchars($data['no_telp_ortu'] ?? '-') ?></td></tr>
        </table>

        <p style="text-indent: 1.2cm; text-align: justify;">
            Menyatakan memang benar sanggup membina anak kami yang bernama <strong><?= htmlspecialchars($data['nama_siswa']) ?></strong>, Kelas : <strong><?= htmlspecialchars($data['kelas']) ?></strong> untuk lebih disiplin mengikuti proses pembelajaran dan mengikuti Tata Tertib Sekolah.
        </p>

        <p style="margin-top: 20px;">
            Demikian pernyataan kami dan jika tidak sesuai dengan pernyataan diatas, anak kami dapat dikeluarkan dari sekolah ini dengan rekomendasi pindah ke SMK lain yang serumpun.
        </p>

        <div class="signature-section">
            <div style="float: right; width: 50%; text-align: center;">
                <p>Denpasar, <?= $formattedDate ?></p>
                <p>Yang membuat pernyataan</p>
                <p>Orang Tua/Wali siswa</p>
                <div class="sig-space"></div>
                <p style="font-weight: bold; text-decoration: underline;">( <?= htmlspecialchars($details['parent_nama'] ?? $data['nama_ortu'] ?? '..........................') ?> )</p>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div style="margin-top: 60px; font-size: 10pt;">
            <p style="font-weight: bold; margin-bottom: 5px;">NB :</p>
            <p style="text-decoration: underline; margin-top: 0;">Jika siswa tidak bisa mengikuti proses pembelajaran sampai bulan Juni <?= date('Y') ?> maka siswa dinyatakan mengundurkan diri.</p>
        </div>
    </div>

    <script>window.print();</script>
</body>
</html>
