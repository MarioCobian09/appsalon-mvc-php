<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController {
    public static function index(Router $router) {

        isAdmin();

        $servicios = Servicio::all();

        $router->render('/servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router) {

        isAdmin();

        $servicio = new Servicio;
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar('ser_id');
                header('Location: /servicios');
            }
        }

        $router->render('/servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router) {

        isAdmin();

        $alertas = [];

        if(is_numeric($_GET['id'])) {
            $servicio = Servicio::find('ser_id', $_GET['id']);

            if(!$servicio) {
                Servicio::setAlerta('error', 'ID no existe');
            }
        } else {
            $servicio = null;
            Servicio::setAlerta('error', 'ID no valido');
        }
        

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar('ser_id');
                header('Location: /servicios');
            }
        }

        $alertas = Servicio::getAlertas();

        $router->render('/servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar(Router $router) {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicio = Servicio::find('ser_id', $id);

            $servicio->eliminar($id);
            header('Location: /servicios');
        }
    }
}