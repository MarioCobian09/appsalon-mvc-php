<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {
    public static function index() {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar() {

        // Almacena la cita
        $cita = new Cita($_POST);
        $resultado = $cita->guardar('cit_id'); // Retorna un true o false y el id del nuevo registro

        // Almacena la CitaServicio
        $idCita = $resultado['id'];
        $idServicios = explode(',', $_POST['servicios']);

        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $idCita,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar('citser_id');
        }

        $respuesta = [
            'resultado' => $resultado
        ];

        echo json_encode($respuesta);
    }

    public static function eliminar() {
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $cita = Cita::find('cit_id', $id);
            $cita->eliminar($id);

            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}