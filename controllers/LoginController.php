<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController {
    public static function login(Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if(!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                } else {
                    if( password_verify($_POST['password'], $usuario->password) ) {
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        header('Location: /dashboard');
                    } else {
                        Usuario::setAlerta('error', 'El password es incorrecto');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }
    public static function logout() {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }
    public static function crear(Router $router) {


        $usuario = new Usuario;
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);
            
                if($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya existe');
                    $alertas = Usuario::getAlertas();
                } else {
                    // hasehar password
                    $usuario->hashPassword();

                    // Eliminar password 2
                    unset($usuario->password2);

                    $usuario->crearToken();

                    $resultado = $usuario->guardar();

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion();

                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear', [
            'titulo' => 'Crea tu cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);

    }
    public static function olvide(Router $router) {
        
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {
                // Buscar el usuario
                $usuario = Usuario::where("email", $usuario->email);

                if($usuario && $usuario->confirmado) {
                    // Generar un nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);
                    
                    // Actualizar el usuario
                    $usuario->guardar();

                    // Enviar mail
                    $mail = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $mail->enviarInstrucciones();

                    // Imprimir alertas
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');

                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                }
            }            
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide', [
            'titulo' => 'Olvide Mi Password',
            'alertas' => $alertas
        ]);
    }
    public static function restablecer(Router $router) {

        $token = s($_GET['token']);
        $mostrar = true;
        $alertas = [];

        if(!$token) header('Location /');

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido');
            $mostrar = false;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Añadir nuevo password
            $usuario->sincronizar($_POST);

            // Validar password
            $usuario->validarPassword();

            if(empty($alertas)) {

                $usuario->hashPassword();
                unset($usuario->password2);
                $usuario->token = '';
                $resultado = $usuario->guardar();

                if($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/restablecer', [
            'titulo' => 'Restablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }
    public static function mensaje(Router $router) {
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);
    }
    public static function confirmar(Router $router) {

        $token = s($_GET['token']);

        if(!$token) header('Location /');

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            $usuario->setAlerta('error', 'Token No Válido');
        } else {
            $usuario->confirmado = 1;
            $usuario->token = '';
            unset($usuario->password2);

            $usuario->guardar();

            $usuario->setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar Cuenta',
            'alertas' => $alertas
        ]);
    }
}