<?php

namespace Model;

class Usuario extends ActiveRecord {
    // Base de datos

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['usu_id', 'usu_nombre', 'usu_apellido', 'usu_email', 'usu_password', 'usu_telefono', 'usu_token', 'usu_confirmado', 'usu_admin'];

    public $usu_id;
    public $usu_nombre;
    public $usu_apellido;
    public $usu_email;
    public $usu_password;
    public $usu_telefono;
    public $usu_admin;
    public $usu_token;
    public $usu_confirmado;

    public function __construct($args = []) {
        $this->usu_id = $args['id'] ?? null;
        $this->usu_nombre = $args['nombre'] ?? '';
        $this->usu_apellido = $args['apellido'] ?? '';
        $this->usu_email = $args['email'] ?? '';
        $this->usu_password = $args['password'] ?? '';
        $this->usu_telefono = $args['telefono'] ?? '';
        $this->usu_admin = $args['admin'] ?? '0';
        $this->usu_token = $args['token'] ?? '';
        $this->usu_confirmado = $args['confirmado'] ?? '0';
    }

    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta() {
        if(!$this->usu_nombre) {
            self::$alertas['error'][] = 'El nombre es Obligatorio';
        }
        if(!$this->usu_apellido) {
            self::$alertas['error'][] = 'El apellido es Obligatorio';
        }
        if(!$this->usu_email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        if(!$this->usu_password) {
            self::$alertas['error'][] = 'El password es Obligatorio';
        }
        if(strlen($this->usu_password) < 6) {
            self::$alertas['error'][] = 'El password debe contener 6 caracteres';
        }

        return self::$alertas;
    }

    // Mensajes de validación para la creación de una cuenta
    public function validarLogin() {
        if(!$this->usu_email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        if(!$this->usu_password) {
            self::$alertas['error'][] = 'El password es Obligatorio';
        }

        return self::$alertas;
    }

    public function validarEmail() {
        if(!$this->usu_email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }

        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->usu_password) {
            self::$alertas['error'][] = 'El password es Obligatorio';
        }
        if(strlen($this->usu_password) < 6) {
            self::$alertas['error'][] = 'El password debe contener 6 caracteres';
        }

        return self::$alertas;
    }

    // Revisa si el usuario ya existe
    public function existeUsuario() {
        $query = " SELECT * FROM " . self::$tabla . " WHERE usu_email = '" . $this->usu_email . "' LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas['error'][] = 'El Usuario ya esta registrado';
        }

        return $resultado;
    }

    public function hashPassword() {
        $this->usu_password = password_hash($this->usu_password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->usu_token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->usu_password);

        if(!$this->usu_confirmado) {
            self::$alertas['error'][] = 'El usuario no esta confirmado';
            return False;
        } else {
            if(!$resultado) {
                self::$alertas['error'][] = 'El password no es correcto';
                return False;
            } else {
                return True;
            }
        }
    }
}