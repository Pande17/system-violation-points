<?php
$fullDate = date('d F Y', strtotime($data['tanggal']));
// Indonesia Month Translation
$months = [
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember'
];
$enMonth = date('F', strtotime($data['tanggal']));
$idMonth = $months[$enMonth] ?? $enMonth;
$formattedDate = date('d', strtotime($data['tanggal'])) . ' ' . $idMonth . ' ' . date('Y', strtotime($data['tanggal']));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Keterangan Pindah Sekolah - <?= htmlspecialchars($data['nama_siswa']) ?></title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 2cm 2.5cm;
            background: #fff;
        }

        .kop-container {
            text-align: center;
            margin-bottom: 5px;
        }

        .kop-img {
            width: 100%;
            display: block;
        }

        .title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 14pt;
            margin-top: 15px;
            letter-spacing: 1px;
        }

        .nomor {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 25px;
        }

        .content-section {
            margin-top: 10px;
        }

        table.data-table {
            width: 100%;
            margin-bottom: 15px;
        }

        table.data-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .val-col {
            border-bottom: 0px solid #000;
            padding-left: 5px;
        }

        .signature-section {
            margin-top: 40px;
            width: 100%;
        }

        .name-line {
            text-decoration: underline;
            font-weight: bold;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        .no-print {
            position: fixed;
            bottom: 20px;
            right: 20px;
        }

        .btn {
            padding: 10px 20px;
            background: #054030;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn">CETAK</button>
        <button onclick="window.close()" class="btn" style="background:#666">TUTUP</button>
    </div>

    <div class="kop-container">
        <img src="../assets/img/kop.jpeg" class="kop-img"
            onerror="this.onerror=null; this.src='https://placehold.co/800x150?text=SMK+TI+BALI+GLOBAL+DENPASAR'">
    </div>

    <div class="title">KETERANGAN PINDAH SEKOLAH</div>
    <div class="nomor"><?= htmlspecialchars($data['nomor_surat'] ?: '.../SMK-TI/BK/' . date('Y')) ?></div>

    <div class="content-section">
        <p>Yang bertandatangan di bawah ini Kepala SMK TI BALI GLOBAL Denpasar, kecamatan Denpasar Selatan, Kota
            Denpasar, Provinsi Bali, Menerangkan bahwa :</p>

        <table class="data-table" style="margin-left: 20px;">
            <tr>
                <td style="width: 180px;">Nama Siswa</td>
                <td style="width: 15px;">:</td>
                <td style="font-weight: bold;"><?= htmlspecialchars($data['nama_siswa']) ?></td>
            </tr>
            <tr>
                <td>Kelas / Program</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['kelas']) ?></td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['nis']) ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td><?= ($data['jk_siswa'] ?? 'L') == 'L' ? 'Laki - Laki' : 'Perempuan' ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['alamat_siswa'] ?? '................................................') ?>
                </td>
            </tr>
        </table>

        <p>Sesuai dengan surat permohonan pindah sekolah dari Orang Tua / Wali siswa :</p>
        <table class="data-table" style="margin-left: 20px;">
            <tr>
                <td style="width: 180px;">Nama</td>
                <td style="width: 15px;">:</td>
                <td><?= htmlspecialchars($data['nama_orang_tua'] ?? $data['nama_ortu'] ?? '-') ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['alamat_orang_tua'] ?? $data['alamat_ortu'] ?? '-') ?></td>
            </tr>
        </table>

        <p style="text-align: justify; text-indent: 0;">
            Telah mengajukan surat permohonan pindah ke
            <strong><?= htmlspecialchars($data['tujuan_sekolah'] ?? '..........................') ?></strong>, dengan
            alasan <strong><?= htmlspecialchars($data['alasan'] ?? '..........................') ?></strong> dan untuk
            kelengkapan administrasi sudah diselesaikan.<br>
            Demikian surat pindah ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>

        <div class="signature-section">
            <div style="float: right; width: 50%; text-align: center;">
                <p>Denpasar, <?= $formattedDate ?></p>
                <p>Kepala SMK TI Bali Global Denpasar</p>
                <div style="height: 100px;"></div>
                <p class="name-line">Drs. I Gusti Made Murjana, M.Pd.</p>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
    <script>window.print();</script>
</body>

</html>