<?php
session_start();
$idhoja_ruta = isset($_GET['id']) ? intval($_GET['id']) : 0;
require_once '../../config/db.php';

// Hoja de ruta principal
$stmt = $pdo->prepare('CALL sp_get_hoja_ruta(:idhoja_ruta)');
$stmt->execute([':idhoja_ruta' => $idhoja_ruta]);
$hoja = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// Todas las derivaciones
$stmt2 = $pdo->prepare('CALL sp_get_derivaciones_hoja_ruta(:idhoja_ruta)');
$stmt2->execute([':idhoja_ruta' => $idhoja_ruta]);
$derivaciones = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$stmt2->closeCursor();

// Primera derivación para los datos de origen
$primera = $derivaciones[0] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hoja de Ruta #<?= htmlspecialchars($hoja['idhoja_ruta'] ?? '') ?></title>
    <style>
        /* ... estilos igual que antes ... */
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
        @media print { .no-print { display:none; } body { padding:0;margin:0; } .container {box-shadow:none;} }
    </style>
</head>
<body>
<?php include '../components/menu.php'; ?>
<div class="container">
    <div class="header">
        <img src="../../assets/icons/logos/logo_sedepos_color.png" alt="Bolivia">
        <img src="../../assets/icons/logos/gobernacion.png" alt="Gobernación">
        <img src="../../assets/icons/logos/cochabamba.png" alt="Cochabamba">
    </div>
    <div class="title">HOJA DE RUTA ÚNICA DE CORRESPONDENCIA</div>

    <!-- PRIMERA PARTE -->
    <div class="section-title">PRIMERA PARTE</div>
    <table class="datagrid">
        <tr>
            <td><span class="blocklabel">Tipo Correspondencia:</span>
                <?= htmlspecialchars($hoja['tipo_correspondencia'] ?? '') ?>
            </td>
            <td><span class="blocklabel">N° Registro Correlativo:</span>
                <?= htmlspecialchars($hoja['nro_registro_correlativo'] ?? '') ?>
            </td>
            <td><span class="blocklabel">Estado:</span>
                <?= htmlspecialchars($hoja['estado'] ?? '') ?>
            </td>
            <td><span class="blocklabel">Cantidad hojas/anexos:</span>
                <?= htmlspecialchars($hoja['cant_hojas_anexos'] ?? '') ?>
            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="blocklabel">Emisión/Recepción:</span>
                <?= isset($hoja['emision_recepcion']) ? date('d/m/Y H:i', strtotime($hoja['emision_recepcion'])) : '' ?>
            </td>
            <td colspan="2"><span class="blocklabel">Entrega:</span>
                <?= isset($hoja['entrega']) ? date('d/m/Y H:i', strtotime($hoja['entrega'])) : '' ?>
            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="blocklabel">Salida:</span>
                <?= isset($hoja['salida']) ? date('d/m/Y H:i', strtotime($hoja['salida'])) : '' ?>
            </td>
            <td colspan="2"><span class="blocklabel">Referencia:</span>
                <?= htmlspecialchars($hoja['referencia'] ?? '') ?>
            </td>
        </tr>
    </table>

    <!-- DATOS DE ORIGEN Solo de la Primera Derivación -->
    <div class="section-title">DATOS DE ORIGEN</div>
    <?php if ($primera): ?>
    <table class="datagrid">
        <tr>
            <td><span class="blocklabel">Remitente (usuario):</span>
                <?= htmlspecialchars($primera['remitente_usuario'] ?? '') ?>
            </td>
            <td><span class="blocklabel">Remitente (descripción):</span>
                <?= htmlspecialchars($primera['remitente_descripcion'] ?? '') ?>
            </td>
            <td><span class="blocklabel">Remitente (cargo):</span>
                <?= htmlspecialchars($primera['remitente_cargo'] ?? '') ?>
            </td>
        </tr>
        <tr>
            <td><span class="blocklabel">Destinatario (usuario):</span>
                <?= htmlspecialchars($primera['destinatario_usuario'] ?? '') ?>
            </td>
            <td><span class="blocklabel">Destinatario (descripción):</span>
                <?= htmlspecialchars($primera['destinatario_descripcion'] ?? '') ?>
            </td>
            <td><span class="blocklabel">Destinatario (cargo):</span>
                <?= htmlspecialchars($primera['destinatario_cargo'] ?? '') ?>
            </td>
        </tr>
        <tr>
            <td colspan="3"><span class="blocklabel">Instrucción/Proveído:</span>
                <?= nl2br(htmlspecialchars($primera['instructivo_proveido'] ?? '')) ?>
            </td>
        </tr>
        <tr>
            <td><span class="blocklabel">Ingreso:</span>
                <?= isset($primera['ingreso']) ? date('d/m/Y H:i', strtotime($primera['ingreso'])) : '' ?>
            </td>
            <td><span class="blocklabel">Salida:</span>
                <?= isset($primera['salida']) ? date('d/m/Y H:i', strtotime($primera['salida'])) : '' ?>
            </td>
            <td class="firma-box">FIRMA Y SELLO</td>
        </tr>
    </table>
    <?php endif; ?>

    <!-- DERIVACIONES: solo las siguientes -->
    <?php if (count($derivaciones) > 1): ?>
        <?php for ($i=1; $i<count($derivaciones); $i++): $d = $derivaciones[$i]; ?>
        <div class="section-title">DESTINATARIO #<?= ($i+1) ?></div>
        <table class="datagrid">
            <tr>
                <td><span class="blocklabel">Destinatario:</span>
                    <?= htmlspecialchars($d['destinatario_descripcion'] ?? '') ?>
                    <?php if (!empty($d['destinatario_cargo'])): ?>
                        - <?= htmlspecialchars($d['destinatario_cargo']) ?>
                    <?php endif; ?>
                </td>
                <td><span class="blocklabel">Remitente:</span>
                    <?= htmlspecialchars($d['remitente_descripcion'] ?? '') ?>
                    <?php if (!empty($d['remitente_cargo'])): ?>
                        - <?= htmlspecialchars($d['remitente_cargo']) ?>
                    <?php endif; ?>
                </td>
                <td><span class="blocklabel">N° Registro Interno:</span>
                    <?= htmlspecialchars($d['nro_registro_interno'] ?? '') ?>
                </td>
            </tr>
            <tr>
                <td colspan="3"><span class="blocklabel">Instrucción / Proveído:</span>
                    <div style="min-height: 40px;">
                        <?= nl2br(htmlspecialchars($d['instructivo_proveido'] ?? '')) ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><span class="blocklabel">Ingreso:</span>
                    <?= isset($d['ingreso']) ? date('d/m/Y H:i', strtotime($d['ingreso'])) : '' ?>
                </td>
                <td><span class="blocklabel">Salida:</span>
                    <?= isset($d['salida']) ? date('d/m/Y H:i', strtotime($d['salida'])) : '' ?>
                </td>
                <td class="firma-box">FIRMA Y SELLO</td>
            </tr>
        </table>
        <?php endfor; ?>
    <?php endif; ?>
</div>
<div class="no-print" style="text-align:center;margin:24px;">
    <button onclick="window.print()" style="padding: 10px 24px; background: #1569c7; color: white; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">
        Imprimir
    </button>
    <button onclick="window.location.href='../views/documentos.php'" style="padding: 10px 24px; background: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer;">
        Cerrar
    </button>
</div>
</body>
</html>