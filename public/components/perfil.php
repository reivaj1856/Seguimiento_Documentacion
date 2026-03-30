<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../../login/login.php');
    exit;
}

$usuario = $_SESSION['usuario'];

// Suponiendo que tienes campos: usuario, descripcion, telefono, email, cargo, id_unidad
// Adaptar según tus columnas.
$stmt = $pdo->prepare("
    SELECT u.usuario,
           u.descripcion,
           u.estado,
           u.cargo,
           un.nombre_area AS unidad
    FROM usuario u
    LEFT JOIN Unidad un ON u.id_unidad = un.idUnidad
    WHERE u.idusuario = ?
    LIMIT 1
");
$stmt->execute([$usuario['idusuario']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi cuenta</title>
    <style>
        .profile-container { background: #fafbfc; border: 1px solid #e3e3e3; border-radius: 5px; padding: 30px; margin: 20px; }
        .profile-header { font-size: 1.4em; margin-bottom: 10px; }
        .profile-section { border-top: 1px solid #ddd; padding-top: 10px; margin-top: 20px;}
        .section-title { font-weight: bold; margin-bottom: 8px; font-size: 1.1em;}
        .profile-table { margin-left: 30px; }
        .profile-table td { padding: 5px 10px;}
        .profile-icon { margin-right: 6px;}
        .change-pass { color: #167ac6; text-decoration: none; }
        .change-pass:hover { text-decoration: underline; }
        .breadcrumb { font-size: 0.95em; color: #666; margin-bottom: 12px;}
    </style>
</head>
<body>
    <?php include '../components/menu.php'; ?>
    <div class="profile-container mx-auto max-w-screen-lg">
        <div class="profile-header">Información de la cuenta de usuario</div>
        <div class="breadcrumb">
          Inicio &gt; Mi cuenta
        </div>
        <div class="profile-container">
            <span class="profile-icon">&#128100;</span>
            DATOS DE USUARIO
           
            <table class="profile-table">
                <tr>
                    <td>Nombre completo:</td>
                    <td><?= htmlspecialchars($user['descripcion']) ?></td>
                </tr>
                <tr>
                    <td>Dirección:</td>
                    <td><!-- Puede agregar un campo direccion si lo tienes --></td>
                </tr>
                <tr>
                    <td>Teléfono:</td>
                    <td><?= htmlspecialchars($user['telefono'] ?? '') ?></td>
                </tr>
                <tr>
                    <td>Correo Electrónico:</td>
                    <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                </tr>
                <tr>
                    <td>Cargo Laboral:</td>
                    <td><?= htmlspecialchars($user['cargo'] ?? '') ?></td>
                </tr>
                <tr>
                    <td>Unidad Organizacional:</td>
                    <td><?= htmlspecialchars($user['unidad'] ?? '') ?></td>
                </tr>
            </table>
        </div>
        <div class="">
            <span class="profile-icon"></span>
            CAMBIAR CONTRASEÑA
            <div style="margin-left:30px;">
                <a href="../actions/cambiar_password.php" class="change-pass">&#128273; Cambiar Contraseña</a>
            </div>
        </div>
    </div>
</body>
</html>