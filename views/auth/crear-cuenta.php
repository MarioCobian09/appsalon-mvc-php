<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php"
?>

<form class="formulario" method="POST" action="/crear-cuenta">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input 
            type="text"
            id="nombre"
            name="usu_nombre"
            placeholder="Tu Nombre"
            value="<?php echo s($usuario->usu_nombre) ?>"
        />
    </div>
    <div class="campo">
        <label for="apellido">Apellido</label>
        <input 
            type="text"
            id="apellido"
            name="usu_apellido"
            placeholder="Tu Apellido"
            value="<?php echo s($usuario->usu_apellido) ?>"
        />
    </div>
    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input 
            type="tel"
            id="telefono"
            name="usu_telefono"
            placeholder="Tu Teléfono"
            value="<?php echo s($usuario->usu_telefono) ?>"
        />
    </div>
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            name="usu_email"
            placeholder="Tu Email"
            value="<?php echo s($usuario->usu_email) ?>"
        />
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            name="usu_password"
            placeholder="Tu Password"
        />
    </div>

    <input type="submit" value="Crear Cuenta" class="boton"/>
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/olvide">¿Olvidaste tu Password?</a>
</div>