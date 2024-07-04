<?php

namespace Model;

class AdminCita extends ActiveRecord {
    protected static $tabla = 'citasservicios';
    protected static $columnasDB = ['cit_id', 'cit_hora', 'cliente', 'usu_email', 'usu_telefono', 'servicio', 'ser_precio'];

    public $cit_id;
    public $cit_hora;
    public $cliente;
    public $usu_email;
    public $usu_telefono;
    public $servicio;
    public $ser_precio;

    public function __construct($args = [])
    {
        $this->cit_id = $args['id'] ?? null;
        $this->cit_hora = $args['hora'] ?? '';
        $this->cliente = $args['cliente'] ?? '';
        $this->usu_email = $args['email'] ?? '';
        $this->usu_telefono = $args['telefono'] ?? '';
        $this->servicio = $args['servicio'] ?? '';
        $this->ser_precio = $args['ser_precio'] ?? '';
    }
}