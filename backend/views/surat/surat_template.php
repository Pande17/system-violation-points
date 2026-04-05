<?php
// backend/views/surat/surat_template.php

$desc = null;
if (!empty($data['deskripsi'])) {
    $desc = json_decode($data['deskripsi'], true);
}

// Fallback for non-json data
if ($desc === null) {
    $desc = [
        'parent_nama' => '..........',
        'parent_job' => '..........',
        'parent_address' => '..........',
        'meeting_date' => '..........',
        'meeting_time' => '..........',
        'meeting_place' => '..........',
        'meeting_purpose' => '..........',
        'pindah_reason' => '..........',
        'pindah_target' => '..........'
    ];
}

$fullDate = date('d F Y', strtotime($data['tanggal_surat']));
$jenis = $data['jenis_surat'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat - <?php echo htmlspecialchars($jenis); ?></title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            background-color: #eee;
        }

        .page {
            width: 21cm;
            min-height: 29.7cm;
            padding: 2cm;
            margin: 1cm auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            box-sizing: border-box;
            color: #000;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }

        .header h2 {
            margin: 2px 0;
            font-size: 14pt;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
            font-size: 10pt;
        }

        .header .italic {
            font-style: italic;
            font-size: 9pt;
        }

        .content {
            font-size: 12pt;
            line-height: 1.6;
            text-align: justify;
        }

        .title-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .title-section h3 {
            text-decoration: underline;
            margin-bottom: 0;
            text-transform: uppercase;
            font-size: 13pt;
        }

        .title-section p {
            margin-top: 5px;
            font-weight: bold;
            font-size: 11pt;
        }

        .details-table {
            margin: 20px 0;
            width: 100%;
            border-collapse: collapse;
        }

        .details-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .details-table td.label {
            width: 160px;
        }

        .details-table td.colon {
            width: 20px;
        }

        .footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .footer.right-only {
            justify-content: flex-end;
        }

        .signature {
            width: 220px;
            text-align: center;
        }

        .signature .space {
            height: 75px;
        }

        .signature p {
            margin: 2px 0;
        }

        .signature .name {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        @media print {
            body {
                background: none;
                margin: 0;
                padding: 0;
            }

            .page {
                margin: 0;
                box-shadow: none;
                border: none;
                width: 100%;
                min-height: 100vh;
                padding: 1.5cm 2cm;
            }

            .no-print {
                display: none !important;
            }
        }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999;
        }

        .print-btn {
            background: #054030;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="no-print">
        <button class="print-btn" onclick="window.print()">CETAK / SIMPAN PDF</button>
    </div>

    <div class="page">
        <div class="header" style="border-bottom: none; padding-bottom: 0; margin-bottom: 20px;">
            <img src="../../../frontend/assets/img/kop.jpeg" style="width: 100%; display: block;" alt="Kop Surat">
        </div>

        <div class="content">
            <?php if ($jenis === 'Surat Pernyataan Siswa'): ?>
                <div class="title-section">
                    <h3>SURAT PERNYATAAN SISWA</h3>
                </div>
                <p>Yang bertandatangan di bawah ini :</p>
                <table class="details-table">
                    <tr>
                        <td class="label">Nama</td>
                        <td class="colon">:</td>
                        <td style="font-weight:bold;"><?php echo htmlspecialchars($data['nama_siswa']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">NIS</td>
                        <td class="colon">:</td>
                        <td><?php echo htmlspecialchars($data['nis']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Kelas</td>
                        <td class="colon">:</td>
                        <td><?php echo htmlspecialchars($data['kelas']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Nama Orang Tua</td>
                        <td class="colon">:</td>
                        <td><?php echo htmlspecialchars($desc['parent_nama']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Pekerjaan</td>
                        <td class="colon">:</td>
                        <td><?php echo htmlspecialchars($desc['parent_job']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Alamat Rumah</td>
                        <td class="colon">:</td>
                        <td><?php echo htmlspecialchars($desc['parent_address']); ?></td>
                    </tr>
                </table>
                <p>Menyatakan dan berjanji akan bersungguh-sungguh berubah dan bersedia mentaati aturan dan tata tertib
                    sekolah terkait permasalahan <strong><?php echo htmlspecialchars($data['keterangan']); ?></strong>.
                    Apabila selama masa pembinaan tidak mengalami perubahan, maka siswa yang bersangkutan bersedia dikenakan
                    sanksi sesuai aturan sekolah.</p>
                <p>Demikian surat pernyataan ini saya buat dengan sesungguhnya tanpa ada tekanan dari siapapun.</p>

                <div class="footer">
                    <div class="signature">
                        <p>Mengetahui,</p>
                        <p>Orang Tua/Wali Siswa</p>
                        <div class="space"></div>
                        <p class="name">( <?php echo htmlspecialchars($desc['parent_nama']); ?> )</p>
                    </div>
                    <div class="signature">
                        <p>Denpasar, <?php echo $fullDate; ?></p>
                        <p>Siswa yang bersangkutan</p>
                        <div class="space"></div>
                        <p class="name">( <?php echo htmlspecialchars($data['nama_siswa']); ?> )</p>
                    </div>
                </div>

            <?php elseif ($jenis === 'Surat Pemanggilan Orang Tua'): ?>
                <table style="width:100%; margin-bottom: 40px;">
                    <tr>
                        <td style="width:100px;">Nomor</td>
                        <td style="width:20px;">:</td>
                        <td><?php echo htmlspecialchars($data['nomor_surat']); ?></td>
                    </tr>
                    <tr>
                        <td>Perihal</td>
                        <td>:</td>
                        <td style="font-weight:bold; text-decoration:underline;">Pemanggilan Orang Tua / Wali Siswa</td>
                    </tr>
                </table>
                <p>Kepada Yth.</p>
                <p>Bapak/Ibu Orang Tua dari <strong><?php echo htmlspecialchars($data['nama_siswa']); ?></strong> (Kelas:
                    <?php echo htmlspecialchars($data['kelas']); ?>)
                </p>
                <p>Di Tempat.</p>
                <br>
                <p>Dengan hormat,</p>
                <p>Bersama surat ini, kami mengharapkan kehadiran Bapak/Ibu pada :</p>
                <table class="details-table" style="margin-left: 40px;">
                    <tr>
                        <td class="label">Hari / Tanggal</td>
                        <td class="colon">:</td>
                        <td><?php echo htmlspecialchars($desc['meeting_date']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Pukul</td>
                        <td class="colon">:</td>
                        <td><?php echo htmlspecialchars($desc['meeting_time']); ?></td>
                    </tr>
                    <tr>
                        <td class="label" style="padding-bottom: 20px;">Tempat</td>
                        <td class="colon">:</td>
                        <td><?php echo htmlspecialchars($desc['meeting_place']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Keperluan</td>
                        <td class="colon">:</td>
                        <td style="font-weight:bold;"><?php echo htmlspecialchars($desc['meeting_purpose']); ?></td>
                    </tr>
                </table>
                <p>Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>

                <div class="footer right-only">
                    <div class="signature">
                        <p>Denpasar, <?php echo $fullDate; ?></p>
                        <p>Waka Kesiswaan / Guru BK,</p>
                        <div class="space"></div>
                        <p class="name">( ........................................ )</p>
                    </div>
                </div>

            <?php elseif ($jenis === 'Surat Pernyataan Orang Tua'): ?>
                <div class="title-section">
                    <h3>SURAT PERNYATAAN ORANG TUA</h3>
                </div>
                <p>Yang bertandatangan di bawah ini :</p>
                <table class="details-table">
                    <tr>
                        <td class="label">Nama</td>
                        <td class="colon">:</td>
                        <td style="font-weight:bold;"><?php echo htmlspecialchars($desc['parent_nama']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Pekerjaan</td>
                        <td class="colon">:</td>
                        <td><?php echo htmlspecialchars($desc['parent_job']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Alamat Rumah</td>
                        <td class="colon">:</td>
                        <td><?php echo htmlspecialchars($desc['parent_address']); ?></td>
                    </tr>
                </table>
                <p>Menyatakan memang benar sanggup membina anak kami yang bernama
                    <strong><?php echo htmlspecialchars($data['nama_siswa']); ?></strong> (Kelas:
                    <?php echo htmlspecialchars($data['kelas']); ?>) untuk lebih disiplin mengikuti proses pembelajaran dan
                    mentaati Tata Tertib Sekolah.
                </p>
                <p>Demikian pernyataan ini kami buat, dan jika tidak sesuai dengan pernyataan di atas, anak kami dapat
                    dikeluarkan dari sekolah ini dengan rekomendasi pindah ke SMK lain yang serumpun.</p>

                <div class="footer right-only">
                    <div class="signature">
                        <p>Denpasar, <?php echo $fullDate; ?></p>
                        <p>Yang membuat pernyataan,</p>
                        <p>Orang Tua/Wali Siswa</p>
                        <div class="space"></div>
                        <p class="name">( <?php echo htmlspecialchars($desc['parent_nama']); ?> )</p>
                    </div>
                </div>

            <?php elseif ($jenis === 'Surat Pindah Sekolah'): ?>
                <div class="title-section">
                    <h3>KETERANGAN PINDAH SEKOLAH</h3>
                    <p>NOMOR : <?php echo htmlspecialchars($data['nomor_surat']); ?></p>
                </div>
                <p>Menerangkan bahwa :</p>
                <table class="details-table" style="margin-left: 40px;">
                    <tr>
                        <td class="label">Nama Siswa</td>
                        <td class="colon">:</td>
                        <td style="font-weight:bold;"><?php echo htmlspecialchars($data['nama_siswa']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">NIS</td>
                        <td class="colon">:</td>
                        <td><?php echo htmlspecialchars($data['nis']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Kelas</td>
                        <td class="colon">:</td>
                        <td><?php echo htmlspecialchars($data['kelas']); ?></td>
                    </tr>
                </table>
                <p>Terhitung sejak tanggal surat ini dibuat, siswa tersebut di atas dinyatakan telah <strong>pindah</strong>
                    ke sekolah: <strong><?php echo htmlspecialchars($desc['pindah_target']); ?></strong> dengan alasan
                    <strong><?php echo htmlspecialchars($desc['pindah_reason']); ?></strong>.
                </p>
                <p>Demikian surat pindah ini dibuat untuk dipergunakan sebagaimana mestinya.</p>

                <div class="footer right-only">
                    <div class="signature">
                        <p>Denpasar, <?php echo $fullDate; ?></p>
                        <p>Kepala SMK TI Bali Global,</p>
                        <div class="space" style="height: 100px;"></div>
                        <p class="name" style="font-size: 13pt;">Drs. I Gusti Made Murjana, M.Pd.</p>
                        <p style="font-size: 10pt;">NIP. 19651231 199003 1 123</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        window.onload = function () {
            // setTimeout(() => window.print(), 500);
        };
    </script>
</body>

</html>