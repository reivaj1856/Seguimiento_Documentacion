<?php
session_start();
require_once '../../config/db.php';
$id = intval($_GET['id']);

$unidades = $pdo->query("SELECT idUnidad, nombre_area FROM Unidad")->fetchAll(PDO::FETCH_ASSOC);
$roles = ['administrador','auditor','encargado','dependiente'];
$estados = ['activo', 'externo', 'inactivo'];

$stmt = $pdo->prepare("SELECT * FROM usuario WHERE idusuario=:id");
$stmt->execute([':id'=>$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $contrasena = ($_POST['contrasena']) ? password_hash($_POST['contrasena'], PASSWORD_DEFAULT) : $user['contrasena'];
    $stmt = $pdo->prepare("UPDATE usuario SET usuario=:usuario, descripcion=:descripcion, estado=:estado,
        cargo=:cargo, id_unidad=:id_unidad, contrasena=:contrasena, rol=:rol WHERE idusuario=:id");
    $stmt->execute([
        ':usuario' => $_POST['usuario'],
        ':descripcion' => $_POST['descripcion'],
        ':estado' => $_POST['estado'],
        ':cargo' => $_POST['cargo'],
        ':id_unidad' => $_POST['id_unidad'],
        ':contrasena' => $contrasena,
        ':rol' => $_POST['rol'],
        ':id' => $id
    ]);
    header("Location: gestion_usuarios.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><title>Editar Usuario</title>
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
  <h3>Editar Usuario</h3>
  <form method="POST">
    <label>Usuario:</label>
    <input name="usuario" value="<?= htmlspecialchars($user['usuario']) ?>" required>
    <label>Nombre/Descripción:</label>
    <input name="descripcion" value="<?= htmlspecialchars($user['descripcion']) ?>" required>
    <label>Cargo:</label>
    <input name="cargo" value="<?= htmlspecialchars($user['cargo']) ?>" required>
    <label>Unidad:</label>
    <select name="id_unidad" required>
      <?php foreach($unidades as $u): ?>
        <option value="<?= $u['idUnidad'] ?>" <?= ($u['idUnidad']==$user['id_unidad'] ? 'selected':'') ?>><?= htmlspecialchars($u['nombre_area']) ?></option>
      <?php endforeach; ?>
    </select>
    <label>Estado:</label>
    <select name="estado" required>
      <?php foreach($estados as $e): ?>
        <option <?= ($e==$user['estado'])?'selected':'' ?>><?= $e ?></option>
      <?php endforeach; ?>
    </select>
    <label>Rol:</label>
    <select name="rol" required>
      <?php foreach($roles as $r): ?>
        <option <?= ($r==$user['rol'])?'selected':'' ?>><?= $r ?></option>
      <?php endforeach; ?>
    </select>
    <label>Nueva contraseña (vacío para no cambiar):</label>
    <input type="password" name="contrasena">
    <button class="btn" type="submit">Guardar</button>
    <a href="gestion_usuarios.php" class="btn" style="background:#aeaeae;margin-left:8px;">Cancelar</a>
  </form>
</div>
</body>
</html>