<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet">
    
    <!-- Al tener mas rutas con otro nivel de profundidad, añadimos el "/" de la ruta para que busque desde la raiz del proyecto y no busque de la subrama que creamos /servicios -->
    <link rel="stylesheet" href="/build/css/app.css">
</head>
<body>

    <div class="contenedor-app">
        <div class="imagen"></div>
        <div class="app">
        <?php echo $contenido; ?>
        </div>
    </div>

    <?php 
        echo $script ?? '';
    ?>
            
</body>
</html>