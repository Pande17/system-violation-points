<?php
require_once 'models/surat.php';
require_once 'helpers/responseHelper.php';

class PernyataanSiswaController
{
    private $model;

    public function __construct()
    {
        $this->model = new SuratModel();
    }

    public function create()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $id_siswa = $data['id_siswa'] ?? null;
            $masalah = $data['masalah'] ?? '';
            $tanggal = $data['tanggal'] ?? date('Y-m-d');
            $created_by = $data['created_by'] ?? 1;

            if (!$id_siswa) {
                BadRequest(null, "ID Siswa wajib diisi");
                return;
            }

            $id = $this->model->addPernyataanSiswa($id_siswa, $masalah, $tanggal, $created_by);
            if ($id) {
                Success(['id' => $id, 'type' => 'Pernyataan Siswa'], "Surat pernyataan siswa berhasil disimpan");
            } else {
                Conflict(null, "Gagal menyimpan surat pernyataan siswa");
            }
        } catch (Exception $e) {
            InternalServerError(null, $e->getMessage());
        }
    }

    public function print($id)
    {
        $surat = $this->model->getSuratById($id, 'surat_pernyataan_siswa');
        if (!$surat) {
            echo "Data tidak ditemukan";
            return;
        }
        $data = $surat;
        $data['jenis_surat'] = 'Surat Pernyataan Siswa';
        $viewPath = __DIR__ . "/../views/surat/print_pernyataan_siswa.php";
        if (file_exists($viewPath)) include $viewPath;
        else echo "Template tidak ditemukan";
    }
}
