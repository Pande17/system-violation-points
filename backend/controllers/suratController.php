<?php
require_once 'models/surat.php';
require_once 'helpers/responseHelper.php';

class SuratController
{
    private $model;

    public function __construct()
    {
        $this->model = new SuratModel();
    }

    public function getSurat()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            try {
                $id_siswa = isset($_GET['id_siswa']) ? $_GET['id_siswa'] : null;
                $surat = $this->model->getAllSurat();

                if ($id_siswa) {
                    $surat = array_filter($surat, function($s) use ($id_siswa) {
                        return (string)$s['id_siswa'] === (string)$id_siswa;
                    });
                    $surat = array_values($surat); // Re-index array
                }

                Success($surat, "Daftar surat berhasil diambil");
            } catch (Exception $e) {
                InternalServerError(null, "Server Error: " . $e->getMessage());
            }
        }
    }
}
