<?php

require_once '../../config/db.php';

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


  <main class="max-w-7xl mx-auto p-4">
    
  <h1 class="text-black">Bienvenido al Dashboard</h1>
        
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