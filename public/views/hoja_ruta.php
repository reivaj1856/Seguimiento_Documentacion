<?php
if ($_SERVER['REQUEST_METHOD']==='POST') {
    // Procesa la creación, llama el procedimiento sp_insertar_hoja_ruta con los datos del form.
    // Recomiendo usar PDO y prepared statements para seguridad
    // ...
    echo '<div class="bg-green-100 p-2 rounded mb-4 text-green-800">Hoja de ruta creada correctamente</div>';
}
?>
<form method="POST" class="space-y-2">
    <!-- Crea inputs/fields para cada parámetro del procedimiento,
         puedes completar con selects, inputs, por ejemplo: -->
    <input type="text" name="referencia" class="border p-2 rounded w-full" placeholder="Referencia">
    <input type="number" name="cant_hojas_anexos" class="border p-2 rounded w-full" placeholder="Cantidad hojas anexos">
    <!-- ...replicar todos campos necesarios... -->
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Crear</button>
</form>