<?php
// Template provided via $data variable from SuratController
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

// Get parent details from joined data or defaults
$nama_ortu = $data['ortu_nama'] ?? $data['nama_ortu'] ?? '................................................';
$pekerjaan = $data['ortu_pekerjaan'] ?? $data['pekerjaan_ortu'] ?? '................................................';
$alamat = $data['ortu_alamat'] ?? $data['alamat_ortu'] ?? '................................................';
$no_telp = $data['ortu_no_telp'] ?? $data['no_telp_ortu'] ?? '................................................';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Pernyataan Siswa - <?= htmlspecialchars($data['nama_siswa']) ?></title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11.5pt;
            line-height: 1.35;
            color: #000;
            margin: 0;
            padding: 2cm 2.5cm;
            background: #fff;
        }

        .kop-container {
            text-align: center;
            margin-bottom: 10px;
            /* border-bottom: 3px double #000; */
            padding-bottom: 5px;
        }

        .kop-img {
            width: 100%;
            display: block;
        }

        .surat-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 15px;
            letter-spacing: 1px;
            text-decoration: underline;
        }

        .content {
            text-align: justify;
        }

        table.details {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        table.details td {
            padding: 2px 0;
            vertical-align: top;
        }

        .val-col {
            border-bottom: 1px dashed #000;
            padding-left: 5px;
        }

        .signature-section {
            margin-top: 15px;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            text-align: center;
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

    <div class="surat-title">SURAT PERNYATAAN SISWA</div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini:</p>
        <table class="details">
            <tr>
                <td style="width: 180px;">Nama</td>
                <td style="width: 15px;">:</td>
                <td class="val-col"><?= htmlspecialchars($data['nama_siswa']) ?></td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>:</td>
                <td class="val-col"><?= htmlspecialchars($data['nis']) ?></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td class="val-col"><?= htmlspecialchars($data['kelas']) ?></td>
            </tr>
            <tr>
                <td>Tempat/ tanggal Lahir</td>
                <td>:</td>
                <td class="val-col">
                    <?php 
                    $tempat = $data['siswa_tempat_lahir'] ?? '-';
                    $tanggal = '';
                    if (!empty($data['siswa_tanggal_lahir'])) {
                        $tgl = $data['siswa_tanggal_lahir'];
                        $enM = date('F', strtotime($tgl));
                        $idM = $months[$enM] ?? $enM;
                        $tanggal = date('d', strtotime($tgl)) . ' ' . $idM . ' ' . date('Y', strtotime($tgl));
                    }
                    echo htmlspecialchars($tempat) . ($tanggal ? " / " . $tanggal : "");
                    ?>
                </td>
            </tr>
            <tr>
                <td>Masalah</td>
                <td>:</td>
                <td class="val-col"><?= htmlspecialchars($data['masalah']) ?></td>
            </tr>
            <tr>
                <td>Nama Orang Tua</td>
                <td>:</td>
                <td class="val-col"><?= htmlspecialchars($nama_ortu) ?></td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td class="val-col"><?= htmlspecialchars($pekerjaan) ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td class="val-col"><?= htmlspecialchars($alamat) ?></td>
            </tr>
        </table>

        <p>Menyatakan dan berjanji akan bersungguh-sungguh berubah dan bersedia mentaati aturan dan tata
            tertib sekolah. Apabila selama masa pembinaan tidak mengalami perubahan, maka siswa yang
            bersangkutan dikembalikan kepada orang tua/wali</p>

        <p>Demikian surat pernyataan ini saya buat dengan sadar dan penuh tanggung jawab tanpa ada paksaan dari pihak
            manapun.</p>

        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td style="width: 50%;">Mengetahui,<br>Orang Tua/Wali</td>
                    <td style="width: 50%;">Denpasar, <?= $formattedDate ?><br>Siswa Bersangkutan</td>
                </tr>
                <tr style="height: 60px;">
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><span class="name-line"><?= htmlspecialchars($nama_ortu) ?></span></td>
                    <td><span class="name-line"><?= htmlspecialchars($data['nama_siswa']) ?></span></td>
                </tr>
            </table>

            <table class="signature-table" style="margin-top: 30px;">
                <tr>
                    <td style="width: 50%;">Guru BK</td>
                    <td style="width: 50%;">Wali Kelas</td>
                </tr>
                <tr style="height: 60px;">
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><span class="name-line">Ni Putu Chintya Pradnya Suari, S.Pd</span></td>
                    <td><span
                            class="name-line"><?= htmlspecialchars($data['wali_kelas_nama'] ?? '................................................') ?></span>
                    </td>
                </tr>
            </table>

            <div style="text-align: center; margin-top: 20px;">
                <p>Mengetahui,<br>Waka Kesiswaan</p>
                <div style="height: 10px;"></div>
                <p class="name-line">Bagus Putu Eka Wijaya, S.Kom</p>
            </div>
        </div>
    </div>
    <script>window.print();</script>
</body>

</html>