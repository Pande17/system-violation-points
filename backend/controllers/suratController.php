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
                $surat = $this->model->getAllSurat();
                Success($surat, "Daftar surat berhasil diambil");
            } catch (Exception $e) {
                InternalServerError(null, "Server Error: " . $e->getMessage());
            }
        }
    }
}
