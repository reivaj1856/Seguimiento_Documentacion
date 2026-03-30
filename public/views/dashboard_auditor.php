<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SEDEPOS | Auditor</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include '../views/layout/menu.php'; ?>
    <div class="dashboard-auditor">
        <h2>Panel Auditor</h2>
        <div class="stats-boxes">
            <div class="stat-box"><strong>Pendientes:</strong> <?= $pendientes ?></div>
            <div class="stat-box"><strong>En proceso:</strong> <?= $enproceso ?></div>
            <div class="stat-box"><strong>Observados:</strong> <?= $observados ?></div>
            <div class="stat-box"><strong>Archivados:</strong> <?= $archivados ?></div>
        </div>
        <div class="dashboard-actions">
            <a href="index.php?r=reportes" class="btn">Ver Reportes</a>
            <a href="index.php?r=documentos" class="btn">Ver Documentos</a>
        </div>
        <hr>
        <h3>Últimos documentos gestionados</h3>
        <div class="last-docs">
            <?php foreach($ultimos_documentos as $doc): ?>
                <div><?= htmlspecialchars($doc['nro_cite']) ?> - <?= htmlspecialchars($doc['estado']) ?> (<?= htmlspecialchars($doc['fecha_recepcion']) ?>)</div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>