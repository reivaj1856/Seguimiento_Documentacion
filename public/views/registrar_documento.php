<?php
session_start();
require_once '../../config/db.php';

// Cargar selectores
$usuarios = $pdo->query("SELECT idusuario, descripcion FROM usuario ORDER BY descripcion")->fetchAll(PDO::FETCH_ASSOC);
$unidades = $pdo->query("SELECT idUnidad, nombre_area FROM Unidad ORDER BY nombre_area")->fetchAll(PDO::FETCH_ASSOC);
$tipos = ['interno', 'externo']; // O consulta si tienes tabla

$errores = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Valida datos requeridos...
    if (empty($_POST['referencia']) || empty($_POST['tipo_correspondencia']) ||
        empty($_POST['remitente']) || empty($_POST['destinatario']) ||
        empty($_POST['id_unidad']) || empty($_POST['cant_hojas_anexos'])
    ) {
        $errores[] = "Completa todos los campos obligatorios.";
    }

    if (!$errores) {
        // 1. Registrar la hoja de ruta
        $stmt = $pdo->prepare("INSERT INTO hoja_ruta 
            (nro_registro_correlativo, referencia, tipo_correspondencia, estado, emision_recepcion, entrega, cant_hojas_anexos, id_unidad)
            VALUES (?, ?, ?, 'en proceso', ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nro_registro_correlativo'],
            $_POST['referencia'],
            $_POST['tipo_correspondencia'],
            $_POST['emision_recepcion'],
            $_POST['entrega'],
            $_POST['cant_hojas_anexos'],
            $_POST['id_unidad']
        ]);
        $idhoja_ruta = $pdo->lastInsertId();

        // 2. Registrar la PRIMERA DERIVACIÓN
        $stmt2 = $pdo->prepare("INSERT INTO derivaciones 
            (idhoja_ruta, remitente, destinatario, ingreso, salida, instructivo_proveido, nro_registro_interno)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt2->execute([
            $idhoja_ruta,
            $_POST['remitente'],
            $_POST['destinatario'],
            $_POST['ingreso'],
            $_POST['salida'],
            $_POST['instructivo_proveido'],
            $_POST['nro_registro_interno'] ?? ''
        ]);
        // Redirigir a visualizar o listado
        header("Location: ver.php?id=$idhoja_ruta");
        exit;
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
<div class="max-w-xl mx-auto bg-white rounded-sm shadow p-6 mt-10">
    <h2 class="text-lg font-semibold mb-5">Registrar Nuevo Documento</h2>
    <?php if($errores): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-sm text-sm"><?= implode('<br>', $errores) ?></div>
    <?php endif; ?>
    <form method="POST" autocomplete="off">
        <!-- Datos del documento principal -->
        <div class="mb-4">
            <label class="block text-sm mb-1">Referencia*:</label>
            <input name="referencia" class="border rounded-sm px-3 py-2 w-full text-sm" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm mb-1">Tipo de correspondencia*:</label>
            <select name="tipo_correspondencia" class="border rounded-sm px-3 py-2 w-full text-sm" required>
                <option value="">Seleccione...</option>
                <?php foreach($tipos as $t): ?>
                  <option><?= $t ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm mb-1">Unidad*:</label>
            <select name="id_unidad" class="border rounded-sm px-3 py-2 w-full text-sm" required>
                <option value="">Seleccione...</option>
                <?php foreach($unidades as $u): ?>
                  <option value="<?= $u['idUnidad'] ?>"><?= htmlspecialchars($u['nombre_area']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4 flex gap-2">
            <div class="flex-1">
                <label class="block text-sm mb-1">Nro. Hoja de Ruta:</label>
                <input name="nro_registro_correlativo" class="border rounded-sm px-3 py-2 w-full text-sm">
            </div>
            <div class="flex-1">
                <label class="block text-sm mb-1">Cantidad hojas/anexos*:</label>
                <input type="number" name="cant_hojas_anexos" min="1" class="border rounded-sm px-3 py-2 w-full text-sm" required>
            </div>
        </div>
        <div class="mb-4 flex gap-2">
            <div class="flex-1">
                <label class="block text-sm mb-1">Fecha emisión/recepción*:</label>
                <input type="date" name="emision_recepcion" class="border rounded-sm px-3 py-2 w-full text-sm" required>
            </div>
            <div class="flex-1">
                <label class="block text-sm mb-1">Fecha entrega:</label>
                <input type="date" name="entrega" class="border rounded-sm px-3 py-2 w-full text-sm">
            </div>
        </div>

        <!-- Primera derivación -->
        <h3 class="text-base font-semibold mt-7 mb-2 text-blue-700">Datos de origen</h3>
        <div class="mb-4 flex gap-2">
            <div class="flex-1">
                <label class="block text-sm mb-1">Remitente*:</label>
                <select name="remitente" class="border rounded-sm px-3 py-2 w-full text-sm" required>
                    <option value="">Seleccione...</option>
                    <?php foreach($usuarios as $u): ?>
                        <option value="<?= $u['idusuario'] ?>"><?= htmlspecialchars($u['descripcion']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm mb-1">Destinatario*:</label>
                <select name="destinatario" class="border rounded-sm px-3 py-2 w-full text-sm" required>
                    <option value="">Seleccione...</option>
                    <?php foreach($usuarios as $u): ?>
                        <option value="<?= $u['idusuario'] ?>"><?= htmlspecialchars($u['descripcion']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="mb-4 flex gap-2">
            <div class="flex-1">
                <label class="block text-sm mb-1">Nro. Registro Interno (opcional):</label>
                <input name="nro_registro_interno" class="border rounded-sm px-3 py-2 w-full text-sm">
            </div>
            <div class="flex-1">
                <label class="block text-sm mb-1">Fecha ingreso (opcional):</label>
                <input type="date" name="ingreso" class="border rounded-sm px-3 py-2 w-full text-sm">
            </div>
            <div class="flex-1">
                <label class="block text-sm mb-1">Fecha salida (opcional):</label>
                <input type="date" name="salida" class="border rounded-sm px-3 py-2 w-full text-sm">
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-sm mb-1">Instrucción / Proveído:</label>
            <textarea name="instructivo_proveido" class="border rounded-sm px-3 py-2 w-full text-sm"></textarea>
        </div>
        <button type="submit" class="bg-blue-700 text-white px-7 py-2 rounded hover:bg-blue-900 transition">Registrar</button>
        <a href="documentos.php" class="ml-3 px-7 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-600">Cancelar</a>
    </form>
</div>
</body>
</html>