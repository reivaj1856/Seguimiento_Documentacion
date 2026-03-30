<?php
session_start();
require_once '../../config/db.php';
$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM Unidad WHERE idUnidad=:id");
$stmt->execute([':id'=>$id]);
$uni = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $stmt = $pdo->prepare("UPDATE Unidad SET nombre_area=:nombre_area WHERE idUnidad=:id");
    $stmt->execute([
        ':nombre_area' => $_POST['nombre_area'],
        ':id' => $id
    ]);
    header("Location: ../views/gestion_unidades.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><title>Editar Unidad</title>
  <style>
    body { font-family: sans-serif; background:#eef2f8;}
    .form-box {max-width:400px; margin:30px auto; background:white; padding:22px; border-radius:8px; box-shadow:0 0 10px #ccc;}
    label {font-size:13px;display:block;margin-bottom:4px;}
    input {width:100%;margin-bottom:14px;padding:4px;}
    .btn { background:#2376b2;color:white;border-radius:4px;padding:9px 18px;font-size:13px;border:none;}
  </style>
</head>
<body>
<div class="form-box">
  <h3>Editar Unidad</h3>
  <form method="POST">
    <label>Nombre:</label>
    <input name="nombre_area" value="<?= htmlspecialchars($uni['nombre_area']) ?>" required>
    <button class="btn" type="submit">Guardar</button>
    <a href="../views/gestion_unidades.php" class="btn" style="background:#aeaeae;margin-left:8px;">Cancelar</a>
  </form>
</div>
</body>
</html>