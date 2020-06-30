<?php

namespace App\Controllers;

use Config\DataBase as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Usuario;
use App\Utils\ValidadorJWT;
use App\Utils\Funciones;

class UsuarioController {

    public function getAll(Request $request, Response $response, $args)
    {
        $rta = json_encode(Usuario::all());

        $response->getBody()->write($rta);

        return $response;
    }

    public function add(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();

        $pass = password_hash($body["clave"], PASSWORD_DEFAULT);
        $usuario = new Usuario;
        $usuario->email = $body["email"];
        $usuario->tipo = $body["tipo"];
        $usuario->clave = $pass;
        $usuario->usuario = $body["usuario"];
        $rta = json_encode(array("ok" => $usuario->save()));

        $response->getBody()->write($rta);

        return $response;
    }


    public function login(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $email = $body["email"];
        $clave = $body['clave'];

         $usuarios = Funciones::TraerTodos();
         $usuario = Funciones::TraerUno($usuarios, $email);
         $valido = Funciones::ValidarLogin($usuario, $email, $clave);

        // $usuario = Usuario::where('email', $email)->get();
        // var_dump($usuario->attributes['clave']);
        if($valido)
        {
             $token = ValidadorJWT::CrearToken($usuario);
             $rta = json_encode($token);

        }
        else
        {
            $rta = json_encode("Error, usuario o clave no coinciden");
        }

        $response->getBody()->write($rta);

        return $response;
    }
}