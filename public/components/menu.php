<?php
$usuario = $_SESSION['usuario'] ?? [];
$rol = strtolower($usuario['rol'] ?? '');

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
                    <img src="../../assets/icons/logos/logo_sedepos.png" class="w-36" alt="Logo" />
                </a>

                <!-- Perfil -->
                <div class="flex items-center max-sm:ml-auto space-x-6 relative">
                    <ul>
                        <li class="text-white font-sens"><?= htmlspecialchars($usuario['descripcion']) ?> </li>

                    </ul>
                    <ul>
                        <li id="notification-dropdown-toggle" class="relative cursor-pointer">
                            <div class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="transition hover:fill-gray-300" viewBox="0 0 24 24">
                                    <path d="M12 2a6 6 0 0 0-6 6v3.586L4.293 13.293A1 1 0 0 0 5 15h14a1 1 0 0 0 .707-1.707L18 11.586V8a6 6 0 0 0-6-6Zm0 20a3 3 0 0 0 2.83-2H9.17A3 3 0 0 0 12 22Z"/>
                                </svg>
                                <?php if ($totalEntrada > 0): ?>
                                    <span class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-semibold px-1.5 py-0.5 rounded-full"><?= $totalEntrada ?></span>
                                <?php endif; ?>
                            </div>

                            <div id="notification-dropdown-menu" class="hidden absolute right-0 top-10 bg-white z-20 shadow-lg py-4 px-4 rounded-sm w-[340px] max-h-[420px] overflow-y-auto">
                                <h6 class="font-semibold text-gray-700 text-sm mb-3">Notificaciones</h6>

                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-xs font-semibold text-blue-700">Bandeja de Entrada</p>
                                        <span class="text-xs text-gray-500"><?= $totalEntrada ?> total</span>
                                    </div>
                                    <?php if (!empty($notificacionesEntrada)): ?>
                                        <ul class="space-y-2">
                                            <?php foreach ($notificacionesEntrada as $doc): ?>
                                                <li>
                                                    <a href="/sedeposv3/public/actions/ver_hoja_ruta.php?id=<?= (int)$doc['idhoja_ruta'] ?>" class="block p-2 rounded-sm hover:bg-gray-100">
                                                        <p class="text-xs text-gray-700 font-medium">HR #<?= htmlspecialchars($doc['idhoja_ruta']) ?> - Nro <?= htmlspecialchars($doc['nro_registro_correlativo']) ?></p>
                                                        <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($doc['referencia'] ?? 'Sin referencia') ?></p>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="text-xs text-gray-500">No tienes documentos en bandeja de entrada.</p>
                                    <?php endif; ?>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-xs font-semibold text-red-700">Retrasados</p>
                                        <span class="text-xs text-gray-500"><?= $totalRetrasados ?> total</span>
                                    </div>
                                    <?php if (!empty($notificacionesRetrasadas)): ?>
                                        <ul class="space-y-2">
                                            <?php foreach ($notificacionesRetrasadas as $doc): ?>
                                                <li>
                                                    <a href="/sedeposv3/public/actions/ver_hoja_ruta.php?id=<?= (int)$doc['idhoja_ruta'] ?>" class="block p-2 rounded-sm hover:bg-red-50">
                                                        <p class="text-xs text-gray-700 font-medium">HR #<?= htmlspecialchars($doc['idhoja_ruta']) ?> - Nro <?= htmlspecialchars($doc['nro_registro_correlativo']) ?></p>
                                                        <p class="text-xs text-red-600 truncate"><?= htmlspecialchars($doc['referencia'] ?? 'Sin referencia') ?></p>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="text-xs text-gray-500">No tienes documentos retrasados asignados.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul>
                        <li id="profile-dropdown-toggle" class="relative cursor-pointer">
                            <!-- Icono del perfil -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                fill="white" class="transition hover:fill-gray-300"
                                viewBox="0 0 512 512">
                                <path
                                    d="M437.02 74.981C388.667 26.629 324.38 0 256 0S123.333 26.629 74.98 74.981C26.629 123.333 0 187.62 0 256s26.629 132.667 74.98 181.019C123.333 485.371 187.62 512 256 512s132.667-26.629 181.02-74.981C485.371 388.667 512 324.38 512 256s-26.629-132.667-74.98-181.019zM256 482c-66.869 0-127.037-29.202-168.452-75.511C113.223 338.422 178.948 290 256 290c-49.706 0-90-40.294-90-90s40.294-90 90-90 90 40.294 90 90-40.294 90-90 90c77.052 0 142.777 48.422 168.452 116.489C383.037 452.798 322.869 482 256 482z" />
                            </svg>

                            <!-- Menú desplegable -->
                            <div id="profile-dropdown-menu"
                                class="hidden absolute right-0 top-10 bg-white z-20 shadow-lg py-5 px-6 rounded-sm w-64">
                                <div class="border-b pb-2 mb-3">
                                    <h6 class="font-semibold text-gray-700 text-sm py-1">
                                        👤 <?= htmlspecialchars($usuario['descripcion']) ?>
                                    </h6>
                                    <p class="text-xs text-gray-500"><?= ucfirst($rol) ?></p>
                                </div>
                                <ul class="space-y-2">
                                    <li><a href="index.php?r=perfil" class="block text-sm text-gray-600 hover:text-indigo-600">Manual de usuario</a></li>
                                    <li><a href="../components/perfil.php" class="block text-sm text-gray-600 hover:text-indigo-600">Mi perfil</a></li>
                                    <li><a href="index.php?r=configuracion" class="block text-sm text-gray-600 hover:text-indigo-600">Configuración</a></li>
                                    <li><a href="index.php?r=ayuda" class="block text-sm text-gray-600 hover:text-indigo-600">Ayuda</a></li>
                                    <li>
                                        <hr class="my-2 border-gray-300">
                                    </li>
                                    <li>
                                        <a href="../components/logout.php"
                                            onclick="return confirm('¿Seguro que deseas salir?')"
                                            class="block text-sm text-red-600 font-normal hover:text-red-700">Cerrar sesión</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Menú secundario -->
        <nav class="bg-neutral-secondary-soft border-y border-default border-default">
            <div class="max-w-screen-xl px-4 py-3 mx-auto">
                <div class="flex items-center">
                    <ul class="flex flex-row font-sens mt-0 space-x-8 rtl:space-x-reverse text-sm items-center">
                        <?php if ($rol === 'administrador'): ?>
                            <li><a href="/sedeposv3/public/views/dashboard_admin.php">Inicio</a></li>
                            <li><a href="/sedeposv3/public/views/documentos.php">Documentos</a></li>
                            <li><a href="/sedeposv3/public/views/gestion_usuarios.php">Gestión de usuarios</a></li>
                            <li><a href="/sedeposv3/public/views/gestion_unidades.php">Unidades</a></li>
                            <li><a href="/sedeposv3/public/views/reportes.php">Reportes</a></li>
                        <?php elseif ($rol === 'auditor'): ?>
                            <li><a href="../views/dashboard_auditor.php">Inicio</a></li>
                            <li><a href="index.php?r=documentos">Documentos</a></li>
                            <li><a href="index.php?r=reportes">Reportes</a></li>
                        <?php else: ?>
                            <li><a href="/sedeposv3/public/views/dashboard.php" class="hover:text-blue-700">Inicio</a></li>

                            <li class="relative">
                                <a href="javascript:void(0)" class="submenu-toggle hover:text-blue-700 flex items-center gap-1" aria-expanded="false">
                                    Hoja de Ruta
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 16a1 1 0 0 1-.71-.29l-6-6a1 1 0 1 1 1.42-1.42L12 13.59l5.29-5.3a1 1 0 0 1 1.42 1.42l-6 6A1 1 0 0 1 12 16z"/>
                                    </svg>
                                </a>
                                <ul class="submenu-panel absolute left-0 top-full mt-1 z-50 hidden bg-white shadow-lg rounded-sm min-w-[240px] py-2">
                                    <li><a class="block px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-gray-100" href="/sedeposv3/public/views/archivados.php">Archivados</a></li>
                                    <li><a class="block px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-gray-100" href="/sedeposv3/public/views/documentos.php?bandeja=entrada">Bandeja Entrada</a></li>
                                    <li><a class="block px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-gray-100" href="/sedeposv3/public/views/documentos.php?bandeja=enviados">Enviados</a></li>
                                    <li><a class="block px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-gray-100" href="/sedeposv3/public/views/registrar_documento.php">Generar Hoja de Ruta</a></li>
                                    <li><a class="block px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-gray-100" href="/sedeposv3/public/views/documentos.php?historial=1">Historico Generar HR</a></li>
                                    <li><a class="block px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-gray-100" href="/sedeposv3/public/views/documentos.php?bandeja=recibidos_derivar">Recibidos/Derivar</a></li>
                                </ul>
                            </li>

                            <li class="relative">
                                <a href="javascript:void(0)" class="submenu-toggle hover:text-blue-700 flex items-center gap-1" aria-expanded="false">
                                    Seguimiento
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 16a1 1 0 0 1-.71-.29l-6-6a1 1 0 1 1 1.42-1.42L12 13.59l5.29-5.3a1 1 0 0 1 1.42 1.42l-6 6A1 1 0 0 1 12 16z"/>
                                    </svg>
                                </a>
                                <ul class="submenu-panel absolute left-0 top-full mt-1 z-50 hidden bg-white shadow-lg rounded-sm min-w-[220px] py-2">
                                    <li><a class="block px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-gray-100" href="/sedeposv3/public/views/seguimiento.php?filtro=cite_referencia">Por Cite/Referencia</a></li>
                                    <li><a class="block px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-gray-100" href="/sedeposv3/public/views/seguimiento.php?filtro=nro_unico_hr">Por Nro Unico HR</a></li>
                                </ul>
                            </li>

                            <li class="relative">
                                <a href="javascript:void(0)" class="submenu-toggle hover:text-blue-700 flex items-center gap-1" aria-expanded="false">
                                    Reportes
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 16a1 1 0 0 1-.71-.29l-6-6a1 1 0 1 1 1.42-1.42L12 13.59l5.29-5.3a1 1 0 0 1 1.42 1.42l-6 6A1 1 0 0 1 12 16z"/>
                                    </svg>
                                </a>
                                <ul class="submenu-panel absolute left-0 top-full mt-1 z-50 hidden bg-white shadow-lg rounded-sm min-w-[260px] py-2">
                                    <li><a class="block px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-gray-100" href="/sedeposv3/public/views/reportes.php?tipo=ingresos">Reporte Ingresos</a></li>
                                    <li><a class="block px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-gray-100" href="/sedeposv3/public/views/reportes.php?tipo=nro_hr_gestion">Reporte por Nro HR Gestion</a></li>
                                    <li><a class="block px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-gray-100" href="/sedeposv3/public/views/reportes.php?tipo=origen_unidad">Reporte por Origen Unidad</a></li>
                                    <li><a class="block px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-gray-100" href="/sedeposv3/public/views/reportes.php?tipo=salidas">Reporte Salidas</a></li>
                                </ul>
                            </li>

                            <li class="relative">
                                <a href="javascript:void(0)" class="submenu-toggle hover:text-blue-700 flex items-center gap-1" aria-expanded="false">
                                    Pendientes
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 16a1 1 0 0 1-.71-.29l-6-6a1 1 0 1 1 1.42-1.42L12 13.59l5.29-5.3a1 1 0 0 1 1.42 1.42l-6 6A1 1 0 0 1 12 16z"/>
                                    </svg>
                                </a>
                                <ul class="submenu-panel absolute left-0 top-full mt-1 z-50 hidden bg-white shadow-lg rounded-sm min-w-[200px] py-2">
                                    <li><a class="block px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-gray-100" href="/sedeposv3/public/views/pendientes.php">Ver pendientes</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
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