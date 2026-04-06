<?php
session_start();
require_once '../../config/db.php';
?>

<body class="bg-gray-50">
    
<?php include '../components/menu.php'; ?>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Botón Volver -->

        <!-- Header -->
        <div class="bg-blue-950 text-white rounded-lg p-5 mb-10 text-center">
            <h1 class="text-4xl font-bold mb-2"><i class="fas fa-question-circle mr-3"></i>Centro de Ayuda</h1>
            <p class="text-lg">Conoce cómo utilizar cada sección de tu aplicación SEDEPOS</p>
        </div>

        <!-- SECCIÓN 1: INICIO / DASHBOARD -->
        <div class="bg-white border-l-4 border-indigo-600 rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-indigo-600 flex items-center mb-6">
                <i class="fas fa-home text-3xl mr-4"></i> 1. INICIO (Dashboard)
            </h2>
            
            <p class="text-gray-700 leading-relaxed mb-6">La sección de <strong>Inicio</strong> es tu panel principal donde puedes visualizar un resumen general de tus documentos y acceder a un tutorial completo de la aplicación.</p>

            <h3 class="text-xl font-semibold text-purple-700 mt-6 mb-4">¿Qué puedes ver en Inicio?</h3>
            <ul class="text-gray-700 space-y-2 ml-4">
                <li class="list-disc"><strong>Documentos en Proceso:</strong> Muestra la cantidad total de documentos que actualmente están en trámite y pendientes de completar.</li>
                <li class="list-disc"><strong>Documentos Archivados:</strong> Indica cuántos documentos han sido completados y archivados exitosamente.</li>
                <li class="list-disc"><strong>Documentos Retrasados:</strong> Muestra los documentos cuyo plazo de entrega ha vencido o está próximo a vencer.</li>
                <li class="list-disc"><strong>Video Tutorial:</strong> Un video instructivo que explica paso a paso cómo usar toda la aplicación.</li>
            </ul>

            <h3 class="text-xl font-semibold text-purple-700 mt-6 mb-4">¿Cómo usar esta sección?</h3>
            <div class="space-y-3">
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 1:</strong> Al ingresar a la aplicación, automáticamente verás el dashboard con los números actualizados de tus documentos.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 2:</strong> Observa los indicadores de documentos en proceso, archivados y retrasados para tener un control rápido de tu situación.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 3:</strong> Si es tu primera vez o necesitas recordar cómo usar la aplicación, mira el video tutorial disponible en esta sección.
                </div>
            </div>

            <div class="bg-cyan-50 border-l-4 border-cyan-500 p-4 rounded mt-6">
                <strong class="text-cyan-700">💡 Consejo:</strong> <span class="text-gray-700">Revisa regularmente los documentos retrasados para no perder plazos importantes. Los números actualizan en tiempo real.</span>
            </div>
        </div>

        <!-- SECCIÓN 2: BANDEJA DE ENTRADA -->
        <div class="bg-white border-l-4 border-indigo-600 rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-indigo-600 flex items-center mb-6">
                <i class="fas fa-inbox text-3xl mr-4"></i> 2. BANDEJA DE ENTRADA
            </h2>
            
            <p class="text-gray-700 leading-relaxed mb-6">La <strong>Bandeja de Entrada</strong> es donde recibes todos los comentarios y observaciones asignados a ti sobre los documentos que están en proceso. Aquí puedes revisar el feedback de otros usuarios.</p>

            <h3 class="text-xl font-semibold text-purple-700 mt-6 mb-4">¿Qué puedes hacer en Bandeja de Entrada?</h3>
            <ul class="text-gray-700 space-y-2 ml-4">
                <li class="list-disc">Ver todos los comentarios nuevos y anteriores asignados a ti</li>
                <li class="list-disc">Revisar en qué documentos se han dejado comentarios</li>
                <li class="list-disc">Tomar acciones basado en el feedback recibido</li>
                <li class="list-disc">Marcar comentarios como leídos</li>
            </ul>

            <h3 class="text-xl font-semibold text-purple-700 mt-6 mb-4">¿Cómo usar esta sección?</h3>
            <div class="space-y-3">
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 1:</strong> Dirígete a la sección "Bandeja de Entrada" en el menú principal.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 2:</strong> Verás una lista de todos los comentarios asignados a ti de documentos en proceso.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 3:</strong> Haz clic en cualquier comentario para ver los detalles completos y el documento asociado.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 4:</strong> Lee el feedback y realiza los cambios necesarios en el documento correspondiente.
                </div>
            </div>

            <div class="bg-cyan-50 border-l-4 border-cyan-500 p-4 rounded mt-6">
                <strong class="text-cyan-700">💡 Consejo:</strong> <span class="text-gray-700">Revisa tu bandeja de entrada diariamente para no perderte feedback importante de otros usuarios.</span>
            </div>
        </div>

        <!-- SECCIÓN 3: GENERAR HOJA DE RUTA -->
        <div class="bg-white border-l-4 border-indigo-600 rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-indigo-600 flex items-center mb-6">
                <i class="fas fa-file-alt text-3xl mr-4"></i> 3. GENERAR HOJA DE RUTA (Registrar Documentos)
            </h2>
            
            <p class="text-gray-700 leading-relaxed mb-6">La sección <strong>Generar Hoja de Ruta</strong> es donde registras y creas nuevas hojas de ruta para los documentos que ingresan al sistema. Este es el punto de partida para cualquier nuevo documento.</p>

            <h3 class="text-xl font-semibold text-purple-700 mt-6 mb-4">Campos Principales (Requeridos)</h3>
            <p class="text-gray-700 mb-4">Para crear una hoja de ruta, es <strong>obligatorio</strong> llenar los siguientes campos:</p>
            <ul class="text-gray-700 space-y-2 ml-4">
                <li class="list-disc"><strong>Remitente:</strong> Quién envía o inicia el documento</li>
                <li class="list-disc"><strong>Asignado a:</strong> El usuario responsable de procesar el documento</li>
                <li class="list-disc"><strong>Plazo:</strong> Fecha límite para completar el documento</li>
                <li class="list-disc"><strong>Día de Ingreso:</strong> Fecha en que se registra el documento en el sistema</li>
            </ul>

            <h3 class="text-xl font-semibold text-purple-700 mt-6 mb-4">Campos Opcionales</h3>
            <p class="text-gray-700 mb-4">Puedes dejar sin llenar otros campos menos críticos. El sistema te permitirá omitir estos si así lo deseas.</p>

            <h3 class="text-xl font-semibold text-purple-700 mt-6 mb-4">¿Cómo crear una nueva Hoja de Ruta?</h3>
            <div class="space-y-3">
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 1:</strong> Dirígete a "Generar Hoja de Ruta" en el menú principal.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 2:</strong> Completa los campos obligatorios: Remitente, Asignado a, Plazo y Día de Ingreso.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 3:</strong> (Opcional) Llena otros campos adicionales si los necesitas para más detalle.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 4:</strong> Haz clic en "Guardar" o "Crear Hoja de Ruta" para registrar el documento.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 5:</strong> El sistema te asignará un número único de hoja de ruta que podrás usar para seguimiento.
                </div>
            </div>

            <div class="bg-cyan-50 border-l-4 border-cyan-500 p-4 rounded mt-6">
                <strong class="text-cyan-700">💡 Consejo:</strong> <span class="text-gray-700">Asegúrate de asignar la hoja de ruta a la persona correcta. Los plazos son importantes para el control de documentos retrasados.</span>
            </div>
        </div>

        <!-- SECCIÓN 4: ARCHIVADOS -->
        <div class="bg-white border-l-4 border-indigo-600 rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-indigo-600 flex items-center mb-6">
                <i class="fas fa-archive text-3xl mr-4"></i> 4. ARCHIVADOS
            </h2>
            
            <p class="text-gray-700 leading-relaxed mb-6">La sección <strong>Archivados</strong> te permite consultar todos los documentos que has completado y archivado. Es un histórico de tu trabajo completado.</p>

            <h3 class="text-xl font-semibold text-purple-700 mt-6 mb-4">¿Qué puedes hacer en esta sección?</h3>
            <ul class="text-gray-700 space-y-2 ml-4">
                <li class="list-disc">Ver todos tus documentos archivados</li>
                <li class="list-disc">Buscar documentos específicos archivados</li>
                <li class="list-disc">Revisar información completa de documentos ya procesados</li>
                <li class="list-disc">Verificar fechas de finalización</li>
            </ul>

            <h3 class="text-xl font-semibold text-purple-700 mt-6 mb-4">¿Cómo consultar Documentos Archivados?</h3>
            <div class="space-y-3">
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 1:</strong> Dirígete a la sección "Archivados" en el menú principal.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 2:</strong> Verás una lista de todos los documentos que has archivado.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 3:</strong> Usa el buscador (si está disponible) para encontrar un documento específico por número de hoja de ruta o tema.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 4:</strong> Haz clic en cualquier documento para ver sus detalles completos.
                </div>
            </div>

            <div class="bg-cyan-50 border-l-4 border-cyan-500 p-4 rounded mt-6">
                <strong class="text-cyan-700">💡 Consejo:</strong> <span class="text-gray-700">Los documentos archivados no pueden ser modificados. Si necesitas hacer cambios, consulta con un administrador.</span>
            </div>
        </div>

        <!-- SECCIÓN 5: SEGUIMIENTO -->
        <div class="bg-white border-l-4 border-indigo-600 rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-indigo-600 flex items-center mb-6">
                <i class="fas fa-search text-3xl mr-4"></i> 5. SEGUIMIENTO
            </h2>
            
            <p class="text-gray-700 leading-relaxed mb-6">La sección <strong>Seguimiento</strong> te permite buscar y monitorear el estado de cualquier documento usando su número único de hoja de ruta. Es ideal para verificar el progreso sin necesidad de imprimir.</p>

            <h3 class="text-xl font-semibold text-purple-700 mt-6 mb-4">¿Qué puedes hacer en Seguimiento?</h3>
            <ul class="text-gray-700 space-y-2 ml-4">
                <li class="list-disc">Buscar documentos por número de hoja de ruta</li>
                <li class="list-disc">Verificar el estado actual del documento</li>
                <li class="list-disc">Ver histórico de cambios</li>
                <li class="list-disc">Revisar asignaciones y comentarios</li>
            </ul>

            <h3 class="text-xl font-semibold text-purple-700 mt-6 mb-4">¿Cómo hacer Seguimiento a un Documento?</h3>
            <div class="space-y-3">
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 1:</strong> Dirígete a la sección "Seguimiento" en el menú principal.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 2:</strong> Ingresa el número de hoja de ruta del documento que deseas seguir en el campo de búsqueda.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 3:</strong> El sistema mostrará toda la información del documento, incluyendo su estado actual.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 4:</strong> Revisa el histórico para ver todos los cambios y comentarios del documento.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 5:</strong> Si necesitas más información, usa los filtros disponibles para refinar tu búsqueda.
                </div>
            </div>

            <div class="bg-cyan-50 border-l-4 border-cyan-500 p-4 rounded mt-6">
                <strong class="text-cyan-700">💡 Consejo:</strong> <span class="text-gray-700">Usa esta sección para monitorear documentos críticos y estar al tanto de su progreso en tiempo real.</span>
            </div>
        </div>

        <!-- SECCIÓN 6: REPORTES -->
        <div class="bg-white border-l-4 border-indigo-600 rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-indigo-600 flex items-center mb-6">
                <i class="fas fa-print text-3xl mr-4"></i> 6. REPORTES
            </h2>
            
            <p class="text-gray-700 leading-relaxed mb-6">La sección <strong>Reportes</strong> es similar a Seguimiento, pero con la funcionalidad adicional de poder generar e imprimir documentos. Es perfecta cuando necesitas documentación física o en formato PDF.</p>

            <h3 class="text-xl font-semibold text-purple-700 mt-6 mb-4">¿Qué puedes hacer en Reportes?</h3>
            <ul class="text-gray-700 space-y-2 ml-4">
                <li class="list-disc">Buscar documentos por número de hoja de ruta (igual que en Seguimiento)</li>
                <li class="list-disc">Ver toda la información del documento</li>
                <li class="list-disc"><strong>Imprimir el documento completo</strong></li>
                <li class="list-disc">Exportar a PDF</li>
                <li class="list-disc">Generar reportes en formato imprimible</li>
            </ul>

            <h3 class="text-xl font-semibold text-purple-700 mt-6 mb-4">¿Cómo generar un Reporte e Imprimir?</h3>
            <div class="space-y-3">
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 1:</strong> Dirígete a la sección "Reportes" en el menú principal.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 2:</strong> Ingresa el número de hoja de ruta del documento del que deseas generar reporte.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 3:</strong> El sistema mostrará la información del documento en formato de reporte.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 4:</strong> Haz clic en el botón "Imprimir" o "Exportar a PDF" según lo que necesites.
                </div>
                <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 rounded">
                    <strong class="text-indigo-600">Paso 5:</strong> Completa el proceso de impresión usando el diálogo estándar de tu navegador.
                </div>
            </div>

            <h3 class="text-xl font-semibold text-purple-700 mt-6 mb-4">Opciones de Impresión Disponibles</h3>
            <ul class="text-gray-700 space-y-2 ml-4">
                <li class="list-disc"><strong>Imprimir en papel:</strong> Envía directamente a tu impresora configurada</li>
                <li class="list-disc"><strong>Guardar como PDF:</strong> Crea un archivo PDF en tu computadora</li>
                <li class="list-disc"><strong>Enviar por correo:</strong> Comparte el reporte directamente por email</li>
            </ul>

            <div class="bg-cyan-50 border-l-4 border-cyan-500 p-4 rounded mt-6">
                <strong class="text-cyan-700">💡 Consejo:</strong> <span class="text-gray-700">Los reportes incluyen toda la información del documento más comentarios y histórico. Son útiles para auditorías y registros.</span>
            </div>
        </div>

        <!-- PREGUNTAS FRECUENTES -->
        <div class="bg-white border-l-4 border-yellow-500 rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-yellow-600 flex items-center mb-6">
                <i class="fas fa-comments text-3xl mr-4"></i> Preguntas Frecuentes
            </h2>
            
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-purple-700 mb-2">¿Cuál es la diferencia entre Seguimiento y Reportes?</h3>
                    <p class="text-gray-700"><strong>Seguimiento</strong> es para consultar el estado del documento en pantalla. <strong>Reportes</strong> hace lo mismo pero te permite imprimir o exportar a PDF para tener una copia física o digital.</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-purple-700 mb-2">¿Puedo modificar documentos archivados?</h3>
                    <p class="text-gray-700">No, los documentos archivados están bloqueados y no pueden ser modificados. Si necesitas cambios, contacta a un administrador.</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-purple-700 mb-2">¿Qué pasa si pierdo el número de hoja de ruta?</h3>
                    <p class="text-gray-700">Puedes buscar el documento en la sección de "Bandeja de Entrada" si aún está en proceso, o en "Archivados" si ya está completado. También puedes usar los filtros de búsqueda por remitente o asignado.</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-purple-700 mb-2">¿Es obligatorio llenar todos los campos?</h3>
                    <p class="text-gray-700">No, solo los campos marcados como obligatorios (Remitente, Asignado a, Plazo y Día de Ingreso) en la sección de Generar Hoja de Ruta son requeridos.</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-purple-700 mb-2">¿Cómo sé si hay comentarios nuevos en mis documentos?</h3>
                    <p class="text-gray-700">Los comentarios aparecen en tu "Bandeja de Entrada". Además, en el Dashboard (Inicio) verás indicadores de documentos con comentarios pendientes de revisión.</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-purple-700 mb-2">¿Puedo deshacer un archivo?</h3>
                    <p class="text-gray-700">Una vez archivado, un documento no puede ser desarchivado automáticamente. Si necesitas reabrir un documento, contacta al administrador del sistema.</p>
                </div>
            </div>
        </div>

        <!-- SOPORTE -->
        <div class="bg-white border-l-4 border-red-500 rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-red-600 flex items-center mb-6">
                <i class="fas fa-phone text-3xl mr-4"></i> ¿Necesitas Más Ayuda?
            </h2>
            
            <p class="text-gray-700 mb-4">Si aún tienes dudas o encuentras problemas usando la aplicación:</p>
            <ul class="text-gray-700 space-y-2 ml-4">
                <li class="list-disc"><strong>Contacta al administrador:</strong> Para problemas técnicos o acceso</li>
                <li class="list-disc"><strong>Revisa el tutorial en video:</strong> Disponible en la sección de Inicio</li>
                <li class="list-disc"><strong>Consulta con tu supervisor:</strong> Para duda sobre procedimientos específicos</li>
            </ul>
        </div>

        <!-- Botón Final -->
        <div class="text-center mb-8">
            <a href="../views/dashboard.php" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-lg font-semibold">
                <i class="fas fa-home mr-2"></i> Volver al Inicio
            </a>
        </div>
    </div>
</body>
</html>
