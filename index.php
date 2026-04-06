<?php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: public/views/dashboard.php');
    exit();
}   
$role = $_SESSION['role'];
$userid = $_SESSION['userid'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Gestión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-6xl mx-auto bg-white rounded shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Panel de Gestión</h1>
        <nav class="flex gap-2 mb-4">
            <?php if ($role === 'administrador'): ?>
                <a href="?tab=documentos" class="px-4 py-2 bg-blue-500 text-white rounded">Documentos</a>
                <a href="?tab=unidades" class="px-4 py-2 bg-green-500 text-white rounded">Unidades</a>
                <a href="?tab=usuarios" class="px-4 py-2 bg-yellow-500 text-white rounded">Usuarios</a>
                <a href="?tab=reportes" class="px-4 py-2 bg-gray-500 text-white rounded">Reportes</a>
            <?php elseif ($role === 'auditor'): ?>
                <a href="?tab=reportes" class="px-4 py-2 bg-gray-500 text-white rounded">Reportes</a>
            <?php else: ?>
                <a href="?tab=inicio" class="px-4 py-2 bg-blue-500 text-white rounded">Inicio</a>
                <a href="?tab=hoja_ruta" class="px-4 py-2 bg-green-500 text-white rounded">Hoja de Ruta</a>
                <a href="?tab=seguimiento" class="px-4 py-2 bg-yellow-500 text-white rounded">Seguimiento</a>
                <a href="?tab=reportes" class="px-4 py-2 bg-gray-500 text-white rounded">Reportes</a>
                <a href="?tab=pendientes" class="px-4 py-2 bg-red-500 text-white rounded">Mis Pendientes</a>
                <a href="?tab=archivados" class="px-4 py-2 bg-indigo-500 text-white rounded">Archivados</a>
            <?php endif; ?>
            <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded ml-auto">Cerrar Sesión</a>
        </nav>

        <div>
            <?php
            $tab = $_GET['tab'] ?? ($role === 'administrador' ? 'documentos' : ($role === 'auditor' ? 'reportes' : 'inicio'));
            switch ($tab) {
                case 'documentos':
                    include 'documentos.php';
                    break;
                case 'unidades':
                    include 'unidades.php';
                    break;
                case 'usuarios':
                    include 'usuarios.php';
                    break;
                case 'reportes':
                    include 'reportes.php';
                    break;
                case 'hoja_ruta':
                    include 'hoja_ruta.php';
                    break;
                case 'seguimiento':
                    include 'seguimiento.php';
                    break;
                case 'pendientes':
                    include 'pendientes.php';
                    break;
                case 'archivados':
                    include 'archivados.php';
                    break;
                case 'inicio':
                default:
                    echo '<div class="text-xl text-gray-700">Bienvenido al sistema.</div>';
                    break;
            }
            ?>
        </div>
    </div>
</body>
</html>