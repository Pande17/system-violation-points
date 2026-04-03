<?php
require_once 'config/db.php';

class JurusanModel
{
    private $db;

    public function __construct()
    {
        $this->db = getDBConnection();
    }

    public function getAllJurusan()
    {
        $stmt = $this->db->prepare("SELECT * FROM jurusan WHERE deleted_at IS NULL ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getJurusanById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM jurusan WHERE id = :id AND deleted_at IS NULL");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addJurusan($kode, $nama, $ketua_kompetensi)
    {
        $stmt = $this->db->prepare("INSERT INTO jurusan (kode, nama, ketua_kompetensi) VALUES (:kode, :nama, :ketua_kompetensi)");
        $stmt->bindParam(':kode', $kode);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':ketua_kompetensi', $ketua_kompetensi);
        return $stmt->execute();
    }

    public function updateJurusan($id, $kode, $nama, $ketua_kompetensi)
    {
        $stmt = $this->db->prepare("UPDATE jurusan SET kode = :kode, nama = :nama, ketua_kompetensi = :ketua_kompetensi, updated_at = NOW() WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':kode', $kode);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':ketua_kompetensi', $ketua_kompetensi);
        return $stmt->execute();
    }

    public function deleteJurusan($id)
    {
        $stmt = $this->db->prepare("UPDATE jurusan SET deleted_at = NOW() WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
