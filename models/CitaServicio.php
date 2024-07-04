<?php

namespace Model;

class CitaServicio extends ActiveRecord {
    protected static $tabla = 'citasservicios';
    protected static $columnasDB = ['citser_id', 'citser_cit_id', 'citser_ser_id'];

    public $citser_id;
    public $citser_cit_id;
    public $citser_ser_id;

    public function __construct($args = [])
    {
        $this->citser_id = $args['id'] ?? null;
        $this->citser_cit_id = $args['citaId'] ?? null;
        $this->citser_ser_id = $args['servicioId'] ?? null;
    }
}