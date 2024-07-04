<h1 class="nombre-pagina">Actualizar Servicio</h1>
<p class="descripcion-pagina">Modifica los valores del formulario</p>

<?php
    include_once __DIR__ . '/../templates/barra.php';
    include_once __DIR__ . '/../templates/alertas.php';
?>

<?php   

    $errores = $alertas['error'] ?? null;
    
    if($errores) {
        if(!array_search('ID no valido', $errores) || !array_search('ID no existe', $errores)){
            return;
        };
    }
?>

<form method="POST" class="formulario">
    <?php
        include_once __DIR__ . '/formulario.php';
    ?>

    <input type="submit" value="Actualizar Servicio" class="boton">
</form>