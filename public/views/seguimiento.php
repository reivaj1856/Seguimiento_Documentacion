<?php
session_start();
require_once '../../config/db.php';

// ==== Filtros ====
$idUsuario = isset($_GET['idusuario']) && $_GET['idusuario'] !== '' ? $_GET['idusuario'] : null;
$fecha_desde = $_GET['fecha_desde'] ?? null;
$fecha_hasta = $_GET['fecha_hasta'] ?? null;
$estado = $_GET['estado'] ?? null;
$tipo_correspondencia = $_GET['tipo_correspondencia'] ?? null;
$idUnidad = isset($_GET['idUnidad']) && $_GET['idUnidad'] !== '' ? $_GET['idUnidad'] : null;
$nro_hoja_ruta = isset($_GET['nro_hoja_ruta']) && $_GET['nro_hoja_ruta'] !== '' ? $_GET['nro_hoja_ruta'] : null;

// ==== Paginación ====
$per_page_options = [10, 20, 30, 50, 100];
$per_page = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $per_page_options) ? (int)$_GET['per_page'] : 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page-1)*$per_page;

// ==== Llamada al procedimiento filtrado ====
$stmt = $pdo->prepare(
    "CALL sp_documentos_ejecutivos_filtrado(:fecha_desde, :fecha_hasta, :estado, :tipo_correspondencia, :idUnidad, :nro_hoja_ruta, :idUsuario)"
);

$stmt->execute([
    ':fecha_desde'         => ($fecha_desde && trim($fecha_desde) !== '') ? $fecha_desde . ' 00:00:00' : null,
    ':fecha_hasta'         => ($fecha_hasta && trim($fecha_hasta) !== '') ? $fecha_hasta . ' 23:59:59' : null,
    ':estado'              => ($estado && trim($estado) !== '') ? $estado : null,
    ':tipo_correspondencia'=> ($tipo_correspondencia && trim($tipo_correspondencia) !== '') ? $tipo_correspondencia : null,
    ':idUnidad'            => ($idUnidad !== null && $idUnidad !== '') ? $idUnidad : null,
    ':nro_hoja_ruta'       => ($nro_hoja_ruta && trim($nro_hoja_ruta) !== '') ? $nro_hoja_ruta : null,
    ':idUsuario'           => ($idUsuario !== null && $idUsuario !== '') ? $idUsuario : null
]);
$all_documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// ==== Paginación de resultados ====
$total = count($all_documentos);
$total_pages = (int)ceil($total / $per_page);
$first_entry = $total == 0 ? 0 : $offset+1;
$last_entry = min($offset+$per_page, $total);
$documentos = array_slice($all_documentos, $offset, $per_page);

// ==== Opciones ====
$estados = ['en proceso','archivado','retrasado'];
// Excluir unidades con nombre 'admin'
$unidades_stmt = $pdo->query("SELECT idUnidad, nombre_area FROM Unidad WHERE LOWER(nombre_area) != 'admin' ORDER BY nombre_area");
$unidades = $unidades_stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener usuarios si hay una unidad seleccionada
$usuarios = [];
if ($idUnidad !== null) {
    $usuarios_stmt = $pdo->prepare("SELECT * FROM Usuario WHERE id_unidad = :idUnidad ");
    $usuarios_stmt->execute([':idUnidad' => $idUnidad]);
    $usuarios = $usuarios_stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ======= Utilidades =========
function filters_except($except = []) {
    $params = $_GET;
    foreach ($except as $ex) unset($params[$ex]);
    return http_build_query($params);
}
function clean_filters_link() {
    $params = $_GET;
    $per_page = isset($params['per_page']) ? $params['per_page'] : 10;
    return 'documentos.php?per_page='.intval($per_page);
}
?>

<!-- Aquí va el HTML: igual que tu estructura existente -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Documentos Ejecutivos Filtrados</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .main-accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .main-accordion-content.active {
            max-height: 2000px;
            overflow-y: auto;
        }
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .accordion-content.active {
            max-height: 300px;
            overflow-y: auto;
        }
        .accordion-button::after {
            content: '▼';
            transition: transform 0.3s ease;
            font-size: 12px;
        }
        .accordion-button.active::after {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="bg-gray-50">
<?php include '../components/menu.php'; ?>
<div class="p-6 mx-auto max-w-7xl">

    <!-- Búsqueda de Hoja de Ruta (Arriba del filtro) -->
    <div class="p-6 bg-gray-50 mx-auto mb-8 border bg-gray-100 rounded-sm max-w-3xl">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="text-sm font-normal text-gray-900">BUSCAR ARCHIVADOS POR:</h3>
        </div>
        <div class="flex items-center gap-4">
            <div>
                <label for="nro_hoja_ruta_search_top" class="block text-xs font-normal text-gray-700 mb-2">Nro Único Correspondencia</label>
            </div>
            <div  class="flex-1">
                <input type="text" name="nro_hoja_ruta" id="nro_hoja_ruta_search_top"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       value="<?= htmlspecialchars($_GET['nro_hoja_ruta'] ?? '') ?>"
                       placeholder="Ingrese número de correspondencia"
                       onkeypress="if(event.key === 'Enter') buscarHojaRutaTop()">
            </div>
            <button type="button" onclick="buscarHojaRutaTop()"
                    class="bg-blue-900 hover:bg-blue-950 text-white font-normal px-8 py-2.5 rounded-lg transition-colors duration-200 flex items-center gap-2 whitespace-nowrap h-fit">
                BUSCAR
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Filtros y tabla igual que tu diseño -->
    <table class="min-w-full mb-8 font-normal text-gray-700 text-sm">
        <thead>
            <tr>
                <th class="px-2 py-2 font-normal">H RUTA</th>
                <th class="px-2 py-2 font-normal">DATOS DE ORIGEN</th>
                <th class="px-2 py-2 font-normal">REMITE</th>
                <th class="px-2 py-2 font-normal">DESTINATARIO</th>
                <th class="px-2 py-2 font-normal">ACCIONES</th>
            </tr>
        </thead>
        <tbody>
        <?php $i=0; foreach($documentos as $doc): $i++; ?>
            <tr class=" <?= $i % 2 == 0 ? 'bg-gray-50' : 'bg-white' ?>">
                <td class="px-2 py-2 nm-corresp"><?= htmlspecialchars($doc['idhoja_ruta'] ?? 'no encontrado') ?></td>
                <td class="px-2 py-2 ">
                    <div>
                        <span class="remitente">Remitente:</span>
                        <span><?= htmlspecialchars($doc['primera_derivacion_remitente'] ?? '') ?><?= isset($doc['unidad']) ? " - {$doc['unidad']}" : '' ?></span>
                    </div>
                    <div>
                        <span class="remitente">Destinatario:</span>
                        <span><?= htmlspecialchars($doc['primera_derivacion_destinatario'] ?? '') ?></span>
                    </div>
                    <div>
                        <span>Ems./Recep:</span>
                        <span><?= isset($doc['primera_derivacion_ingreso']) ? date('d/m/Y', strtotime($doc['primera_derivacion_ingreso'])) : '-' ?></span>
                        <span> Salida:</span>
                        <span><?= isset($doc['primera_derivacion_salida']) ? date('d/m/Y', strtotime($doc['primera_derivacion_salida'])) : '-' ?></span>
                    </div>
                    <div><span class="referencia">Referencia:</span> <?= htmlspecialchars($doc['primera_derivacion_instructivo'] ?? '') ?></div>
                </td>
                <td class="px-2 py-2">
                    <div>
                        <span class="remitente">Remite:</span>
                        <span><?= htmlspecialchars($doc['ultima_derivacion_remitente'] ?? '') ?><?= isset($doc['unidad']) ? " - {$doc['unidad']}" : '' ?></span>
                    </div>
                    <?php if(!empty($doc['ultima_derivacion_salida'])): ?>
                        <div><span style="color:teal;">Salida:</span> <?= date("d/m/Y H:i", strtotime($doc['ultima_derivacion_salida'])) ?></div>
                    <?php endif; ?>
                    <?php if(!empty($doc['cant_hojas_anexos'])): ?>
                        <div><span style="color:#1d4ed8;">Hojas/Anexos:</span> <?= $doc['cant_hojas_anexos'] ?></div>
                    <?php endif; ?>
                </td>
                <td class="px-2 py-2">
                    <div class="detalle-box px-2 py-1">
                        <span style="color:#1569c7; font-weight:500;"> <?= htmlspecialchars($doc['cant_derivaciones'] ?? '') ?> Destinatario:</span><br>
                        <span><?= htmlspecialchars($doc['ultima_derivacion_destinatario'] ?? '') ?></span><br>
                        <span style="color:#049f2f; font-weight:500;">Proveido:</span> <?= htmlspecialchars($doc['ultima_derivacion_instructivo'] ?? '') ?>
                    </div>
                </td>
                
                <td class="px-2 py-2 text-center">
                <div class="flex gap-2 justify-center">
                    <!-- VER -->
                    <a href="../actions/ver_hoja_ruta.php?id=<?= $doc['idhoja_ruta'] ?>"
                       class="inline-flex items-center px-0.5 py-1 text-sm border rounded hover:bg-blue-200"
                       title="Ver">
                        <!-- ejemplo icono ojo SVG -->
                        <img src="../../assets/icons/observar.png" class="w-7" alt="Logo" />
                        </a>
                    <!-- EDITAR -->
                    <a href="../actions/editar_hoja_ruta.php?id=<?= $doc['idhoja_ruta'] ?>"
                       class="inline-flex items-center px-0.5 py-1 text-sm border rounded hover:bg-yellow-200"
                       title="Editar">
                        <!-- ejemplo icono lápiz SVG -->
                        <img src="../../assets/icons/editar.png" class="w-7" alt="Logo" />
                    </a>
                    <!-- ELIMINAR -->
                    <a href="../actions/eliminar_hoja_ruta.php?id=<?= $doc['idhoja_ruta'] ?>"
                        class="inline-flex items-center px-0.5 py-1 text-sm border rounded hover:bg-red-200"
                        title="Eliminar"
                        onclick="return confirm('¿Está seguro de eliminar esta hoja de ruta?');">
                            <img src="../../assets/icons/eliminar1.png" class="w-7" alt="Eliminar" />
                        </a>
                </div>
            </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación y rango -->
    <div class="md:flex mt-6 m-4 items-center">
        <p class="text-sm text-slate-600 flex-1">
            Mostrando <?= $first_entry ?> a <?= $last_entry ?> de <?= $total ?> documentos
        </p>
        <div class="flex items-center max-md:mt-4">
            <!-- Paginación -->
            <ul class="flex space-x-3 justify-center">
                <li class="flex items-center justify-center <?= $page <= 1 ? 'bg-gray-200' : 'cursor-pointer' ?> w-8 h-8 border border-gray-300 text-gray-500">
                    <?php if ($page > 1): ?>
                        <a href="?<?= filters_except(['page']) ?>&page=<?= $page-1 ?>">&lt;</a>
                    <?php else: ?>
                        <span>&lt;</span>
                    <?php endif; ?>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="flex items-center justify-center <?= $i == $page ? 'bg-blue-800 text-white border border-blue-800' : 'border border-gray-300 hover:border-blue-800 text-slate-900' ?> cursor-pointer text-sm font-normal px-3 h-8 ">
                        <?php if ($i == $page): ?>
                            <?= $i ?>
                        <?php else: ?>
                            <a href="?<?= filters_except(['page']) ?>&page=<?= $i ?>"><?= $i ?></a>
                        <?php endif; ?>
                    </li>
                <?php endfor; ?>
                <li class="flex items-center justify-center <?= $page >= $total_pages ? 'bg-gray-200' : 'cursor-pointer' ?> w-8 h-8 border border-gray-300 text-gray-500">
                    <?php if ($page < $total_pages): ?>
                        <a href="?<?= filters_except(['page']) ?>&page=<?= $page+1 ?>">&gt;</a>
                    <?php else: ?>
                        <span>&gt;</span>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>

<script>
// Toggle Acordeón Principal
function toggleMainAccordion() {
    const content = document.getElementById('main-accordion-content');
    const icon = document.getElementById('main-accordion-icon');
    
    content.classList.toggle('active');
    icon.style.transform = content.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0deg)';
}

// Toggle todos los acordeones internos a la vez
function toggleAllAccordions() {
    const contents = document.querySelectorAll('.accordion-content');
    const buttons = document.querySelectorAll('.accordion-button');
    
    // Verificar si alguno está abierto
    const isAnyOpen = Array.from(contents).some(content => content.classList.contains('active'));
    
    // Si alguno está abierto, cerrar todos. Si todos están cerrados, abrir todos
    contents.forEach((content, index) => {
        if (isAnyOpen) {
            content.classList.remove('active');
            buttons[index].classList.remove('active');
        } else {
            content.classList.add('active');
            buttons[index].classList.add('active');
        }
    });
}

// Lógica para checkbox de Unidad
document.getElementById('check_todas').addEventListener('change', function() {
    const checks = document.querySelectorAll('.unidad-check');
    checks.forEach(check => check.checked = this.checked);
});

// Cuando seleccionan una unidad, recargar usuarios
document.querySelectorAll('.unidad-check').forEach(check => {
    check.addEventListener('change', function() {
        if (this.checked) {
            // Desmarcar otras unidades
            document.querySelectorAll('.unidad-check').forEach(c => {
                if (c !== this) c.checked = false;
            });
            document.getElementById('check_todas').checked = false;
            
            const url = new URL(window.location.href);
            url.searchParams.set('idUnidad', this.value);
            url.searchParams.delete('idusuario');
            window.location.href = url.toString();
        }
    });
});

// Lógica para checkbox de Estado
document.getElementById('check_estado_todos').addEventListener('change', function() {
    const checks = document.querySelectorAll('.estado-check');
    checks.forEach(check => check.checked = this.checked);
});

// Lógica para checkbox de Tipo
document.getElementById('check_tipo_todos').addEventListener('change', function() {
    const checks = document.querySelectorAll('.tipo-check');
    checks.forEach(check => check.checked = this.checked);
});

// Aplicar filtros de fecha
function aplicarFiltros() {
    const url = new URL(window.location.href);
    
    // Unidad
    const unidadChecked = document.querySelector('.unidad-check:checked');
    if (unidadChecked) {
        url.searchParams.set('idUnidad', unidadChecked.value);
    } else {
        url.searchParams.delete('idUnidad');
    }
    
    // Usuario
    const usuarioChecked = document.querySelector('.usuario-check:checked');
    if (usuarioChecked) {
        url.searchParams.set('idusuario', usuarioChecked.value);
    } else {
        url.searchParams.delete('idusuario');
    }
    
    // Estado
    const estadoChecked = document.querySelector('.estado-check:checked');
    if (estadoChecked) {
        url.searchParams.set('estado', estadoChecked.value);
    } else {
        url.searchParams.delete('estado');
    }
    
    // Tipo
    const tipoChecked = document.querySelector('.tipo-check:checked');
    if (tipoChecked) {
        url.searchParams.set('tipo_correspondencia', tipoChecked.value);
    } else {
        url.searchParams.delete('tipo_correspondencia');
    }
    
    // Fechas
    const fechaDesde = document.getElementById('fecha_desde').value;
    const fechaHasta = document.getElementById('fecha_hasta').value;
    
    if (fechaDesde) url.searchParams.set('fecha_desde', fechaDesde);
    else url.searchParams.delete('fecha_desde');
    
    if (fechaHasta) url.searchParams.set('fecha_hasta', fechaHasta);
    else url.searchParams.delete('fecha_hasta');
    
    url.searchParams.delete('nro_hoja_ruta');
    window.location.href = url.toString();
}

// Buscar por hoja de ruta
function buscarHojaRutaTop() {
    const nroHojaRuta = document.getElementById('nro_hoja_ruta_search_top').value.trim();
    
    if (!nroHojaRuta) {
        alert('Ingrese un número de correspondencia');
        return;
    }
    
    const url = new URL(window.location.href);
    url.searchParams.set('nro_hoja_ruta', nroHojaRuta);
    url.searchParams.delete('idUnidad');
    url.searchParams.delete('idusuario');
    url.searchParams.delete('estado');
    url.searchParams.delete('tipo_correspondencia');
    url.searchParams.delete('fecha_desde');
    url.searchParams.delete('fecha_hasta');
    
    window.location.href = url.toString();
}
</script>
</div>
</body>
</html>