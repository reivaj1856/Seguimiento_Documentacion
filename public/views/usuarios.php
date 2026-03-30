<?php
// Listar usuarios
$stmt = $conn->query("SELECT u.*, un.nombre_area FROM usuario u LEFT JOIN Unidad un ON u.id_unidad = un.idUnidad");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="flex justify-between mb-4">
    <h2 class="text-xl font-bold">Usuarios</h2>
    <a href="crear_usuario.php" class="bg-green-600 text-white px-4 py-2 rounded">Crear Usuario</a>
</div>
<table class="min-w-full bg-white border rounded">
    <thead>
        <tr>
            <th class="p-2 border">ID</th>
            <th class="p-2 border">Usuario</th>
            <th class="p-2 border">Descripción</th>
            <th class="p-2 border">Cargo</th>
            <th class="p-2 border">Unidad</th>
            <th class="p-2 border">Estado</th>
            <th class="p-2 border">Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($usuarios as $user): ?>
        <tr>
            <td class="border px-2"><?= htmlspecialchars($user['idusuario']) ?></td>
            <td class="border px-2"><?= htmlspecialchars($user['usuario']) ?></td>
            <td class="border px-2"><?= htmlspecialchars($user['descripcion']) ?></td>
            <td class="border px-2"><?= htmlspecialchars($user['cargo']) ?></td>
            <td class="border px-2"><?= htmlspecialchars($user['nombre_area']) ?></td>
            <td class="border px-2"><?= htmlspecialchars($user['estado']) ?></td>
            <td class="border px-2">
                <a href="editar_usuario.php?id=<?= $user['idusuario'] ?>" class="text-yellow-600">Editar</a>
                <a href="eliminar_usuario.php?id=<?= $user['idusuario'] ?>" class="text-red-600 ml-2">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>