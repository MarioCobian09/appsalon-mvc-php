<?php

namespace Model;

class Cita extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'citas';
    protected static $columnasDB = ['cit_id', 'cit_fecha', 'cit_hora', 'cit_usu_id'];

    public $cit_id;
    public $cit_fecha;
    public $cit_hora;
    public $cit_usu_id;

    public function __construct($args = [])
    {
        $this->cit_id = $args['id'] ?? null;
        $this->cit_fecha = $args['fecha'] ?? '';
        $this->cit_hora = $args['hora'] ?? '';
        $this->cit_usu_id = $args['usuarioId'] ?? '';
    }

}