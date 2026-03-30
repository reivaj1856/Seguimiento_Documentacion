<?php
session_start();
require_once '../../config/db.php';

// Listado de unidades
$stmt = $pdo->query("SELECT * FROM Unidad");
$unidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Muestra cantidad de usuarios por unidad
$usuario_count = [];
$ucount_stmt = $pdo->query("SELECT id_unidad, COUNT(*) as cant FROM usuario GROUP BY id_unidad");
foreach ($ucount_stmt as $row) $usuario_count[$row['id_unidad']] = $row['cant'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Unidades</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    tr:hover { background: #dbeafe; }
    .icon-btn img { width:24px; height:24px; margin:0 2px; vertical-align:middle; }
    .icon-btn { display:inline-block; }
  </style>
</head>
<body class="bg-gray-50">
  
<?php include '../components/menu.php'; ?>
<div class="max-w-7xl mx-auto font-normal rounded-sm shadow-md p-7 mt-10">
  <h2 class="text-xl mb-6 text-gray-800 font-normal">Gestión de Unidades</h2>
  <a href="../actions/agregar_unidad.php" class="inline-flex items-center rounded-sm bg-blue-900 text-white px-3 py-2 mb-5 text-sm hover:bg-blue-700 font-normal">
    Nueva Unidad
  </a>
  <table class="min-w-full mb-8 font-normal rounded-sm text-sm ">
    <thead>
      <tr class="bg-blue-900 text-white hover:bg-blue-900">
        <th class="px-2 py-2 font-normal">ID</th>
        <th class="px-2 py-2 font-normal">Área/Nombre</th>
        <th class="px-2 py-2 font-normal">Cant. Usuarios</th>
        <th class="px-2 py-2 font-normal">Acciones</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($unidades as $u): ?>
      <tr class="border-b">
        <td class="px-2 py-2"><?= $u['idUnidad'] ?></td>
        <td class="px-2 py-2"><?= htmlspecialchars($u['nombre_area']) ?></td>
        <td class="px-2 py-2 text-center">
          <a href="usuarios_unidad.php?id=<?= $u['idUnidad'] ?>" class="hover:underline text-blue-700 font-normal"><?= isset($usuario_count[$u['idUnidad']]) ? $usuario_count[$u['idUnidad']] : 0 ?></a>
        </td>
        <td class="px-2 py-2 text-center">
          <a href="../actions/editar_unidad.php?id=<?= $u['idUnidad'] ?>" 
                 class="inline-flex items-center px-3 py-1.5 text-sm font-normal border border-yellow-500 text-yellow-700 rounded-sm hover:bg-yellow-50 transition-colors"
                 title="Editar">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
              </a>
          <a href="../actions/eliminar_unidad.php?id=<?= $u['idUnidad'] ?>" 
                 class="inline-flex items-center px-3 py-1.5 text-sm font-normal border border-red-500 text-red-700 rounded-sm hover:bg-red-50 transition-colors"
                 title="Eliminar"
                 onclick="return confirm('¿Eliminar la unidad <?= htmlspecialchars($u['nombre_area']) ?>?');">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Eliminar
              </a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</body>
</html>