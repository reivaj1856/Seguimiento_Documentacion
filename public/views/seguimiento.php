<?php
$sql = "SELECT hr.*, d.* FROM hoja_ruta hr
    JOIN derivaciones d ON hr.idhoja_ruta = d.hoja_ruta_idhoja_ruta
    WHERE hr.estado='en proceso' AND (d.remitente=? OR d.destinatario=?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$userid, $userid]);
$docs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="text-xl font-bold mb-4">Seguimiento</div>
<table class="min-w-full bg-white border rounded">
    <thead>
        <tr>
            <th class="p-2 border">Hoja de Ruta</th>
            <th class="p-2 border">Estado</th>
            <th class="p-2 border">Ver</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($docs as $doc): ?>
        <tr>
            <td class="border px-2"><?= htmlspecialchars($doc['idhoja_ruta']) ?></td>
            <td class="border px-2"><?= htmlspecialchars($doc['estado']) ?></td>
            <td class="border px-2">
                <a href="ver_documento.php?id=<?= $doc['idhoja_ruta'] ?>" class="text-blue-600">Ver</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>