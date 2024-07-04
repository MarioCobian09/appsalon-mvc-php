<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Administración de Servicios</p>

<?php
    include_once __DIR__ . '/../templates/barra.php'
?>

<ul class="servicios">
    <?php foreach($servicios as $servicio): ?>

        <li>
            <p>Nombre: <Span><?php echo $servicio->ser_nombre; ?></Span></p>
            <p>Precio: <Span>$<?php echo $servicio->ser_precio; ?></Span></p>

            <div class="acciones">
                <a href="/servicios/actualizar?id=<?php echo $servicio->ser_id ?>" class="boton">Actualizar</a>

                <form action="/servicios/eliminar" method="post">
                    <input type="hidden" name="id" value="<?php echo $servicio->ser_id ?>">
                    <input type="submit" value="Eliminar" class="boton-eliminar">
                </form>
            </div>
        </li>

    <?php endforeach; ?>
</ul>