<?php
// Listar unidades
$stmt = $conn->query("SELECT * FROM Unidad");
$unidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="flex justify-between mb-4">
    <h2 class="text-xl font-bold">Unidades</h2>
    <a href="crear_unidad.php" class="bg-green-600 text-white px-4 py-2 rounded">Crear Unidad</a>
</div>
<table class="min-w-full bg-white border rounded">
    <thead>
        <tr>
            <th class="p-2 border">ID</th>
            <th class="p-2 border">Nombre Área</th>
            <th class="p-2 border">Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($unidades as $unidad): ?>
        <tr>
            <td class="border px-2"><?= htmlspecialchars($unidad['idUnidad']) ?></td>
            <td class="border px-2"><?= htmlspecialchars($unidad['nombre_area']) ?></td>
            <td class="border px-2">
                <a href="editar_unidad.php?id=<?= $unidad['idUnidad'] ?>" class="text-yellow-600">Editar</a>
                <a href="eliminar_unidad.php?id=<?= $unidad['idUnidad'] ?>" class="text-red-600 ml-2">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>