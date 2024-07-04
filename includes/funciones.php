<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// funcion que revisa que el usuario este autenticado
function isAuth() : void {
    if(!isset($_SESSION['login'])) {
        header('Location: /');
    }
}

// funcion que revisa que el usuario este autenticado
function isAuthReady() : void {
    if(isset($_SESSION['login'])) {
        if(isset($_SESSION['admin'])) {
            header('Location: /admin');
        } else {
            header('Location: /cita');
        }
    }
}
// funcion que revisa que el usuario es admin
function isAdmin() : void {
    if(!isset($_SESSION['admin'])) {
        header('Location: /');
    }
}

function esUltimo(string $actual, string $proximo): bool {
    if($actual !== $proximo) {
        return True;
    }

    return False;
}