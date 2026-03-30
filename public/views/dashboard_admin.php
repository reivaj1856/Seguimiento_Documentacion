<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header('Location: ../../login/login.php');
    exit;
}

// Obtener conteos de hojas de ruta según estado
function contarPorEstado($pdo, $estado)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM hoja_ruta WHERE estado = ?");
    $stmt->execute([$estado]);
    return $stmt->fetchColumn();
}
$enproceso = contarPorEstado($pdo, 'en proceso');
$archivados = contarPorEstado($pdo, 'archivado');
$retrasado  = contarPorEstado($pdo, 'retrasado');

// Los otros estados sólo son placeholders, puedes personalizar estos valores o quitarlos.
$pendientes = 0;   // ¿Cómo se determina "pendientes" en tu modelo?
$observados = 0;   // ¿Observados es igual a "retrasado"? Si sí, usa $retrasado
$eliminados = 0;   // No tienes eliminado en tu modelo SQL actual

// Obtener últimos movimientos (ejemplo: últimos registros en derivaciones)
$stmt = $pdo->query("SELECT d.idderivaciones, 
                            CONCAT('De ', u1.usuario, ' para ', u2.usuario, ' - ', IFNULL(d.instructivo_proveido, '')) AS detalle, 
                            d.salida AS fecha 
                     FROM derivaciones d
                     LEFT JOIN usuario u1 ON d.remitente = u1.idusuario
                     LEFT JOIN usuario u2 ON d.destinatario = u2.idusuario
                     ORDER BY d.salida DESC LIMIT 10");
$ultimos_movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de Gestión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php include '../components/menu.php'; ?>
      
    <div class="bg-gray-50 min-h-screen p-6">
       
        <div class="max-w-7xl max-lg:max-w-2xl max-sm:max-w-sm mx-auto">
            <h2 class="text-3xl font-semibold text-slate-900 mb-6">Explore nuestras <span class="text-blue-700">funcionalidades</span></h2>
            <div>
                    <img class="mx-auto w-auto h-max max-w-xl" src="../../assets/icons/logos/logo_sedepos_color.png" alt="">
   
                 </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Documentos -->
                 
                <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                    <!-- Icon -->
                    <img src="../../assets/icons/carpeta.png" class="w-10" alt="Logo" />
                    <div class="mt-6">

                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Documentos</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">Administra, gestiona y consulta todos tus documentos generados en el sistema.</p>
                        <div class="mt-6">
                            <a href="../views/documentos.php" class="flex items-center flex-wrap justify-between gap-2 border border-gray-200 cursor-pointer text-[15px] font-medium rounded-3xl pl-5 pr-3 h-14 w-full hover:bg-purple-100 transition-all duration-300">
                                Ver Documentos
                                <div class="w-11 h-11 rounded-full bg-blue-900 flex justify-center items-center">
                                    <!-- Arrow Icon -->
                                    <svg width="18" height="18" ...>
                                        <path d="..." />
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Gestión / Reportes -->
                <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                    <img src="../../assets/icons/reportes.png" class="w-10" alt="Logo" />
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Gestión y Reportes</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">Visualiza reportes, genera estadísticas y controla la gestión de tu área.</p>
                        <div class="mt-6">
                            <a href="index.php?r=reportes" class="flex items-center flex-wrap justify-between gap-2 border border-gray-200 cursor-pointer text-[15px] font-medium rounded-3xl pl-5 pr-3 h-14 w-full hover:bg-purple-100 transition-all duration-300">
                                Ver Reportes
                                <div class="w-11 h-11 rounded-full bg-blue-900 flex justify-center items-center">
                                    <svg width="18" height="18" ...>
                                        <path d="..." />
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Correspondencia / ejemplo extra -->
                <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                    <img src="../../assets/icons/asignados.png" class="w-10" alt="Logo" />
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Gestión de usuarios</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">Administra y consulta los usuarios del sistema.</p>
                        <div class="mt-6">
                            <a href="index.php?r=gestion_correspondencia" class="flex items-center flex-wrap justify-between gap-2 border border-gray-200 cursor-pointer text-[15px] font-medium rounded-3xl pl-5 pr-3 h-14 w-full hover:bg-purple-100 transition-all duration-300">
                                Ver Gestión
                                <div class="w-11 h-11 rounded-full bg-blue-900 flex justify-center items-center">
                                    <svg width="18" height="18" ...>
                                        <path d="..." />
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Unidades -->
                <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                    <!-- Icon -->
                    <img src="../../assets/icons/carpeta.png" class="w-10" alt="Logo" />
                    <div class="mt-6">

                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Unidades</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">Administra, gestiona y consulta todas las unidades del sistema.</p>
                        <div class="mt-6">
                            <a href="../views/unidades.php" class="flex items-center flex-wrap justify-between gap-2 border border-gray-200 cursor-pointer text-[15px] font-medium rounded-3xl pl-5 pr-3 h-14 w-full hover:bg-purple-100 transition-all duration-300">
                                Ver Unidades
                                <div class="w-11 h-11 rounded-full bg-blue-900 flex justify-center items-center">
                                    <!-- Arrow Icon -->
                                    <svg width="18" height="18" ...>
                                        <path d="..." />
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</body>