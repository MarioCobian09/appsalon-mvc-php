<?php

namespace Model;

class Servicio extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['ser_id', 'ser_nombre', 'ser_precio'];

    public $ser_id;
    public $ser_nombre;
    public $ser_precio;

    public function __construct($args = [])
    {
        $this->ser_id = $args['id'] ?? null;
        $this->ser_nombre = $args['nombre'] ?? null;
        $this->ser_precio = $args['precio'] ?? null;
    }

    public function validar()
    {
        if(!$this->ser_nombre) {
            self::$alertas['error'][] = 'El nombre del servicio es obligatorio';
        }
        if(!$this->ser_precio) {
            self::$alertas['error'][] = 'El precio del servicio es obligatorio';
        }
        if(!is_numeric($this->ser_precio)) {
            self::$alertas['error'][] = 'El precio no tiene un formato valido';
        }

        return self::$alertas;
    }
}