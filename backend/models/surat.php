<?php
require_once 'config/db.php';

class SuratModel
{
    private $db;

    public function __construct()
    {
        $this->db = getDBConnection();
    }

    // 1. SURAT PERNYATAAN SISWA
    public function addPernyataanSiswa($id_siswa, $masalah, $tanggal, $created_by)
    {
        $stmt = $this->db->prepare("INSERT INTO surat_pernyataan_siswa (id_siswa, masalah, tanggal, created_by) VALUES (:id_siswa, :masalah, :tanggal, :created_by)");
        return $stmt->execute([
            ':id_siswa' => $id_siswa,
            ':masalah' => $masalah,
            ':tanggal' => $tanggal,
            ':created_by' => $created_by
        ]) ? $this->db->lastInsertId() : false;
    }

    // 2. SURAT PERNYATAAN ORANG TUA
    public function addPernyataanOrtu($id_siswa, $tanggal, $created_by)
    {
        $stmt = $this->db->prepare("INSERT INTO surat_pernyataan_orang_tua (id_siswa, tanggal, created_by) VALUES (:id_siswa, :tanggal, :created_by)");
        return $stmt->execute([
            ':id_siswa' => $id_siswa,
            ':tanggal' => $tanggal,
            ':created_by' => $created_by
        ]) ? $this->db->lastInsertId() : false;
    }

    // 3. SURAT PEMANGGILAN ORANG TUA
    public function addPemanggilanOrtu($data)
    {
        $stmt = $this->db->prepare("INSERT INTO surat_pemanggilan_orang_tua (nomor_surat, id_siswa, hari_tanggal, pukul, tempat, keperluan, tanggal_pengiriman, created_by) 
                                    VALUES (:nomor_surat, :id_siswa, :hari_tanggal, :pukul, :tempat, :keperluan, :tanggal_pengiriman, :created_by)");
        return $stmt->execute([
            ':nomor_surat' => $data['nomor_surat'],
            ':id_siswa' => $data['id_siswa'],
            ':hari_tanggal' => $data['hari_tanggal'],
            ':pukul' => $data['pukul'],
            ':tempat' => $data['tempat'],
            ':keperluan' => $data['keperluan'],
            ':tanggal_pengiriman' => $data['tanggal_pengiriman'],
            ':created_by' => $data['created_by']
        ]) ? $this->db->lastInsertId() : false;
    }

    // 4. SURAT PINDAH SEKOLAH
    public function addPindahSekolah($data)
    {
        $stmt = $this->db->prepare("INSERT INTO surat_pindah_sekolah (nomor_surat, id_siswa, nama_orang_tua, alamat_orang_tua, tujuan_sekolah, alasan, tanggal, created_by) 
                                    VALUES (:nomor_surat, :id_siswa, :nama_orang_tua, :alamat_orang_tua, :tujuan_sekolah, :alasan, :tanggal, :created_by)");
        return $stmt->execute([
            ':nomor_surat' => $data['nomor_surat'],
            ':id_siswa' => $data['id_siswa'],
            ':nama_orang_tua' => $data['nama_orang_tua'],
            ':alamat_orang_tua' => $data['alamat_orang_tua'],
            ':tujuan_sekolah' => $data['tujuan_sekolah'],
            ':alasan' => $data['alasan'],
            ':tanggal' => $data['tanggal'],
            ':created_by' => $data['created_by']
        ]) ? $this->db->lastInsertId() : false;
    }

    public function getAllSurat()
    {
        $allSurat = [];
        $tableConfigs = [
            'surat_pernyataan_siswa' => [
                'label' => 'Surat Pernyataan Siswa',
                'no_surat_col' => 'NULL',
                'tanggal_col' => 'tanggal',
                'keterangan_col' => 'masalah'
            ],
            'surat_pemanggilan_orang_tua' => [
                'label' => 'Surat Pemanggilan Orang Tua',
                'no_surat_col' => 'nomor_surat',
                'tanggal_col' => 'tanggal_pengiriman',
                'keterangan_col' => 'keperluan'
            ],
            'surat_pernyataan_orang_tua' => [
                'label' => 'Surat Pernyataan Orang Tua',
                'no_surat_col' => 'NULL',
                'tanggal_col' => 'tanggal',
                'keterangan_col' => 'NULL'
            ],
            'surat_pindah_sekolah' => [
                'label' => 'Surat Pindah Sekolah',
                'no_surat_col' => 'nomor_surat',
                'tanggal_col' => 'tanggal',
                'keterangan_col' => 'alasan'
            ]
        ];

        foreach ($tableConfigs as $table => $cfg) {
            try {
                $sql = "
                    SELECT s.id_surat as id, s.id_siswa, :label as jenis_surat, 
                           {$cfg['no_surat_col']} as nomor_surat, 
                           {$cfg['tanggal_col']} as tanggal_surat, 
                           {$cfg['keterangan_col']} as keterangan, 
                           s.created_at,
                           sis.nama as nama_siswa, sis.nis
                    FROM $table s
                    LEFT JOIN siswa sis ON s.id_siswa = sis.id
                ";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':label', $cfg['label']);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $allSurat = array_merge($allSurat, $results);
            } catch (PDOException $e) {
                // Skip tables that might not exist or have error
                error_log("History Fetch Error ($table): " . $e->getMessage());
            }
        }

        // Sort all results by date descending (using created_at as backup)
        usort($allSurat, function($a, $b) {
            return ($b['created_at'] ?? '') <=> ($a['created_at'] ?? '');
        });

        return $allSurat;
    }

    public function getSuratById($id, $table)
    {
        $stmt = $this->db->prepare("
            SELECT s.*, 
                   sis.nama as nama_siswa, sis.nis, sis.kelas, sis.jurusan as siswa_jurusan, 
                   sis.jenis_kelamin as jk_siswa, sis.alamat as alamat_siswa,
                   sis.tempat_lahir as siswa_tempat_lahir, sis.tanggal_lahir as siswa_tanggal_lahir,
                   o.nama as nama_ortu, o.alamat as alamat_ortu, o.no_telp as no_telp_ortu, 
                   o.pekerjaan as pekerjaan_ortu, o.tempat_lahir as ortu_tempat_lahir, o.tanggal_lahir as ortu_tanggal_lahir,
                   k_full.wali_kelas_nama,
                   j.nama as program_keahlian
            FROM $table s
            JOIN siswa sis ON s.id_siswa = sis.id
            LEFT JOIN ortu_wali o ON sis.id_ortuWali = o.id
            LEFT JOIN jurusan j ON (sis.jurusan = j.nama OR sis.jurusan = j.kode)
            LEFT JOIN (
                SELECT CONCAT(k.tingkat, ' ', k.jurusan, ' ', k.kelas) as full_name, g.nama as wali_kelas_nama
                FROM kelas k
                LEFT JOIN guru g ON k.wali_kelas = g.id
                WHERE k.deleted_at IS NULL
            ) k_full ON sis.kelas = k_full.full_name
            WHERE s.id_surat = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteSurat($id, $table)
    {
        $stmt = $this->db->prepare("DELETE FROM $table WHERE id_surat = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getNextNomor($table, $dateColumn, $date)
    {
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        
        $sql = "SELECT COUNT(*) FROM $table WHERE MONTH($dateColumn) = :month AND YEAR($dateColumn) = :year";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':month' => $month, ':year' => $year]);
        $count = $stmt->fetchColumn() + 1;
        
        $romans = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $romanMonth = $romans[(int)$month] ?? $month;
        
        return sprintf("%02d/SMK TI/BG/%s/%d", $count, $romanMonth, $year);
    }
}
