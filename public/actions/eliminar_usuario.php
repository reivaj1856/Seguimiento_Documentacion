<?php
session_start();
require_once '../../config/db.php';
$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT usuario FROM usuario WHERE idusuario=:id");
$stmt->execute([':id'=>$id]);
$user = $stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['confirm']=='SI') {
    $del = $pdo->prepare("DELETE FROM usuario WHERE idusuario=:id");
    $del->execute([':id'=>$id]);
    header("Location: gestion_usuarios.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><title>Eliminar Usuario</title>
  <style>
    body { font-family:sans-serif; background:#eef2f8; }
    .confirm-box {max-width:400px; margin:40px auto; background:white; border-radius:8px; box-shadow:0 0 8px #bbb; padding:33px;}
    .btn { background:#2376b2;color:white;border-radius:4px;padding:8px 14px;font-size:13px;border:none;}
    .btn-cancel { background:#666;}
  </style>
</head>
<body>
<div class="confirm-box">
  <h3>Eliminar Usuario</h3>
  <form method="POST">
    <p>¿Está seguro que desea eliminar al usuario <strong><?= htmlspecialchars($user) ?></strong>?</p>
    <button class="btn" type="submit" name="confirm" value="SI">Sí, eliminar</button>
    <a href="gestion_usuarios.php" class="btn btn-cancel">No, cancelar</a>
  </form>
</div>
</body>
</html>