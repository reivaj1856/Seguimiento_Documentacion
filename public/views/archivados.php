<?php
session_start();
require_once '../../config/db.php';

$usuarioSesion = $_SESSION['usuario'] ?? [];
$idUsuarioSesion = (int)($usuarioSesion['idusuario'] ?? 0);

if ($idUsuarioSesion <= 0) {
    header('Location: login.php');
    exit;
}

// ==== Busqueda ==== 
$nro_hoja_ruta = isset($_GET['nro_hoja_ruta']) && $_GET['nro_hoja_ruta'] !== '' ? $_GET['nro_hoja_ruta'] : null;

// ==== Paginacion ==== 
$per_page_options = [10, 20, 30, 50, 100];
$per_page = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $per_page_options, true) ? (int)$_GET['per_page'] : 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;

// Estado archivado fijo + ultimo destinatario = usuario logueado
$stmt = $pdo->prepare(
    "CALL sp_documentos_ejecutivos_filtrado(:fecha_desde, :fecha_hasta, :estado, :tipo_correspondencia, :idUnidad, :nro_hoja_ruta, :idUsuario)"
);

$stmt->execute([
    ':fecha_desde'          => null,
    ':fecha_hasta'          => null,
    ':estado'               => 'archivado',
    ':tipo_correspondencia' => null,
    ':idUnidad'             => null,
    ':nro_hoja_ruta'        => ($nro_hoja_ruta && trim($nro_hoja_ruta) !== '') ? $nro_hoja_ruta : null,
    ':idUsuario'            => $idUsuarioSesion,
]);

$all_documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$total = count($all_documentos);
$total_pages = max(1, (int)ceil($total / $per_page));
$first_entry = $total === 0 ? 0 : $offset + 1;
$last_entry = min($offset + $per_page, $total);
$documentos = array_slice($all_documentos, $offset, $per_page);

function filters_except($except = []) {
    $params = $_GET;
    foreach ($except as $ex) {
        unset($params[$ex]);
    }
    return http_build_query($params);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Documentos Archivados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<?php include '../components/menu.php'; ?>

<div class="p-6 mx-auto max-w-7xl">
    <div class="p-6 bg-gray-50 mx-auto mb-8 border bg-gray-100 rounded-sm max-w-3xl">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="text-sm font-normal text-gray-900">BUSCAR ARCHIVADOS POR:</h3>
        </div>
        <div class="flex items-center gap-4">
            <div>
                <label for="nro_hoja_ruta_search_top" class="block text-xs font-normal text-gray-700 mb-2">Nro Unico Correspondencia</label>
            </div>
            <div class="flex-1">
                <input type="text" name="nro_hoja_ruta" id="nro_hoja_ruta_search_top"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       value="<?= htmlspecialchars($_GET['nro_hoja_ruta'] ?? '') ?>"
                       placeholder="Ingrese numero de correspondencia"
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
        <p class="text-xs text-gray-500 mt-3">Mostrando solo documentos archivados donde el ultimo derivado corresponde al usuario logueado.</p>
    </div>

    <table class="min-w-full mb-6 font-normal text-gray-700 text-sm bg-white border border-gray-200 rounded-sm overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-2 py-2 font-normal">H RUTA</th>
                <th class="px-2 py-2 font-normal">DATOS DE ORIGEN</th>
                <th class="px-2 py-2 font-normal">REMITE</th>
                <th class="px-2 py-2 font-normal">DESTINATARIO</th>
                <th class="px-2 py-2 font-normal">ACCIONES</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($documentos)): ?>
            <tr>
                <td colspan="5" class="px-4 py-6 text-center text-gray-500">No se encontraron documentos archivados para tu usuario.</td>
            </tr>
        <?php else: ?>
            <?php $i = 0; foreach ($documentos as $doc): $i++; ?>
                <tr class="<?= $i % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?>">
                    <td class="px-2 py-2 nm-corresp"><?= htmlspecialchars($doc['idhoja_ruta'] ?? 'no encontrado') ?></td>
                    <td class="px-2 py-2">
                        <div><span class="font-medium">Remitente:</span> <?= htmlspecialchars($doc['primera_derivacion_remitente'] ?? '') ?></div>
                        <div><span class="font-medium">Destinatario:</span> <?= htmlspecialchars($doc['primera_derivacion_destinatario'] ?? '') ?></div>
                        <div>
                            <span class="font-medium">Ems./Recep:</span>
                            <?= isset($doc['primera_derivacion_ingreso']) ? date('d/m/Y', strtotime($doc['primera_derivacion_ingreso'])) : '-' ?>
                        </div>
                        <div><span class="font-medium">Referencia:</span> <?= htmlspecialchars($doc['primera_derivacion_instructivo'] ?? '') ?></div>
                    </td>
                    <td class="px-2 py-2">
                        <div><span class="font-medium">Remite:</span> <?= htmlspecialchars($doc['ultima_derivacion_remitente'] ?? '') ?></div>
                        <?php if (!empty($doc['ultima_derivacion_salida'])): ?>
                            <div><span class="font-medium">Salida:</span> <?= date('d/m/Y H:i', strtotime($doc['ultima_derivacion_salida'])) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($doc['cant_hojas_anexos'])): ?>
                            <div><span class="font-medium">Hojas/Anexos:</span> <?= htmlspecialchars($doc['cant_hojas_anexos']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-2 py-2">
                        <div class="border border-gray-200 bg-gray-50 px-2 py-1 rounded-sm">
                            <span class="font-medium"><?= htmlspecialchars($doc['cant_derivaciones'] ?? '') ?> Destinatario:</span><br>
                            <span><?= htmlspecialchars($doc['ultima_derivacion_destinatario'] ?? '') ?></span><br>
                            <span class="font-medium">Proveido:</span> <?= htmlspecialchars($doc['ultima_derivacion_instructivo'] ?? '') ?>
                        </div>
                    </td>
                    <td class="px-2 py-2 text-center">
                        <div class="flex gap-2 justify-center">
                            <a href="../actions/ver_hoja_ruta.php?id=<?= (int)$doc['idhoja_ruta'] ?>" class="inline-flex items-center px-0.5 py-1 text-sm border rounded-sm hover:bg-blue-100" title="Ver">
                                <img src="../../assets/icons/observar.png" class="w-7" alt="Ver" />
                            </a>
                            <a href="../actions/editar_hoja_ruta.php?id=<?= (int)$doc['idhoja_ruta'] ?>" class="inline-flex items-center px-0.5 py-1 text-sm border rounded-sm hover:bg-yellow-100" title="Editar">
                                <img src="../../assets/icons/editar.png" class="w-7" alt="Editar" />
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="md:flex mt-6 items-center">
        <p class="text-sm text-slate-600 flex-1">
            Mostrando <?= $first_entry ?> a <?= $last_entry ?> de <?= $total ?> documentos archivados
        </p>
        <div class="flex items-center max-md:mt-4">
            <ul class="flex space-x-3 justify-center">
                <li class="flex items-center justify-center <?= $page <= 1 ? 'bg-gray-200' : 'cursor-pointer' ?> w-8 h-8 border border-gray-300 text-gray-500">
                    <?php if ($page > 1): ?>
                        <a href="?<?= filters_except(['page']) ?>&page=<?= $page - 1 ?>">&lt;</a>
                    <?php else: ?>
                        <span>&lt;</span>
                    <?php endif; ?>
                </li>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="flex items-center justify-center <?= $i === $page ? 'bg-blue-800 text-white border border-blue-800' : 'border border-gray-300 hover:border-blue-800 text-slate-900' ?> cursor-pointer text-sm font-normal px-3 h-8">
                        <?php if ($i === $page): ?>
                            <?= $i ?>
                        <?php else: ?>
                            <a href="?<?= filters_except(['page']) ?>&page=<?= $i ?>"><?= $i ?></a>
                        <?php endif; ?>
                    </li>
                <?php endfor; ?>

                <li class="flex items-center justify-center <?= $page >= $total_pages ? 'bg-gray-200' : 'cursor-pointer' ?> w-8 h-8 border border-gray-300 text-gray-500">
                    <?php if ($page < $total_pages): ?>
                        <a href="?<?= filters_except(['page']) ?>&page=<?= $page + 1 ?>">&gt;</a>
                    <?php else: ?>
                        <span>&gt;</span>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
function buscarHojaRutaTop() {
    const nroHojaRuta = document.getElementById('nro_hoja_ruta_search_top').value.trim();
    const url = new URL(window.location.href);

    if (nroHojaRuta) url.searchParams.set('nro_hoja_ruta', nroHojaRuta);
    else url.searchParams.delete('nro_hoja_ruta');

    url.searchParams.delete('page');
    window.location.href = url.toString();
}
</script>
</body>
</html>