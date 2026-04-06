<?php
$usuario = $_SESSION['usuario'] ?? [];
$rol = strtolower($usuario['rol'] ?? '');
$idUsuarioSesion = !empty($usuario['idusuario']) ? (int)$usuario['idusuario'] : null;

require_once __DIR__ . '/../../config/db.php';

$notificacionesEntrada = [];
$notificacionesRetrasadas = [];
$totalEntrada = 0;
$totalRetrasados = 0;

if (!empty($usuario['idusuario'])) {
    $idUsuario = (int)$usuario['idusuario'];

    $sqlCountEntrada = "
        SELECT COUNT(*)
        FROM hoja_ruta hr
        JOIN derivaciones d ON d.idderivaciones = (
            SELECT MAX(d2.idderivaciones)
            FROM derivaciones d2
            WHERE d2.idhoja_ruta = hr.idhoja_ruta
        )
        WHERE d.destinatario = :idUsuario
    ";
    $stmtCountEntrada = $pdo->prepare($sqlCountEntrada);
    $stmtCountEntrada->execute([':idUsuario' => $idUsuario]);
    $totalEntrada = (int)$stmtCountEntrada->fetchColumn();

    $sqlEntrada = "
        SELECT hr.idhoja_ruta, hr.referencia, hr.estado, hr.nro_registro_correlativo, d.ingreso
        FROM hoja_ruta hr
        JOIN derivaciones d ON d.idderivaciones = (
            SELECT MAX(d2.idderivaciones)
            FROM derivaciones d2
            WHERE d2.idhoja_ruta = hr.idhoja_ruta
        )
        WHERE d.destinatario = :idUsuario
        ORDER BY COALESCE(d.ingreso, hr.emision_recepcion) DESC
        LIMIT 8
    ";
    $stmtEntrada = $pdo->prepare($sqlEntrada);
    $stmtEntrada->execute([':idUsuario' => $idUsuario]);
    $notificacionesEntrada = $stmtEntrada->fetchAll(PDO::FETCH_ASSOC);

    $sqlCountRetrasados = "
        SELECT COUNT(*)
        FROM hoja_ruta hr
        JOIN derivaciones d ON d.idderivaciones = (
            SELECT MAX(d2.idderivaciones)
            FROM derivaciones d2
            WHERE d2.idhoja_ruta = hr.idhoja_ruta
        )
        WHERE d.destinatario = :idUsuario
          AND hr.estado = 'retrasado'
    ";
    $stmtCountRetrasados = $pdo->prepare($sqlCountRetrasados);
    $stmtCountRetrasados->execute([':idUsuario' => $idUsuario]);
    $totalRetrasados = (int)$stmtCountRetrasados->fetchColumn();

    $sqlRetrasados = "
        SELECT hr.idhoja_ruta, hr.referencia, hr.estado, hr.nro_registro_correlativo, d.ingreso
        FROM hoja_ruta hr
        JOIN derivaciones d ON d.idderivaciones = (
            SELECT MAX(d2.idderivaciones)
            FROM derivaciones d2
            WHERE d2.idhoja_ruta = hr.idhoja_ruta
        )
        WHERE d.destinatario = :idUsuario
          AND hr.estado = 'retrasado'
        ORDER BY COALESCE(d.ingreso, hr.emision_recepcion) DESC
        LIMIT 8
    ";
    $stmtRetrasados = $pdo->prepare($sqlRetrasados);
    $stmtRetrasados->execute([':idUsuario' => $idUsuario]);
    $notificacionesRetrasadas = $stmtRetrasados->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    
    <header class="min-h-[60px] tracking-wide relative z-50">
        <nav class="bg-blue-950 border-gray-200 lg:px-6 py-2">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
                <!-- Logo -->
                <a href="" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <h2 class="py-4 text-white text-xl">Gestor de tareas</h2>
                </a>

                <!-- Perfil -->
                
            </div>
        </nav>

        <!-- Menú secundario -->
        <nav class="bg-neutral-secondary-soft border-y border-default border-default">
            <div class="max-w-screen-xl px-4 py-3 mx-auto">
                <div class="flex items-center">
                    <ul class="flex flex-row font-sens mt-0 space-x-8 rtl:space-x-reverse text-sm items-center">
                        
                            <li><a href="/examen/public/views/dashboard.php" class="hover:text-blue-700">Inicio</a></li>
                            <li><a href="/examen/public/views/registrar_documento.php" class="hover:text-blue-700">Crear Tarea</a></li>
                            <li><a href="/examen/public/views/documentos.php" class="hover:text-blue-700">Ver tareas</a></li>
                            
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <script>
        var toggleOpen = document.getElementById('toggleOpen');
        var toggleClose = document.getElementById('toggleClose');
        var collapseMenu = document.getElementById('collapseMenu');

        function handleClick() {
            if (!collapseMenu) {
                return;
            }

            if (collapseMenu.style.display === 'block') {
                collapseMenu.style.display = 'none';
            } else {
                collapseMenu.style.display = 'block';
            }
        }

        if (toggleOpen && collapseMenu) {
            toggleOpen.addEventListener('click', handleClick);
        }

        if (toggleClose && collapseMenu) {
            toggleClose.addEventListener('click', handleClick);
        }

        const toggleNotification = document.getElementById('notification-dropdown-toggle');
        const notificationMenu = document.getElementById('notification-dropdown-menu');
        const submenuToggles = document.querySelectorAll('.submenu-toggle');
        const submenuPanels = document.querySelectorAll('.submenu-panel');

        function closeAllSubmenus(exceptPanel = null) {
            submenuPanels.forEach((panel) => {
                if (panel !== exceptPanel) {
                    panel.classList.add('hidden');
                }
            });

            submenuToggles.forEach((toggle) => {
                if (!exceptPanel || toggle.nextElementSibling !== exceptPanel) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        }

        submenuToggles.forEach((toggle) => {
            toggle.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                const panel = toggle.nextElementSibling;
                if (!panel) {
                    return;
                }

                const isHidden = panel.classList.contains('hidden');
                closeAllSubmenus();

                if (isHidden) {
                    panel.classList.remove('hidden');
                    toggle.setAttribute('aria-expanded', 'true');
                }
            });
        });

        if (toggleNotification && notificationMenu) {
            toggleNotification.addEventListener('click', (event) => {
                event.stopPropagation();
                notificationMenu.classList.toggle('hidden');
            });
        }

        // Menú de perfil desplegable
        const toggleDropdown = document.getElementById('profile-dropdown-toggle');
        const dropdownMenu = document.getElementById('profile-dropdown-menu');

        if (toggleDropdown && dropdownMenu) {
            toggleDropdown.addEventListener('click', (event) => {
                event.stopPropagation();
                dropdownMenu.classList.toggle('hidden');

                if (notificationMenu && !notificationMenu.classList.contains('hidden')) {
                    notificationMenu.classList.add('hidden');
                }
            });

            document.addEventListener('click', (event) => {
                if (notificationMenu && toggleNotification && !notificationMenu.contains(event.target) && !toggleNotification.contains(event.target)) {
                    notificationMenu.classList.add('hidden');
                }

                if (!dropdownMenu.contains(event.target) && !toggleDropdown.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                }

                if (!event.target.closest('.submenu-toggle') && !event.target.closest('.submenu-panel')) {
                    closeAllSubmenus();
                }
            });
        }
    </script>
</body>