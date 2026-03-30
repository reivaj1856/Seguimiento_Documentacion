<?php
session_start();
require_once '../../config/db.php';

$unidades = $pdo->query("SELECT idUnidad, nombre_area FROM Unidad")->fetchAll(PDO::FETCH_ASSOC);
$roles = ['administrador','auditor','encargado','dependiente'];
$estados = ['activo', 'externo', 'inactivo'];

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $stmt = $pdo->prepare("INSERT INTO usuario (usuario, descripcion, estado, cargo, id_unidad, contrasena, rol)
                           VALUES (:usuario, :descripcion, :estado, :cargo, :id_unidad, :contrasena, :rol)");
    $stmt->execute([
        ':usuario' => $_POST['usuario'],
        ':descripcion' => $_POST['descripcion'],
        ':estado' => $_POST['estado'],
        ':cargo' => $_POST['cargo'],
        ':id_unidad' => $_POST['id_unidad'],
        ':contrasena' => password_hash($_POST['contrasena'], PASSWORD_DEFAULT),
        ':rol' => $_POST['rol'],
    ]);
    header("Location: gestion_usuarios.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><title>Agregar Usuario</title>
  <style>
    body { font-family: sans-serif; background:#eef2f8;}
    .form-box {max-width:500px; margin:30px auto; background:white; padding:22px; border-radius:8px; box-shadow:0 0 10px #ccc;}
    label {font-size:13px;display:block;margin-bottom:4px;}
    input, select {width:100%;margin-bottom:14px;padding:4px;}
    .btn { background:#2376b2;color:white;border-radius:4px;padding:9px 18px;font-size:13px;border:none;}
  </style>
</head>
<body>
<div class="form-box">
  <h3>Agregar Usuario</h3>
  <form method="POST">
    <label>Usuario:</label>
    <input name="usuario" required>
    <label>Nombre/Descripción:</label>
    <input name="descripcion" required>
    <label>Cargo:</label>
    <input name="cargo" required>
    <label>Unidad:</label>
    <select name="id_unidad" required>
      <option value="">Seleccione...</option>
      <?php foreach($unidades as $u): ?>
        <option value="<?= $u['idUnidad'] ?>"><?= htmlspecialchars($u['nombre_area']) ?></option>
      <?php endforeach; ?>
    </select>
    <label>Estado:</label>
    <select name="estado" required>
      <?php foreach($estados as $e): ?><option><?= $e ?></option><?php endforeach; ?>
    </select>
    <label>Rol:</label>
    <select name="rol" required>
      <?php foreach($roles as $r): ?><option><?= $r ?></option><?php endforeach; ?>
    </select>
    <label>Contraseña:</label>
    <input type="password" name="contrasena" required>
    <button class="btn" type="submit">Guardar</button>
    <a href="gestion_usuarios.php" class="btn" style="background:#aeaeae;margin-left:8px;">Cancelar</a>
  </form>
</div>
</body>
</html>