<?php
session_start();
require_once '../../config/db.php';

$user_rol = $_SESSION['rol'] ?? '';
$user = $_SESSION['usuario'] ?? [];

// Usa el procedimiento almacenado para los contadores principales
$stmt = $pdo->prepare("CALL sp_contar_documentos_usuario(:idUsuario)");
$stmt->execute([':idUsuario' => $user['idusuario']]);
$counts = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();
// Documentos retrasados del usuario (puedes adaptar la consulta a tu estructura real)
$stmt2 = $pdo->prepare("
    SELECT hr.idhoja_ruta, hr.referencia, hr.nro_registro_correlativo, hr.tipo_correspondencia, hr.emision_recepcion
    FROM hoja_ruta hr
    JOIN derivaciones d ON d.idhoja_ruta = hr.idhoja_ruta
    WHERE hr.estado = 'retrasado'
      AND (d.destinatario = :idUsuario OR d.remitente = :idUsuario)
    GROUP BY hr.idhoja_ruta
    ORDER BY hr.emision_recepcion DESC
");
$stmt2->execute([':idUsuario' => $user['idusuario']]);
$retrasados_usuario = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SEDEPOS | Dashboard Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <?php include '../components/menu.php'; ?>

  <div id="nuevoSistemaModal" class="hidden fixed inset-0 z-50 bg-black/55 backdrop-blur-[1px] transition-opacity duration-500">
    <div class="min-h-full w-full flex items-center justify-center p-4">
      <div class="w-full max-w-2xl bg-white border border-gray-200 shadow-2xl rounded-sm overflow-hidden">
        <div class="bg-blue-900 text-white px-5 py-3">
          <h2 class="text-lg font-semibold">Nuevo sistema emergente</h2>
          <p class="text-sm text-blue-100">Resolucion de problemas y criterios de evaluacion de desempeno</p>
        </div>
        <div class="p-5 space-y-4">
          <p class="text-sm text-gray-700">
            Desde ahora, el sistema cuenta con una atencion emergente para reportar incidencias, priorizar bloqueos y dar seguimiento a soluciones con trazabilidad.
          </p>

          <div>
            <h3 class="text-sm font-semibold text-gray-800 mb-2">Criterios de evaluacion de desempeno</h3>
            <ul class="list-disc pl-5 text-sm text-gray-700 space-y-1">
              <li>Tiempo de respuesta ante incidencias.</li>
              <li>Calidad de la derivacion y claridad del proveido.</li>
              <li>Cumplimiento de plazos de atencion y cierre.</li>
              <li>Trazabilidad completa de cada hoja de ruta.</li>
            </ul>
          </div>

          <div class="flex flex-wrap gap-2 justify-end">
            <button type="button" id="btnVerInfoSedepos" class="px-4 py-2 text-sm font-medium border border-blue-200 text-blue-800 bg-blue-50 hover:bg-blue-100 rounded-sm transition">
              Ver informacion SEDEPOS
            </button>
            <button type="button" id="btnCerrarModalSistema" class="px-8 py-3 text-base font-semibold bg-blue-900 text-white hover:bg-blue-950 rounded-sm transition shadow-sm">
              Entendido
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <main class="max-w-7xl mx-auto p-4">
    <!-- Notificación al iniciar sesión (mantengo tu contenido) -->
    <section id="loginNotif" class="mb-4">
      <?php if (!empty($retrasados_usuario)): ?>
        <div class="rounded-xl overflow-hidden shadow-sm border border-rose-200">
          <div class="bg-gradient-to-r from-rose-600 to-red-500 text-white p-4 flex items-start justify-between gap-4">
            <div class="flex items-start gap-3">
              <div class="text-2xl">⚠️</div>
              <div>
                <h3 class="text-lg font-semibold">Tienes <?= number_format($counts['retrasado'] ?? 0) ?> documento(s) retrasado(s)</h3>
                <p class="text-rose-100 text-sm">Revisa el vencimiento y considera observar, reasignar o responder.</p>
              </div>
            </div>
            <button type="button"
                    onclick="document.getElementById('loginNotif').classList.add('hidden')"
                  class="px-3 py-1 rounded-sm bg-white text-rose-700 hover:bg-rose-50 text-sm border border-rose-200">
              Cerrar
            </button>
          </div>
        </div>
      <?php else: ?>
        <div class="rounded-xl overflow-hidden shadow-sm border border-emerald-200">
          <div class="bg-gradient-to-r from-emerald-600 to-teal-500 text-white p-4 flex items-start justify-between gap-4">
            <div class="flex items-start gap-3">
              <div class="text-2xl">✅</div>
              <div>
                <h3 class="text-lg font-semibold">Bienvenido, <?= htmlspecialchars($user['descripcion'] ?? '') ?></h3>
                <p class="text-emerald-100 text-sm">No tienes documentos retrasados. ¡Buen trabajo!</p>
              </div>
            </div>
            <button type="button"
                    onclick="document.getElementById('loginNotif').classList.add('hidden')"
                  class="px-3 py-1 rounded-sm bg-white text-emerald-700 hover:bg-emerald-50 text-sm border border-emerald-200">
              Cerrar
            </button>
          </div>
        </div>
      <?php endif; ?>
    </section>

        <section id="infoSedepos" class="mt-4">
      <div class="p-5">
        <h2 class="text-lg font-semibold text-gray-900">SEDEPOS en breve</h2>
        <p class="text-sm text-gray-700 mt-2">
          SEDEPOS centraliza la gestion documental institucional para registrar, derivar y controlar el avance de hojas de ruta con transparencia y tiempos medibles.
        </p>
        <div class="grid md:grid-cols-3 gap-4 mt-4">
          <div class="border border-blue-100 bg-blue-50 rounded-sm p-3">
            <h3 class="text-sm font-semibold text-blue-900">Trazabilidad</h3>
            <p class="text-xs text-blue-800 mt-1">Seguimiento completo desde el ingreso hasta el archivo o cierre.</p>
          </div>
          <div class="border border-emerald-100 bg-emerald-50 rounded-sm p-3">
            <h3 class="text-sm font-semibold text-emerald-900">Control oportuno</h3>
            <p class="text-xs text-emerald-800 mt-1">Alertas de retraso para actuar a tiempo y mejorar cumplimiento.</p>
          </div>
          <div class="border border-amber-100 bg-amber-50 rounded-sm p-3">
            <h3 class="text-sm font-semibold text-amber-900">Desempeno</h3>
            <p class="text-xs text-amber-800 mt-1">Indicadores para evaluar calidad, tiempos y eficiencia operativa.</p>
          </div>
        </div>
      </div>
    </section>

    <section>
      <div class="flex flex-wrap">
      <!-- En proceso -->
      <div class="w-full md:w-1/2 xl:w-1/3 p-6">
        <div class=" rounded-sm shadow-sm hover:shadow-md transition-shadow duration-200 p-5">
          <div class="flex flex-row items-center">
            <div class="flex-shrink pr-4">
              <div class="h-11 w-11 flex items-center justify-center bg-slate-100 border border-slate-200 rounded-sm">
                <img class="h-6 w-6 opacity-80" src="../../assets/icons/ingenieria.png" alt="En proceso">
              </div>
            </div>
            <div class="flex-1 text-right md:text-center">
              <h2 class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">En proceso</h2>
              <p class="font-semibold text-3xl text-slate-800"><?= number_format($counts['en_proceso'] ?? 0) ?></p>
            </div>
          </div>
          <div class="mt-4 h-[2px] w-full bg-blue-700/70"></div>
        </div>
      </div>
      <!-- Retrasados -->
      <div class="w-full md:w-1/2 xl:w-1/3 p-6">
        <div class="bg-white rounded-sm shadow-sm hover:shadow-md transition-shadow duration-200 p-5">
          <div class="flex flex-row items-center">
            <div class="flex-shrink pr-4">
              <div class="h-11 w-11 flex items-center justify-center ">
                <img class="h-6 w-6 opacity-80" src="../../assets/icons/caducado.png" alt="Retrasados">
              </div>
            </div>
            <div class="flex-1 text-right md:text-center">
              <h2 class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">Retrasados</h2>
              <p class="font-semibold text-3xl text-slate-800"><?= number_format($counts['retrasado'] ?? 0) ?></p>
            </div>
          </div>
          <div class="mt-4 h-[2px] w-full bg-rose-700/70"></div>
        </div>
      </div>
      <!-- Archivados -->
      <div class="w-full md:w-1/2 xl:w-1/3 p-6">
        <div class="shadow-sm hover:shadow-md transition-shadow duration-200 p-5">
          <div class="flex flex-row items-center">
            <div class="flex-shrink pr-4">
              <div class="h-11 w-11 flex items-center justify-center ">
                <img class="h-6 w-6 opacity-80" src="../../assets/icons/archivados.png" alt="Archivados">
              </div>
            </div>
            <div class="flex-1 text-right md:text-center">
              <h2 class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">Archivados</h2>
              <p class="font-semibold text-3xl text-slate-800"><?= number_format($counts['archivado'] ?? 0) ?></p>
            </div>
          </div>
          <div class="mt-4 h-[2px] w-full bg-slate-700/80"></div>
        </div>
      </div>
    </div>
    </section>

    <div class="max-w-screen-xl mx-auto mt-4">
      <div class="bg-blue-950 rounded-lg overflow-hidden w-full">
        <div class="grid md:grid-cols-2 items-center">
          <div class="p-8">
            <h2 class="sm:text-4xl text-2xl font-semibold text-white leading-tight">
              Aprender a <span class="text-orange-400">Navegar</span>
            </h2>
            <p class="mt-6 text-sm text-slate-300 leading-relaxed">
              Aqui te muestro un video explicativo de como navegar por el sistema, para que puedas sacarle el mayor provecho a todas las funcionalidades que ofrecemos.
            </p>
            <p class="mt-2 text-sm text-slate-300 leading-relaxed">
              Si tienes alguna duda, no dudes en contactar a soporte técnico o revisar la documentación disponible. Estamos aquí para ayudarte a aprovechar al máximo nuestro sistema de gestión de documentos.
            </p>
          </div>
          <iframe class="w-full h-96"
                  src="https://www.youtube.com/embed/R5EXap3vNDA?si=4XhznX_EheA4AjJ_"
                  title="YouTube video player" frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                  referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </main>
  

  <!-- Auto-ocultar notificación a los 5 segundos con suave desvanecimiento -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const modal = document.getElementById('nuevoSistemaModal');
      const btnCerrarModal = document.getElementById('btnCerrarModalSistema');
      const btnVerInfo = document.getElementById('btnVerInfoSedepos');
      const infoSedepos = document.getElementById('infoSedepos');

      const cerrarModalConDesvanecimiento = () => {
        if (!modal || modal.classList.contains('hidden')) {
          return;
        }

        modal.classList.add('opacity-0');
        setTimeout(() => {
          modal.classList.add('hidden');
          modal.classList.remove('opacity-0');
        }, 500);
      };

      if (modal) {
        modal.classList.remove('hidden');

        // Cierra automaticamente luego de 10 segundos
        setTimeout(() => {
          cerrarModalConDesvanecimiento();
        }, 10000);
      }

      if (btnCerrarModal && modal) {
        btnCerrarModal.addEventListener('click', () => {
          cerrarModalConDesvanecimiento();
        });
      }

      if (btnVerInfo && infoSedepos && modal) {
        btnVerInfo.addEventListener('click', () => {
          cerrarModalConDesvanecimiento();
          infoSedepos.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
      }

      // Cierra el modal al presionar Enter
      document.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && modal && !modal.classList.contains('hidden')) {
          cerrarModalConDesvanecimiento();
        }
      });

      const notif = document.getElementById('loginNotif');
      if (!notif) return;

      // Asegura transición suave
      notif.classList.add('transition-opacity', 'duration-500');

      // Oculta a los 5 segundos
      const hideAfter = 5000; // ms
      setTimeout(() => {
        notif.classList.add('opacity-0');
        // Tras la transición, oculta del flujo
        setTimeout(() => notif.classList.add('hidden'), 500);
      }, hideAfter);
    });
  </script>
</body>
</html>