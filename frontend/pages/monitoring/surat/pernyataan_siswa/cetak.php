<?php
require_once '../../../../../backend/config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['nis'])) {
    die("Akses tidak valid. Harap gunakan form pencetakan.");
}

$nis = $_POST['nis'];
$masalah = $_POST['masalah'] ?? '';

try {
    $db = getDBConnection();
    // Ambil data siswa lengkap beserta relasinya
    $stmt = $db->prepare("
        SELECT s.*, 
               o.nama as ortu_nama, o.pekerjaan as ortu_pekerjaan, o.no_telp as ortu_no_telp, o.alamat as ortu_alamat
        FROM siswa s 
        LEFT JOIN ortu_wali o ON s.id_ortuWali = o.id
        WHERE s.nis = :nis AND s.deleted_at IS NULL
    ");
    $stmt->execute([':nis' => $nis]);
    $data = $stmt->fetch();

    if (!$data) {
        die("Data siswa tidak ditemukan.");
    }

    $nama_ortu = !empty($data['ortu_nama']) ? $data['ortu_nama'] : '................................................';
    $pekerjaan = !empty($data['ortu_pekerjaan']) ? $data['ortu_pekerjaan'] : '................................................';
    $alamat = !empty($data['ortu_alamat']) ? $data['ortu_alamat'] : (!empty($data['alamat']) ? $data['alamat'] : '................................................');
    $no_telp = !empty($data['ortu_no_telp']) ? $data['ortu_no_telp'] : '................................................';
    $kelas = !empty($data['kelas']) ? $data['kelas'] : '-';
    $jurusan = !empty($data['jurusan']) ? $data['jurusan'] : '-';

    // Wali Kelas name - find from kelas table maybe? Or just use dot line for now
    // My project has classes but doesn't store wali_kelas in the students table?
    // Let's check for a 'wali_kelas' field or use dot lines.
    $wali_kelas = '................................................';

    $months = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];
    $tanggal_sekarang_alt = date('d') . ' ' . $months[(int) date('m')] . ' ' . date('Y');

} catch (PDOException $e) {
    die("Kesalahan database: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pernyataan Siswa - <?php echo htmlspecialchars($data['nama']); ?></title>
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
            padding: 0;
            background: #fff;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 30px;
            box-sizing: border-box;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 3px solid #000;
            padding-bottom: 5px;
        }

        .kop-surat img {
            width: 100%;
            max-width: 800px;
            height: auto;
            display: block;
        }

        .surat-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .content {
            text-align: justify;
        }

        table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            padding: 1px 0;
        }

        .label-col {
            width: 25%;
        }

        .colon-col {
            width: 5%;
            text-align: center;
        }

        .val-col {
            width: 70%;
            border-bottom: 1.5px dashed #000;
            padding-left: 5px;
            padding-bottom: 3px;
        }

        .signature-area {
            width: 100%;
            margin-top: 20px;
        }

        .signature-table {
            width: 100%;
            text-align: center;
            margin-bottom: 15px;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
        }

        .name-line {
            display: inline;
            text-decoration: underline;
            font-weight: normal;
        }

        .name-dots {
            display: inline-block;
            border-bottom: 1px dotted #000;
            min-width: 200px;
            padding-bottom: 1px;
        }

        .name-underline {
            display: inline;
            text-decoration: underline;
        }

        @media print {
            body {
                background: white;
                margin-top: 1cm;
                margin-bottom: 2.54cm;
                margin-left: 2.54cm;
                margin-right: 2.54cm;
            }

            .no-print {
                display: none !important;
            }

            .container {
                border: none !important;
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="no-print" style="position: fixed; bottom: 30px; right: 30px; display: flex; gap: 10px; z-index: 1000;">
        <button onclick="window.print()"
            style="padding:10px 20px; background:#2563eb; color:#fff; border:none; border-radius:5px; cursor:pointer; font-size:16px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">Cetak
            Ulang</button>
        <button onclick="window.close()"
            style="padding:10px 20px; background:#6b7280; color:#fff; border:none; border-radius:5px; cursor:pointer; font-size:16px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">Tutup
            Tab</button>
    </div>

    <div class="container">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <img src="../../../../assets/img/kop.jpeg" alt="Kop Surat SMK TI Bali Global"
                onerror="this.src='https://via.placeholder.com/800x150.png?text=KOP+SURAT+SMK+TI+BALI+GLOBAL+DENPASAR';">
        </div>

        <div class="surat-title">SURAT PERNYATAAN SISWA</div>

        <div class="content">
            <p style="margin-top:0;">Yang bertandatangan di bawah ini :</p>
            <table>
                <tr>
                    <td class="label-col">Nama</td>
                    <td class="colon-col">:</td>
                    <td class="val-col"><?php echo htmlspecialchars($data['nama']); ?></td>
                </tr>
                <tr>
                    <td class="label-col">NIS</td>
                    <td class="colon-col">:</td>
                    <td class="val-col"><?php echo htmlspecialchars($nis); ?></td>
                </tr>
                <tr>
                    <td class="label-col">Kelas</td>
                    <td class="colon-col">:</td>
                    <td class="val-col"><?php echo htmlspecialchars($kelas); ?></td>
                </tr>
                <tr>
                    <td class="label-col">Program Keahlian</td>
                    <td class="colon-col">:</td>
                    <td class="val-col"><?php echo htmlspecialchars($jurusan); ?></td>
                </tr>
                <tr>
                    <td class="label-col">Masalah</td>
                    <td class="colon-col">:</td>
                    <td class="val-col"><?php echo nl2br(htmlspecialchars($masalah)); ?></td>
                </tr>
            </table>

            <table style="margin-top: 10px;">
                <tr>
                    <td class="label-col">Nama Orang Tua</td>
                    <td class="colon-col">:</td>
                    <td class="val-col"><?php echo htmlspecialchars($nama_ortu); ?></td>
                </tr>
                <tr>
                    <td class="label-col">Pekerjaan</td>
                    <td class="colon-col">:</td>
                    <td class="val-col"><?php echo htmlspecialchars($pekerjaan); ?></td>
                </tr>
                <tr>
                    <td class="label-col">Alamat Rumah</td>
                    <td class="colon-col">:</td>
                    <td class="val-col"><?php echo htmlspecialchars($alamat); ?></td>
                </tr>
                <tr>
                    <td class="label-col">No. Hp./Telp.</td>
                    <td class="colon-col">:</td>
                    <td class="val-col"><?php echo htmlspecialchars($no_telp); ?></td>
                </tr>
            </table>

            <p style="text-indent: 0; margin-top: 15px;">Menyatakan dan berjanji akan bersungguh-sungguh berubah dan
                bersedia mentaati aturan dan tata tertib sekolah. Apabila selama masa pembinaan tidak mengalami
                perubahan, maka siswa yang bersangkutan dikembalikan kepada orang tua/wali.</p>
            <p style="text-indent: 0; margin-bottom: 20px;">Demikian surat pernyataan ini saya buat dengan sesungguhnya
                tanpa ada tekanan dari siapapun.</p>

            <div class="signature-area">
                <table class="signature-table">
                    <tr>
                        <td>Mengetahui,<br>Orang Tua/Wali siswa</td>
                        <td>Denpasar, <?php echo $tanggal_sekarang_alt; ?><br>Siswa yang bersangkutan</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="height:60px;"></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:bottom;">
                            <?php if ($nama_ortu !== '................................................'): ?>
                                <span class="name-underline"><?php echo htmlspecialchars($nama_ortu); ?></span>
                            <?php else: ?>
                                <span class="name-dots"></span>
                            <?php endif; ?>
                        </td>
                        <td style="vertical-align:bottom;"><span
                                class="name-underline"><?php echo htmlspecialchars($data['nama']); ?></span></td>
                    </tr>
                </table>

                <table class="signature-table" style="margin-top: 15px;">
                    <tr>
                        <td>Guru Bimbingan Konseling</td>
                        <td>Guru Wali Kelas</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="height:60px;"></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:bottom;"><span class="name-line">Ni Putu Chintya Pradnya Suari,
                                S.Pd</span></td>
                        <td style="vertical-align:bottom;"><span class="name-dots"></span></td>
                    </tr>
                </table>

                <table class="signature-table" style="margin-top: 15px; width: 100%;">
                    <tr>
                        <td style="width:100%;">Mengetahui<br>Wakasek Kesiswaan</td>
                    </tr>
                    <tr>
                        <td style="height:60px;"></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:bottom;"><span class="name-line">Bagus Putu Eka Wijaya, S.Kom</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>