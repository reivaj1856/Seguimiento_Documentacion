<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../../login/login.php');
    exit;
}

$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actual = $_POST['pass_actual'] ?? '';
    $nueva1 = $_POST['pass_nueva1'] ?? '';
    $nueva2 = $_POST['pass_nueva2'] ?? '';

    $usuario = $_SESSION['usuario'];

    // Obtener la contraseña almacenada actualmente
    $stmt = $pdo->prepare("SELECT contrasena FROM usuario WHERE idusuario = ?");
    $stmt->execute([$usuario['idusuario']]);
    $db_pass = $stmt->fetchColumn();

    // Validaciones
    if (!$actual || !$nueva1 || !$nueva2) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!password_verify($actual, $db_pass)) {
        $error = "La contraseña actual no es correcta.";
    } elseif ($nueva1 !== $nueva2) {
        $error = "La nueva contraseña no coincide en ambas casillas.";
    } elseif (strlen($nueva1) < 6) {
        $error = "La nueva contraseña debe tener al menos 6 caracteres.";
    } else {
        // Hashear la nueva contraseña y actualizar
        $hash = password_hash($nueva1, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE usuario SET contrasena = ? WHERE idusuario = ?");
        $stmt->execute([$hash, $usuario['idusuario']]);
        $msg = "¡Contraseña actualizada correctamente!";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <style>
        .container {
            max-width: 500px;
            margin: 60px auto;
            background: #fafbfc;
            border: 1px solid #e3e3e3;
            border-radius: 5px;
            padding: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type=password] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 25px;
            background: #167ac6;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .msg {
            color: green;
            margin-top: 9px;
        }

        .error {
            color: #d10000;
            margin-top: 9px;
        }

        a {
            color: #167ac6;
            font-size: 1em;
            margin-top: 18px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <?php include '../components/menu.php'; ?>
    <div class="container">
        <h2 class="py-2">Cambiar Contraseña</h2>
        <?php if ($msg): ?>
            <div class="msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form class="font-sens" method="POST" autocomplete="off">
            <div class="form-group">
                <label for="pass_actual">Contraseña actual:</label>
                <input type="password" name="pass_actual" id="pass_actual" required>
            </div>
            <div class="form-group">
                <label for="pass_nueva1">Nueva contraseña:</label>
                <input type="password" name="pass_nueva1" id="pass_nueva1" required minlength="6">
            </div>
            <div class="form-group">
                <label for="pass_nueva2">Confirmar nueva contraseña:</label>
                <input type="password" name="pass_nueva2" id="pass_nueva2" required minlength="6">
            </div>
            <button type="submit">Guardar</button>
        </form>
        <a href="../components/perfil.php">&larr; Volver a Mi Perfil</a>
    </div>
</body>

</html>