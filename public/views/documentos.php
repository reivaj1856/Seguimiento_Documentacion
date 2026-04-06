<?php
require_once '../../config/db.php';


$stmt = $pdo->query("SELECT * FROM tarea");
$tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    <!-- Filtros y tabla igual que tu diseño -->
    <table class="min-w-full mb-8 font-normal text-gray-700 text-sm">
        <thead>
            <tr>
                <th class="px-2 py-2 font-normal">Tarea</th>
                <th class="px-2 py-2 font-normal">Encargado</th>
                <th class="px-2 py-2 font-normal">Plazo</th>
                <th class="px-2 py-2 font-normal">Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php $i=0; foreach($tareas as $tarea): $i++; ?>
            <tr class=" <?= $i % 2 == 0 ? 'bg-gray-50' : 'bg-white' ?>">
                <td class="px-2 py-2 nm-corresp"><?= htmlspecialchars($tarea['idtarea'] ?? 'no encontrado') ?></td>

                <td class="px-2 py-2">
                    <div>
                        <span class="remitente">Encargado:</span>
                        <span><?= htmlspecialchars($tarea['referencia'] ?? '') ?></span>
                    </div>
                    
                </td>
                <td class="px-2 py-2">
                    <div>
                        <span class="remitente">plazo:</span>
                        <span><?= htmlspecialchars($tarea['plazo'] ?? '') ?></span>
                    </div>
                    
                </td>

                <td class="px-2 py-2 text-center">
                <div class="flex gap-2 justify-center">
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