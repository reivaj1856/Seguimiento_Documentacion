<?php
session_start();
require_once '../../config/db.php';

$idhoja_ruta = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($idhoja_ruta > 0) {
    try {
        $stmt = $pdo->prepare('CALL sp_eliminar_hoja_ruta(:idhoja_ruta)');
        $stmt->execute([':idhoja_ruta' => $idhoja_ruta]);
        $stmt->closeCursor();
        http_response_code(200);
        header("Location: ../views/documentos.php");
    } catch (Exception $e) {
        http_response_code(500);
        echo 'Error al eliminar: ' . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo 'ID inválido: ' . $idhoja_ruta;
}