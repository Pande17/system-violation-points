<?php
require_once 'models/jurusan.php';
require_once 'helpers/responseHelper.php';

class JurusanController
{
    private $model;

    public function __construct()
    {
        $this->model = new JurusanModel();
    }

    public function getJurusan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if ($id) {
                $jurusan = $this->model->getJurusanById($id);
                if ($jurusan) {
                    Success($jurusan, "Data jurusan dengan ID $id berhasil diambil");
                }
                else {
                    NotFound(null, "Jurusan dengan ID $id tidak ditemukan");
                }
            }
            else {
                $jurusan = $this->model->getAllJurusan();
                Success($jurusan, "Data jurusan berhasil diambil");
            }
        }
    }

    public function createJurusan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);

            $kode = isset($data['kode']) ? $data['kode'] : '';
            $nama = isset($data['nama']) ? $data['nama'] : '';
            $ketua_kompetensi = isset($data['ketua_kompetensi']) ? $data['ketua_kompetensi'] : '';

            if (empty($kode) || empty($nama) || empty($ketua_kompetensi)) {
                BadRequest(null, 'Data penting belum lengkap (kode, nama, ketua_kompetensi)!');
                return;
            }

            try {
                $result = $this->model->addJurusan($kode, $nama, $ketua_kompetensi);
                if ($result) {
                    Created($data, 'Data Jurusan berhasil ditambahkan');
                }
                else {
                    Conflict(null, 'Gagal menambahkan data Jurusan! Coba lagi.');
                }
            }
            catch (Exception $e) {
                Conflict(null, 'Error: ' . $e->getMessage());
            }
        }
    }

    public function updateJurusan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = isset($data['id']) ? $data['id'] : (isset($_GET['id']) ? $_GET['id'] : null);

            $kode = isset($data['kode']) ? $data['kode'] : '';
            $nama = isset($data['nama']) ? $data['nama'] : '';
            $ketua_kompetensi = isset($data['ketua_kompetensi']) ? $data['ketua_kompetensi'] : '';

            if (!$id) {
                BadRequest(null, 'ID Jurusan diperlukan');
                return;
            }

            try {
                $result = $this->model->updateJurusan($id, $kode, $nama, $ketua_kompetensi);
                if ($result) {
                    Success($data, 'Data Jurusan berhasil diupdate');
                }
                else {
                    Conflict(null, 'Gagal mengupdate data Jurusan! Coba lagi.');
                }
            }
            catch (Exception $e) {
                Conflict(null, 'Error: ' . $e->getMessage());
            }
        }
    }

    public function deleteJurusan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = isset($data['id']) ? $data['id'] : (isset($_GET['id']) ? $_GET['id'] : null);

            if (!$id) {
                BadRequest(null, 'ID Jurusan diperlukan');
                return;
            }

            $result = $this->model->deleteJurusan($id);
            if ($result) {
                Success(null, 'Data Jurusan berhasil dihapus');
            }
            else {
                Conflict(null, 'Gagal menghapus data Jurusan! Coba lagi.');
            }
        }
    }
}
