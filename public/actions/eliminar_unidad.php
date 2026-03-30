<?php
session_start();
require_once '../../config/db.php';
$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT nombre_area FROM Unidad WHERE idUnidad=:id");
$stmt->execute([':id'=>$id]);
$nombre = $stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['confirm']=='SI') {
    // Elimina solo si no tiene usuarios asociados, puedes adaptar esto.
    $count = $pdo->query("SELECT COUNT(*) FROM usuario WHERE id_unidad=$id")->fetchColumn();
    if ($count == 0) {
        $del = $pdo->prepare("DELETE FROM Unidad WHERE idUnidad=:id");
        $del->execute([':id'=>$id]);
        header("Location: ../views/gestion_unidades.php"); exit;
    }
    $error = "No se puede eliminar, tiene usuarios asociados.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><title>Eliminar Unidad</title>
  <style>
    body { font-family:sans-serif; background:#eef2f8; }
    .confirm-box {max-width:400px; margin:40px auto; background:white; border-radius:8px; box-shadow:0 0 8px #bbb; padding:33px;}
    .btn { background:#2376b2;color:white;border-radius:4px;padding:8px 14px;font-size:13px;border:none;}
    .btn-cancel { background:#666;}
    .error {color:red;margin-bottom:14px;}
  </style>
</head>
<body>
<div class="confirm-box">
  <h3>Eliminar Unidad</h3>
  <?php if(!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="POST">
    <p>¿Está seguro que desea eliminar la unidad <strong><?= htmlspecialchars($nombre) ?></strong>?</p>
    <button class="btn" type="submit" name="confirm" value="SI">Sí, eliminar</button>
    <a href="../views/gestion_unidades.php" class="btn btn-cancel">No, cancelar</a>
  </form>
</div>
</body>
</html>