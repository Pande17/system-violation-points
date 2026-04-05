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
    <title>Surat Pernyataan Orang Tua - <?= htmlspecialchars($data['nama_siswa']) ?></title>
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
            text-decoration: none;
            font-size: 14pt;
            margin-top: 15px;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }

        .content-section {
            margin-top: 10px;
        }

        table.data-table {
            width: 100%;
            margin-bottom: 25px;
        }

        table.data-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .val-col {
            border-bottom: 0.5px solid #000;
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

    <div class="title">SURAT PERNYATAAN ORANG TUA</div>

    <div class="content-section">
        <p>Yang bertandatangan di bawah ini orang tua/wali siswa SMK TI Bali Global Denpasar :</p>

        <table class="data-table">
            <tr>
                <td style="width: 180px;">Nama</td>
                <td style="width: 15px;">:</td>
                <td class="val-col"><?= htmlspecialchars($data['nama_ortu'] ?? '-') ?></td>
            </tr>
            <tr>
                <td>Tempat/ tanggal Lahir</td>
                <td>:</td>
                <td class="val-col">
                    <?php
                    $tempat = $data['ortu_tempat_lahir'] ?? '-';
                    $tanggal = '';
                    if (!empty($data['ortu_tanggal_lahir'])) {
                        $tgl = $data['ortu_tanggal_lahir'];
                        $enM = date('F', strtotime($tgl));
                        $idM = $months[$enM] ?? $enM;
                        $tanggal = date('d', strtotime($tgl)) . ' ' . $idM . ' ' . date('Y', strtotime($tgl));
                    }
                    echo htmlspecialchars($tempat) . ($tanggal ? " / " . $tanggal : "");
                    ?>
                </td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td class="val-col"><?= htmlspecialchars($data['pekerjaan_ortu'] ?? '-') ?></td>
            </tr>
            <tr>
                <td>Alamat Rumah</td>
                <td>:</td>
                <td class="val-col"><?= htmlspecialchars($data['alamat_ortu'] ?? '-') ?></td>
            </tr>
            <tr>
                <td>No. Hp./Telp.</td>
                <td>:</td>
                <td class="val-col"><?= htmlspecialchars($data['no_telp_ortu'] ?? '-') ?></td>
            </tr>
        </table>

        <p style="text-align: justify;">
            Menyatakan memang benar sanggup membina anak kami yang bernama
            <strong><?= htmlspecialchars($data['nama_siswa']) ?></strong>, Kelas :
            <strong><?= htmlspecialchars($data['kelas']) ?></strong> untuk lebih disiplin mengikuti proses pembelajaran
            dan mengikuti Tata Tertib Sekolah.
        </p>

        <p style="margin-top: 20px;">
            Demikian pernyataan kami dan jika tidak sesuai dengan pernyataan diatas, anak kami dapat dikeluarkan dari
            sekolah ini dengan rekomendasi pindah ke SMK lain yang serumpun.
        </p>

        <div class="signature-section">
            <div style="float: right; width: 45%; text-align: center;">
                <p>Denpasar, <?= $formattedDate ?></p>
                <p>Yang membuat pernyataan<br>Orang Tua/Wali siswa</p>
                <div style="height: 80px;"></div>
                <p class="name-line"><?= htmlspecialchars($data['nama_ortu'] ?? '................................') ?>
                </p>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div style="margin-top: 10px; font-size: 10pt;">
            <p style="font-weight: bold; margin-bottom: 5px;">NB :</p>
            <p style="text-decoration: underline; margin-top: 0;">Jika siswa tidak bisa mengikuti proses pembelajaran
                sampai bulan Juni <?= date('Y') ?> maka siswa dinyatakan mengundurkan diri.</p>
        </div>
    </div>
    <script>window.print();</script>
</body>

</html>