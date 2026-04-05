<?php
require_once 'models/surat.php';
require_once 'helpers/responseHelper.php';

class PindahSekolahController
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
            if (!$id_siswa) {
                BadRequest(null, "ID Siswa wajib diisi");
                return;
            }

            // Auto-generate nomor_surat if empty or default template
            if (empty($data['nomor_surat']) || strpos($data['nomor_surat'], '...') !== false) {
                $data['nomor_surat'] = $this->model->getNextNomor('surat_pindah_sekolah', 'tanggal', $data['tanggal'] ?? date('Y-m-d'));
            }

            $id = $this->model->addPindahSekolah($data);
            if ($id) {
                Success(['id' => $id, 'type' => 'Pindah Sekolah'], "Surat pindah sekolah berhasil disimpan");
            } else {
                Conflict(null, "Gagal menyimpan surat pindah sekolah");
            }
        } catch (Exception $e) {
            InternalServerError(null, $e->getMessage());
        }
    }

    public function print($id)
    {
        $surat = $this->model->getSuratById($id, 'surat_pindah_sekolah');
        if (!$surat) {
            echo "Data tidak ditemukan";
            return;
        }
        $data = $surat;
        $data['jenis_surat'] = 'Surat Pindah Sekolah';
        $viewPath = __DIR__ . "/../views/surat/print_pindah.php";
        if (file_exists($viewPath)) include $viewPath;
        else echo "Template tidak ditemukan";
    }
}
