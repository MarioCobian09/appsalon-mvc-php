<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {

        isAuthReady();

        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                $usuario = Usuario::where('usu_email', $auth->usu_email);

                if($usuario) {
                    if($usuario->comprobarPasswordAndVerificado($auth->usu_password)) {
                        session_start();

                        $_SESSION['id'] = $usuario->usu_id;
                        $_SESSION['nombre'] = $usuario->usu_nombre . " " . $usuario->usu_apellido;
                        $_SESSION['email'] = $usuario->usu_email;
                        $_SESSION['login'] = True;

                        // Redireccionamiento

                        if($usuario->usu_admin === '1') {
                            $_SESSION['admin'] = $usuario->usu_admin ?? null;

                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout() {

        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)) {
                $usuario = Usuario::where('usu_email', $auth->usu_email);
                
                if($usuario && $usuario->usu_confirmado === "1") {

                    // Generacion del token
                    $usuario->crearToken();
                    $usuario->guardar('usu_id');

                    // Enviar el email
                    $email = new Email($usuario->usu_email, $usuario->usu_nombre, $usuario->usu_token);
                    $email->enviarInstrucciones();

                    Usuario::setAlerta('exito', 'Revisa tu email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        $alertas = [];
        $error = False;

        $token = s($_GET['token']) ?? null;

        if(!$token) {
            header('Location: /');
        }

        // Buscar usuario por su token
        $usuario = Usuario::where('usu_token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido');
            $error = True;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)) {
                $usuario->usu_password = null;
                $usuario->usu_password = $password->usu_password;
                $usuario->hashPassword();
                $usuario->usu_token = null;

                $resultado = $usuario->guardar('usu_id');

                if($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {

        isAuthReady();

        $usuario = new Usuario();

        // Alertas vacias
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)) {
                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();         // Retorna el resultado de la consulta, en donde si existe, agrega una alerta

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas(); // Como ya se agrego la alerta en caso de existir resultados, solo volvemos a tomar los arrores
                } else {
                    // Hashear el password
                    $usuario->hashPassword();

                    // Generar un token unico
                    $usuario->crearToken();                    

                    // Enviar el email
                    $email = new Email($usuario->usu_email, $usuario->usu_nombre, $usuario->usu_token);
                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->guardar('usu_id');
                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) { 

        $alertas = [];
        $token = s($_GET['token']) ?? null;

        if(!$token) {
            header('Location: /');
        }
        
        $usuario = Usuario::where('usu_token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            $usuario->usu_confirmado = '1';
            $usuario->usu_token = '';

            $usuario->guardar('usu_id');

            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}