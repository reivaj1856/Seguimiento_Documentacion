<?php
require_once '../../config/db.php'; // tu conexión inicializa $pdo

$error = '';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($login && $password) {
        // Buscar solo por usuario, la contraseña se compara en PHP con password_verify
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE usuario=? AND estado='activo' LIMIT 1");
        $stmt->execute([$login]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['contrasena'])) {
            // No guardar la contraseña en la sesión
            unset($usuario['contrasena']);
            $_SESSION['usuario'] = $usuario;

            // Asegúrate que el campo 'rol' existe en tu tabla usuario
            if(isset($usuario['rol']) && $usuario['rol'] == 'administrador') {
                header("Location: ../views/dashboard_admin.php");
            } elseif(isset($usuario['rol']) && $usuario['rol'] == 'auditor') {
                header("Location: ../views/dashboard_auditor.php");
            } else {
                header("Location: ../views/dashboard.php");
            }
            exit;
        } else {
            $error = "Usuario o contraseña incorrecta, o usuario inactivo.";
        }
    } else {
        $error = "Por favor ingresa usuario y contraseña.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Gestión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="bg-gradient-to-r from-blue-900 via-sky-800 to-sky-600">
      <div class="min-h-screen flex flex-col items-center justify-center p-6">
        <div class="grid md:grid-cols-2 items-center gap-10 max-w-6xl w-full">
          <div class="max-w-lg max-md:mx-auto max-md:text-center">
            <a href="javascript:void(0)">
              <img src="../../assets/icons/logos/logo_sedepos.png" alt="logo" class="lg:w-max w-auto inline-block"/>
            </a>
            <h1 class="text-4xl font-semibold !leading-tight text-white">
              Sistema de Seguimiento Hoja de Ruta
            </h1>
            <p class="text-[15px] mt-6 text-white leading-relaxed">Este sitio está en fase de testeo no es un producto terminado.</p>
          </div>

          <form method="POST" class="bg-white rounded-xl px-8 py-12 max-w-md md:ml-auto max-md:mx-auto w-full">
            <h2 class="text-slate-900 text-3xl font-bold mb-12">
              Bienvenidos
            </h2>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
            <?php endif; ?>
            <div class="space-y-4">
              <div>
                <input name="usuario" type="text" autocomplete="username" required class="bg-gray-100 focus:bg-transparent w-full text-sm px-4 py-3 rounded-md outline-gray-800" placeholder="Usuario" />
              </div>
              <div>
                <input name="password" type="password" autocomplete="current-password" required class="bg-gray-100 focus:bg-transparent w-full text-sm px-4 py-3 rounded-md outline-gray-800" placeholder="Contraseña" />
              </div>
            </div>
            <div class="mt-12">
              <button type="submit" class="w-full shadow-xl py-2 px-6 text-[15px] font-medium rounded-md text-white bg-slate-800 hover:bg-slate-900 focus:outline-0 cursor-pointer">
                Iniciar sesión
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
</body>
</html>