<?php
require_once '../../config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    // Valida datos requeridos...
    if (
        empty($_POST['referencia']) || empty($_POST['encargado']) ||
        empty($_POST['plazo'])
    ) {
        $errores[] = "Completa todos los campos obligatorios.";
    }

    if (true) {
        // 1. Registrar la hoja de ruta
        $stmt = $pdo->prepare("INSERT INTO tarea 
            ( referencia, encargado, plazo)
            VALUES (?, ?, ?)");
        $stmt->execute([
            $_POST['referencia'],
            $_POST['encargado'],
            $_POST['plazo'],
        ]);
    }
}
    
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Nuevo Documento</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-normal">

    <?php include '../components/menu.php'; ?>
    <div class="w-full max-w-screen-xl mx-auto px-4 md:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-sm shadow-sm border border-gray-200 p-5 md:p-7">
            <h2 class="text-lg md:text-xl font-semibold mb-5">Registrar Nueva tarea</h2>

            <form method="POST" autocomplete="off" class="space-y-5">
                <!-- Datos del documento principal -->
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm mb-1">Referencia</label>
                        <input name="referencia" class="border rounded-sm px-3 py-2 w-full text-sm">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Encargado</label>
                        <input type="text" name="encargado" min="1" class="border rounded-sm px-3 py-2 w-full text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Plazo (dias):</label>
                        <input type="text" id="plazo" name="plazo" min="1" value="30" class="border rounded-sm px-3 py-2 w-full text-sm">
                    </div>
                </div>
                
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="bg-blue-700 text-white px-7 py-2 rounded-sm hover:bg-blue-900 transition">Registrar</button>
                    <a href="documentos.php" class="px-7 py-2 rounded-sm bg-gray-200 hover:bg-gray-300 text-gray-600">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const formulario = document.querySelector('form');
        const inputEmision = document.getElementById('emision_recepcion');
        const inputPlazo = document.getElementById('plazo');
        const inputEntrega = document.getElementById('entrega');
        const resumenPlazo = document.getElementById('resumen_plazo');
        const usuariosListado = document.getElementById('usuarios_listado');

        const inputRemitenteTexto = document.getElementById('remitente_texto');
        const inputDestinatarioTexto = document.getElementById('destinatario_texto');
        const inputRemitenteId = document.getElementById('remitente');
        const inputDestinatarioId = document.getElementById('destinatario');
        const inputNroHojaRuta = document.querySelector('input[name="nro_registro_correlativo"]');

        function normalizarTexto(texto) {
            return (texto || '').trim().toLowerCase();
        }

        function obtenerIdUsuarioPorDescripcion(descripcion) {
            const buscado = normalizarTexto(descripcion);
            if (!buscado) {
                return '';
            }

            const opcion = Array.from(usuariosListado.options).find((item) => normalizarTexto(item.value) === buscado);
            return opcion ? (opcion.dataset.id || '') : '';
        }

        function sincronizarAutocompleteUsuario(inputTexto, inputId, etiquetaCampo) {
            const id = obtenerIdUsuarioPorDescripcion(inputTexto.value);
            inputId.value = id;

            if (inputTexto.value && !id) {
                inputTexto.setCustomValidity(`Seleccione un ${etiquetaCampo} valido de la lista.`);
            } else {
                inputTexto.setCustomValidity('');
            }
        }

        function parseDateYmd(value) {
            const [y, m, d] = value.split('-').map(Number);
            return new Date(y, (m || 1) - 1, d || 1);
        }

        function formatYmd(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        function sumarDiasHabiles(fechaInicio, diasHabiles) {
            const fecha = parseDateYmd(fechaInicio);
            let restantes = Math.max(0, Number(diasHabiles) || 0);

            while (restantes > 0) {
                fecha.setDate(fecha.getDate() + 1);
                const dia = fecha.getDay(); // 0=Dom, 6=Sab
                if (dia !== 0 && dia !== 6) {
                    restantes -= 1;
                }
            }

            return formatYmd(fecha);
        }

        function contarDiasHabiles(fechaInicio, fechaFin) {
            const inicio = parseDateYmd(fechaInicio);
            const fin = parseDateYmd(fechaFin);
            if (fin <= inicio) return 0;

            let actual = new Date(inicio);
            let dias = 0;

            while (actual < fin) {
                actual.setDate(actual.getDate() + 1);
                const dia = actual.getDay();
                if (dia !== 0 && dia !== 6) {
                    dias += 1;
                }
            }

            return dias;
        }

        function actualizarEntregaDesdePlazo() {
            if (!inputEmision.value || !inputPlazo.value) {
                return;
            }

            const fechaCalculada = sumarDiasHabiles(inputEmision.value, inputPlazo.value);
            inputEntrega.value = fechaCalculada;
            resumenPlazo.textContent = `Fecha de entrega calculada: ${fechaCalculada} (${inputPlazo.value} dias habiles).`;
        }

        function actualizarPlazoDesdeEntrega() {
            if (!inputEmision.value || !inputEntrega.value) {
                return;
            }

            const dias = contarDiasHabiles(inputEmision.value, inputEntrega.value);
            if (dias > 0) {
                inputPlazo.value = String(dias);
                resumenPlazo.textContent = `Plazo ajustado a ${dias} dias habiles segun la fecha de entrega seleccionada.`;
            }
        }

        inputEmision.addEventListener('change', actualizarEntregaDesdePlazo);
        inputPlazo.addEventListener('input', actualizarEntregaDesdePlazo);
        inputEntrega.addEventListener('change', actualizarPlazoDesdeEntrega);

        inputRemitenteTexto.addEventListener('input', () => sincronizarAutocompleteUsuario(inputRemitenteTexto, inputRemitenteId, 'remitente'));
        inputRemitenteTexto.addEventListener('change', () => sincronizarAutocompleteUsuario(inputRemitenteTexto, inputRemitenteId, 'remitente'));

        inputDestinatarioTexto.addEventListener('input', () => sincronizarAutocompleteUsuario(inputDestinatarioTexto, inputDestinatarioId, 'destinatario'));
        inputDestinatarioTexto.addEventListener('change', () => sincronizarAutocompleteUsuario(inputDestinatarioTexto, inputDestinatarioId, 'destinatario'));

        formulario.addEventListener('submit', (event) => {
            sincronizarAutocompleteUsuario(inputRemitenteTexto, inputRemitenteId, 'remitente');
            sincronizarAutocompleteUsuario(inputDestinatarioTexto, inputDestinatarioId, 'destinatario');

            if (!inputRemitenteId.value || !inputDestinatarioId.value) {
                event.preventDefault();
                if (!inputRemitenteId.value) {
                    inputRemitenteTexto.reportValidity();
                } else if (!inputDestinatarioId.value) {
                    inputDestinatarioTexto.reportValidity();
                }
                return;
            }

            const nroHojaRuta = (inputNroHojaRuta?.value || '').trim();
            const mensajeConfirmacion = nroHojaRuta
                ? `Confirme el Nro. Hoja de Ruta: ${nroHojaRuta}.\n\n¿Desea continuar con el registro?`
                : 'No se ingresó Nro. Hoja de Ruta.\n\n¿Desea continuar con el registro?';

            if (!window.confirm(mensajeConfirmacion)) {
                event.preventDefault();
            }
        });

        if (inputEmision.value && inputPlazo.value && !inputEntrega.value) {
            actualizarEntregaDesdePlazo();
        }
    </script>
</body>

</html>