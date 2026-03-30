<?php
session_start();
require_once '../../config/db.php';

// Obtener listado de usuarios
$stmt = $pdo->query("SELECT u.*, un.nombre_area 
                     FROM usuario u 
                     LEFT JOIN Unidad un ON u.id_unidad = un.idUnidad");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Usuarios</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    
<?php include '../components/menu.php'; ?>
<div class="p-6 mx-auto max-w-7xl">
  <div class="mb-6">
    <h2 class="text-2xl font-normal text-blue-900 mb-4">Gestión de Usuarios</h2>
    <a class="bg-blue-900 hover:bg-blue-950 text-white font-normal px-6 py-2 rounded-sm inline-flex items-center gap-2 transition-colors" 
       href="../actions/agregar_usuario.php">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
      </svg>
      Nuevo Usuario
    </a>
  </div>

  <div class="bg-white rounded-sm shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full">
      <thead class="bg-blue-900 text-white">
        <tr>
          <th class="px-4 py-3 text-left text-sm font-normal">ID</th>
          <th class="px-4 py-3 text-left text-sm font-normal">Usuario</th>
          <th class="px-4 py-3 text-left text-sm font-normal">Nombre/Descripción</th>
          <th class="px-4 py-3 text-left text-sm font-normal">Cargo</th>
          <th class="px-4 py-3 text-left text-sm font-normal">Unidad</th>
          <th class="px-4 py-3 text-left text-sm font-normal">Estado</th>
          <th class="px-4 py-3 text-left text-sm font-normal">Rol</th>
          <th class="px-4 py-3 text-center text-sm font-normal">Acciones</th>
        </tr>
      </thead>
      <tbody class="text-gray-700">
        <?php $i = 0; foreach($usuarios as $u): $i++; ?>
        <tr class="<?= $i % 2 == 0 ? 'bg-gray-50' : 'bg-white' ?> hover:bg-gray-100 transition-colors">
          <td class="px-4 py-3 text-sm font-normal"><?= $u['idusuario'] ?></td>
          <td class="px-4 py-3 text-sm font-normal"><?= htmlspecialchars($u['usuario']) ?></td>
          <td class="px-4 py-3 text-sm font-normal"><?= htmlspecialchars($u['descripcion']) ?></td>
          <td class="px-4 py-3 text-sm font-normal"><?= htmlspecialchars($u['cargo']) ?></td>
          <td class="px-4 py-3 text-sm font-normal"><?= htmlspecialchars($u['nombre_area']) ?></td>
          <td class="px-4 py-3 text-sm font-normal">
            <span class="px-2 py-1 text-xs rounded-sm <?= $u['estado'] == 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
              <?= htmlspecialchars($u['estado']) ?>
            </span>
          </td>
          <td class="px-4 py-3 text-sm font-normal">
            <span class="px-2 py-1 text-xs rounded-sm bg-blue-100 text-blue-900">
              <?= htmlspecialchars($u['rol']) ?>
            </span>
          </td>
          <td class="px-4 py-3">
            <div class="flex gap-2 justify-center">
              <a href="../actions/editar_usuario.php?id=<?= $u['idusuario'] ?>" 
                 class="inline-flex items-center px-3 py-1.5 text-sm font-normal border border-yellow-500 text-yellow-700 rounded-sm hover:bg-yellow-50 transition-colors"
                 title="Editar">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
              </a>
              <button type="button"
                      onclick="eliminarUsuario(<?= $u['idusuario'] ?>, '<?= htmlspecialchars($u['usuario'], ENT_QUOTES) ?>')"
                      class="inline-flex items-center px-3 py-1.5 text-sm font-normal border border-red-500 text-red-700 rounded-sm hover:bg-red-50 transition-colors"
                      title="Eliminar">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Eliminar
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function eliminarUsuario(id, usuario) {
  if (confirm('¿Está seguro de que desea eliminar el usuario "' + usuario + '"?')) {
    window.location.href = '../actions/eliminar_usuario.php?id=' + id;
  }
}
</script>
</body>
</html>