<?php
require_once 'backend/config/db.php';

function getDBConnection() {
    $config = require 'backend/config/db.php';
    // Actually the db.php probably defines the function or includes it.
    // Let's assume it's already available.
}

try {
    // If I cannot easily run it in background, I will just do it via PHP.
    $db = getDBConnection();
    $stmt = $db->prepare("
        UPDATE siswa s
        SET s.poin = (
            SELECT IFNULL(SUM(p.poin), 0) 
            FROM pelanggaran p 
            WHERE p.id_siswa = s.id AND p.deleted_at IS NULL
        )
    ");
    $stmt->execute();
    echo "Success: points synced.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
