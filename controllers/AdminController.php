<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController {
    public static function index(Router $router) {

        isAdmin();

        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechas = explode('-', $fecha);

        if( !checkdate($fechas[1], $fechas[2], $fechas[0]) ) {
            header('Location: /404');
        }


        // Consultar la base de datos
        $consulta = "SELECT cit_id, cit_hora, CONCAT(usu_nombre, ' ', usu_apellido) as cliente, ";
        $consulta .= " usu_email, usu_telefono, ser_nombre as servicio, ser_precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " on citas.cit_usu_id = usuarios.usu_id  ";
        $consulta .= " LEFT OUTER JOIN citasservicios ";
        $consulta .= " ON citasservicios.citser_cit_id = citas.cit_id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.ser_id = citasservicios.citser_ser_id ";
        $consulta .= " WHERE cit_fecha =  '${fecha}' ";

        $citas = AdminCita::SQL($consulta);

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}