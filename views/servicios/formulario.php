<div class="campo">
    <label for="nombre">Nombre</label>
    <input 
        type="text" 
        name="ser_nombre" 
        id="nombre"
        placeholder="Nombre del Servicio"
        value="<?php echo $servicio->ser_nombre ?>"
    >
</div>

<div class="campo">
    <label for="nombre">Precio</label>
    <input 
        type="number" 
        name="ser_precio" 
        id="precio"
        placeholder="Precio servicio"
        value="<?php echo $servicio->ser_precio ?>"
    >
</div>