<?php
session_start();
$idhoja_ruta = isset($_GET['id']) ? intval($_GET['id']) : 0;
require_once '../../config/db.php';

// Procesamiento del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // === Actualiza hoja de ruta principal ===
    $stmt = $pdo->prepare("
        UPDATE hoja_ruta 
        SET tipo_correspondencia = :tipo_correspondencia,
            nro_registro_correlativo = :nro_registro_correlativo,
            estado = :estado,
            cant_hojas_anexos = :cant_hojas_anexos,
            emision_recepcion = :emision_recepcion,
            entrega = :entrega,
            salida = :salida,
            referencia = :referencia
        WHERE idhoja_ruta = :idhoja_ruta");
    $stmt->execute([
        ':tipo_correspondencia' => $_POST['tipo_correspondencia'],
        ':nro_registro_correlativo' => $_POST['nro_registro_correlativo'],
        ':estado' => $_POST['estado'],
        ':cant_hojas_anexos' => $_POST['cant_hojas_anexos'],
        ':emision_recepcion' => $_POST['emision_recepcion'],
        ':entrega' => $_POST['entrega'],
        ':salida' => $_POST['salida'],
        ':referencia' => $_POST['referencia'],
        ':idhoja_ruta' => $idhoja_ruta
    ]);
    // === Actualiza derivaciones (las que estén en el POST) ===
    if (isset($_POST['derivacion']) && is_array($_POST['derivacion'])) {
        foreach ($_POST['derivacion'] as $idderiv => $data) {
            $update_stmt = $pdo->prepare("
                UPDATE derivaciones SET
                    remitente = :remitente,
                    destinatario = :destinatario,
                    nro_registro_interno = :nro_registro_interno,
                    ingreso = :ingreso,
                    salida = :salida,
                    instructivo_proveido = :instructivo_proveido
                WHERE idderivaciones = :idderivaciones");
            $update_stmt->execute([
                ':remitente' => $data['remitente'],
                ':destinatario' => $data['destinatario'],
                ':nro_registro_interno' => $data['nro_registro_interno'],
                ':ingreso' => $data['ingreso'],
                ':salida' => $data['salida'],
                ':instructivo_proveido' => $data['instructivo_proveido'],
                ':idderivaciones' => $idderiv
            ]);
        }
    }
    header("Location: ver_hoja_ruta.php?id=$idhoja_ruta");
    exit;
}

// === Obtención de datos para editar ===
$stmt = $pdo->prepare('CALL sp_get_hoja_ruta(:idhoja_ruta)');
$stmt->execute([':idhoja_ruta' => $idhoja_ruta]);
$hoja = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$stmt2 = $pdo->prepare('CALL sp_get_derivaciones_hoja_ruta(:idhoja_ruta)');
$stmt2->execute([':idhoja_ruta' => $idhoja_ruta]);
$derivaciones = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$stmt2->closeCursor();

// Para selects
$correspondencias = ['interno', 'externo'];
$estados = ['en proceso','archivado','retrasado'];

// Llenar unidades/usuarios si quieres selects (ejemplo)
// $usuarios = $pdo->query("SELECT idusuario, descripcion FROM usuario")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Hoja de Ruta #<?= htmlspecialchars($hoja['idhoja_ruta'] ?? '') ?></title>
    <style>
        /* ... mismos estilos que en ver.php ... */
        body { font-family: Arial, sans-serif; background: #fcfcfc; }
        .container { max-width: 830px; margin: 32px auto; background: white; border:2px solid #333; box-shadow: 0 0 10px #ccc;padding:32px; }
        .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;}
        .header img { height:52px; }
        .title { text-align: center; font-weight: bold; font-size: 16pt; padding: 8px 0; margin-bottom:12px;}
        .section-title { background: #e0e0e0; font-weight: bold; font-size: 11pt; padding: 3px 6px; border-bottom: 1px solid #ccc; margin-top:9px;}
        .datagrid {width:100%;border-collapse:collapse;}
        .datagrid td, .datagrid th {border: 1px solid #333; font-size: 10pt; padding: 4px 6px;vertical-align:top;}
        .blocklabel {font-weight:bold;display:block;margin-bottom:2px;}
        .firma-box {border-top:1px solid #333; text-align:center;font-size:10pt;padding:4px 0;margin-top:12px;}
        input, select, textarea { width: 97%; font-size:10pt; padding:3px 6px; margin-top:2px;}
        textarea { min-height:36px; }
        .no-print { text-align:center;margin-top:24px; }
        @media print { .no-print { display:none; } body { padding:0;margin:0; } .container {box-shadow:none;} }
    </style>
</head>
<body>
<?php include '../components/menu.php'; ?>
<form method="POST" class="container">
    <div class="header">
        <img src="../../assets/icons/logos/logo_sedepos_color.png" alt="Bolivia">
        <img src="../../assets/icons/logos/gobernacion.png" alt="Gobernación">
        <img src="../../assets/icons/logos/cochabamba.png" alt="Cochabamba">
    </div>
    <div class="title">EDITAR HOJA DE RUTA ÚNICA DE CORRESPONDENCIA</div>

    <!-- PRIMERA PARTE -->
    <div class="section-title">PRIMERA PARTE</div>
    <table class="datagrid">
        <tr>
            <td><span class="blocklabel">Tipo Correspondencia:</span>
                <select name="tipo_correspondencia">
                    <?php foreach($correspondencias as $c): ?>
                    <option value="<?= $c ?>" <?= ($hoja['tipo_correspondencia']==$c)?'selected':'' ?>><?= ucfirst($c) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><span class="blocklabel">N° Registro Correlativo:</span>
                <input name="nro_registro_correlativo" value="<?= htmlspecialchars($hoja['nro_registro_correlativo'] ?? '') ?>">
            </td>
            <td><span class="blocklabel">Estado:</span>
                <select name="estado">
                    <?php foreach($estados as $e): ?>
                    <option value="<?= $e ?>" <?= ($hoja['estado']==$e)?'selected':'' ?>><?= ucfirst($e) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><span class="blocklabel">Cantidad hojas/anexos:</span>
                <input name="cant_hojas_anexos" value="<?= htmlspecialchars($hoja['cant_hojas_anexos'] ?? '') ?>">
            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="blocklabel">Emisión/Recepción:</span>
                <input type="datetime-local" name="emision_recepcion" value="<?= isset($hoja['emision_recepcion']) ? date('Y-m-d\TH:i', strtotime($hoja['emision_recepcion'])) : '' ?>">
            </td>
            <td colspan="2"><span class="blocklabel">Entrega:</span>
                <input type="datetime-local" name="entrega" value="<?= isset($hoja['entrega']) ? date('Y-m-d\TH:i', strtotime($hoja['entrega'])) : '' ?>">
            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="blocklabel">Salida:</span>
                <input type="datetime-local" name="salida" value="<?= isset($hoja['salida']) ? date('Y-m-d\TH:i', strtotime($hoja['salida'])) : '' ?>">
            </td>
            <td colspan="2"><span class="blocklabel">Referencia:</span>
                <textarea name="referencia"><?= htmlspecialchars($hoja['referencia'] ?? '') ?></textarea>
            </td>
        </tr>
    </table>

    <!-- DATOS DE ORIGEN Solo de la Primera Derivación -->
    <?php if ($derivaciones): $primera = $derivaciones[0]; ?>
    <div class="section-title">DATOS DE ORIGEN (PRIMERA DERIVACIÓN)</div>
    <table class="datagrid">
        <tr>
            <td><span class="blocklabel">Remitente (ID):</span>
                <input name="derivacion[<?= $primera['idderivaciones'] ?>][remitente]" value="<?= htmlspecialchars($primera['remitente'] ?? '') ?>">
            </td>
            <td><span class="blocklabel">Destinatario (ID):</span>
                <input name="derivacion[<?= $primera['idderivaciones'] ?>][destinatario]" value="<?= htmlspecialchars($primera['destinatario'] ?? '') ?>">
            </td>
            <td><span class="blocklabel">N° Registro Interno:</span>
                <input name="derivacion[<?= $primera['idderivaciones'] ?>][nro_registro_interno]" value="<?= htmlspecialchars($primera['nro_registro_interno'] ?? '') ?>">
            </td>
        </tr>
        <tr>
            <td colspan="3"><span class="blocklabel">Instrucción/Proveído:</span>
                <textarea name="derivacion[<?= $primera['idderivaciones'] ?>][instructivo_proveido]"><?= htmlspecialchars($primera['instructivo_proveido'] ?? '') ?></textarea>
            </td>
        </tr>
        <tr>
            <td><span class="blocklabel">Ingreso:</span>
                <input type="datetime-local" name="derivacion[<?= $primera['idderivaciones'] ?>][ingreso]" value="<?= isset($primera['ingreso']) ? date('Y-m-d\TH:i', strtotime($primera['ingreso'])) : '' ?>">
            </td>
            <td><span class="blocklabel">Salida:</span>
                <input type="datetime-local" name="derivacion[<?= $primera['idderivaciones'] ?>][salida]" value="<?= isset($primera['salida']) ? date('Y-m-d\TH:i', strtotime($primera['salida'])) : '' ?>">
            </td>
            <td class="firma-box">FIRMA Y SELLO</td>
        </tr>
    </table>
    <?php endif; ?>

    <!-- Siguientes Derivaciones -->
    <?php if (count($derivaciones) > 1): ?>
        <?php for ($i=1; $i<count($derivaciones); $i++): $d = $derivaciones[$i]; ?>
        <div class="section-title">DERIVACIÓN #<?= ($i+1) ?></div>
        <table class="datagrid">
            <tr>
                <td><span class="blocklabel">Remitente (ID):</span>
                    <input name="derivacion[<?= $d['idderivaciones'] ?>][remitente]" value="<?= htmlspecialchars($d['remitente'] ?? '') ?>">
                </td>
                <td><span class="blocklabel">Destinatario (ID):</span>
                    <input name="derivacion[<?= $d['idderivaciones'] ?>][destinatario]" value="<?= htmlspecialchars($d['destinatario'] ?? '') ?>">
                </td>
                <td><span class="blocklabel">N° Registro Interno:</span>
                    <input name="derivacion[<?= $d['idderivaciones'] ?>][nro_registro_interno]" value="<?= htmlspecialchars($d['nro_registro_interno'] ?? '') ?>">
                </td>
            </tr>
            <tr>
                <td colspan="3"><span class="blocklabel">Instrucción/Proveído:</span>
                    <textarea name="derivacion[<?= $d['idderivaciones'] ?>][instructivo_proveido]"><?= htmlspecialchars($d['instructivo_proveido'] ?? '') ?></textarea>
                </td>
            </tr>
            <tr>
                <td><span class="blocklabel">Ingreso:</span>
                    <input type="datetime-local" name="derivacion[<?= $d['idderivaciones'] ?>][ingreso]" value="<?= isset($d['ingreso']) ? date('Y-m-d\TH:i', strtotime($d['ingreso'])) : '' ?>">
                </td>
                <td><span class="blocklabel">Salida:</span>
                    <input type="datetime-local" name="derivacion[<?= $d['idderivaciones'] ?>][salida]" value="<?= isset($d['salida']) ? date('Y-m-d\TH:i', strtotime($d['salida'])) : '' ?>">
                </td>
                <td class="firma-box">FIRMA Y SELLO</td>
            </tr>
        </table>
        <?php endfor; ?>
    <?php endif; ?>

    <div class="no-print">
        <button type="submit" style="padding: 10px 24px; background:#1d4ed8;color:white;border-radius:4px;border:none;font-size:12pt;cursor:pointer;margin-right:10px;">Guardar</button>
        <a href="ver.php?id=<?= $hoja['idhoja_ruta'] ?>" style="padding: 10px 24px; background: #f44336; color: white; border-radius: 4px; border:none;font-size:12pt;text-decoration:none;">Cancelar</a>
    </div>
</form>
</body>
</html>