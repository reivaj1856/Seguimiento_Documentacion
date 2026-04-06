<?php
session_start();
require_once '../../config/db.php';
?>
<body class="bg-slate-50 text-slate-800">
    
<?php include '../components/menu.php'; ?>
	<div class="max-w-7xl mx-auto px-4 py-8">

		<section class="rounded-2xl bg-blue-900 text-white p-5 shadow-lg mb-8">
			<p class="text-sm uppercase tracking-[0.3em] text-blue-100 mb-3">SEDEPOS</p>
			<h1 class="text-4xl font-bold mb-4">Manual de Usuario</h1>
			<p class="text-lg max-w-3xl leading-relaxed">Guía práctica para usar la aplicación, consultar documentos, registrar hojas de ruta, revisar comentarios y generar reportes.</p>
		</section>

		<div class="grid gap-6 md:grid-cols-3 mb-8">
			<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
				<p class="text-sm font-semibold text-blue-700 mb-1">1. Inicio</p>
				<p class="text-sm text-slate-600">Panel de bienvenida con documentos en proceso, archivados, retrasados y video tutorial.</p>
			</div>
			<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
				<p class="text-sm font-semibold text-blue-700 mb-1">2. Gestión</p>
				<p class="text-sm text-slate-600">Bandeja de entrada, registro de hojas de ruta y consulta de archivados.</p>
			</div>
			<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
				<p class="text-sm font-semibold text-blue-700 mb-1">3. Consulta</p>
				<p class="text-sm text-slate-600">Seguimiento por número único y reportes con opción de impresión.</p>
			</div>
		</div>

		<section class="space-y-6">
			<article class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
				<h2 class="text-2xl font-bold text-blue-900 flex items-center mb-4"><i class="fas fa-home mr-3 text-blue-700"></i>1. Inicio</h2>
				<p class="leading-relaxed text-slate-700 mb-4">La ventana de inicio es el panel principal de la aplicación. Allí se muestra un resumen general del estado de tus documentos y sirve como punto de entrada para comprender tu actividad diaria.</p>
				<div class="grid gap-4 md:grid-cols-2">
					<div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
						<p class="font-semibold text-slate-900 mb-2">Información visible</p>
						<ul class="space-y-2 text-sm text-slate-700 list-disc pl-5">
							<li>Documentos en proceso</li>
							<li>Documentos archivados</li>
							<li>Documentos retrasados</li>
							<li>Video tutorial de uso</li>
						</ul>
					</div>
					<div class="rounded-xl bg-sky-50 p-4 border border-sky-200">
						<p class="font-semibold text-sky-900 mb-2">Uso recomendado</p>
						<p class="text-sm text-slate-700">Entra primero al inicio para revisar alertas, controlar vencimientos y ubicar rápidamente el estado general de tus documentos.</p>
					</div>
				</div>
			</article>

			<article class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
				<h2 class="text-2xl font-bold text-blue-900 flex items-center mb-4"><i class="fas fa-inbox mr-3 text-blue-700"></i>2. Bandeja de Entrada</h2>
				<p class="leading-relaxed text-slate-700 mb-4">La bandeja de entrada reúne los comentarios, observaciones y documentos asignados al usuario que aún están en proceso.</p>
				<ul class="space-y-2 text-sm text-slate-700 list-disc pl-5">
					<li>Revisa los comentarios asignados a tu usuario.</li>
					<li>Abre cada documento para ver el detalle del comentario.</li>
					<li>Usa esta sección para dar seguimiento a observaciones pendientes.</li>
				</ul>
			</article>

			<article class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
				<h2 class="text-2xl font-bold text-blue-900 flex items-center mb-4"><i class="fas fa-file-alt mr-3 text-blue-700"></i>3. Generar Hoja de Ruta</h2>
				<p class="leading-relaxed text-slate-700 mb-4">Esta opción permite registrar un nuevo documento y crear su hoja de ruta. Es el inicio formal del trámite dentro del sistema.</p>
				<div class="grid gap-4 md:grid-cols-2">
					<div class="rounded-xl bg-amber-50 p-4 border border-amber-200">
						<p class="font-semibold text-amber-900 mb-2">Campos obligatorios</p>
						<ul class="space-y-2 text-sm text-slate-700 list-disc pl-5">
							<li>Asignado</li>
							<li>Remitente</li>
							<li>Plazo</li>
							<li>Día de ingreso</li>
						</ul>
					</div>
					<div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200">
						<p class="font-semibold text-emerald-900 mb-2">Recomendación</p>
						<p class="text-sm text-slate-700">Puedes omitir algunos campos secundarios, pero conviene completar la información básica para facilitar el seguimiento posterior.</p>
					</div>
				</div>
			</article>

			<article class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
				<h2 class="text-2xl font-bold text-blue-900 flex items-center mb-4"><i class="fas fa-archive mr-3 text-blue-700"></i>4. Archivados</h2>
				<p class="leading-relaxed text-slate-700 mb-4">Aquí se consultan los documentos ya finalizados y archivados por el usuario. Sirve como historial de trabajo completado.</p>
				<ul class="space-y-2 text-sm text-slate-700 list-disc pl-5">
					<li>Ver documentos archivados del usuario.</li>
					<li>Buscar por número o referencia si la pantalla lo permite.</li>
					<li>Confirmar que un trámite ya fue concluido.</li>
				</ul>
			</article>

			<article class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
				<h2 class="text-2xl font-bold text-blue-900 flex items-center mb-4"><i class="fas fa-search mr-3 text-blue-700"></i>5. Seguimiento</h2>
				<p class="leading-relaxed text-slate-700 mb-4">La sección de seguimiento permite buscar un documento por su número único de hoja de ruta y revisar su estado actual.</p>
				<div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
					<p class="font-semibold text-slate-900 mb-2">Cómo usarlo</p>
					<ol class="space-y-2 text-sm text-slate-700 list-decimal pl-5">
						<li>Ingresa el número único de la hoja de ruta.</li>
						<li>Revisa el estado actual y el detalle del documento.</li>
						<li>Consulta los movimientos y observaciones asociadas.</li>
					</ol>
				</div>
			</article>

			<article class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
				<h2 class="text-2xl font-bold text-blue-900 flex items-center mb-4"><i class="fas fa-print mr-3 text-blue-700"></i>6. Reportes</h2>
				<p class="leading-relaxed text-slate-700 mb-4">Reportes funciona como seguimiento, pero añade la posibilidad de imprimir el documento o generar una salida lista para archivo.</p>
				<ul class="space-y-2 text-sm text-slate-700 list-disc pl-5">
					<li>Buscar por número único de hoja de ruta.</li>
					<li>Ver el mismo detalle que en seguimiento.</li>
					<li>Imprimir o exportar el documento cuando sea necesario.</li>
				</ul>
			</article>

			<article class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
				<h2 class="text-2xl font-bold text-blue-900 flex items-center mb-4"><i class="fas fa-layer-group mr-3 text-blue-700"></i>Flujo de trabajo recomendado</h2>
				<div class="grid gap-4 md:grid-cols-2">
					<div class="rounded-xl bg-indigo-50 p-4 border border-indigo-200">
						<p class="font-semibold text-indigo-900 mb-2">Paso 1</p>
						<p class="text-sm text-slate-700">Ingresar al inicio y revisar indicadores.</p>
					</div>
					<div class="rounded-xl bg-indigo-50 p-4 border border-indigo-200">
						<p class="font-semibold text-indigo-900 mb-2">Paso 2</p>
						<p class="text-sm text-slate-700">Atender comentarios en la bandeja de entrada.</p>
					</div>
					<div class="rounded-xl bg-indigo-50 p-4 border border-indigo-200">
						<p class="font-semibold text-indigo-900 mb-2">Paso 3</p>
						<p class="text-sm text-slate-700">Registrar nuevos documentos en hoja de ruta.</p>
					</div>
					<div class="rounded-xl bg-indigo-50 p-4 border border-indigo-200">
						<p class="font-semibold text-indigo-900 mb-2">Paso 4</p>
						<p class="text-sm text-slate-700">Buscar, verificar o imprimir en seguimiento y reportes.</p>
					</div>
				</div>
			</article>

			<article class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
				<h2 class="text-2xl font-bold text-blue-900 flex items-center mb-4"><i class="fas fa-circle-question mr-3 text-blue-700"></i>Preguntas frecuentes</h2>
				<div class="space-y-4 text-sm text-slate-700">
					<div>
						<p class="font-semibold text-slate-900">¿Cuál es la diferencia entre seguimiento y reportes?</p>
						<p>Seguimiento sirve para consultar el estado; reportes agrega impresión y exportación.</p>
					</div>
					<div>
						<p class="font-semibold text-slate-900">¿Puedo cambiar documentos ya archivados?</p>
						<p>Los archivados no deberían modificarse. Si hay un error, debe revisarlo un administrador.</p>
					</div>
					<div>
						<p class="font-semibold text-slate-900">¿Qué datos debo priorizar al registrar una hoja de ruta?</p>
						<p>Asignado, remitente, plazo y día de ingreso son los datos básicos para evitar inconsistencias.</p>
					</div>
				</div>
			</article>
		</section>

		<div class="text-center mt-10 mb-6">
			<a href="../views/dashboard.php" class="inline-flex items-center px-6 py-3 rounded-lg bg-blue-900 text-white font-semibold hover:bg-blue-950 transition">
				<i class="fas fa-home mr-2"></i> Volver al inicio
			</a>
		</div>
	</div>
</body>
</html>
