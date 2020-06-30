<?php

namespace App\Utils;

use App\Models\Usuario;
use Config\DataBae;

class Funciones
{
    public static function TraerUno($usuarios, $dato)
    {
        foreach ($usuarios as $usuarioAux) 
        {
            if($usuarioAux['email'] == $dato)
            {
                $usuario = $usuarioAux;
            }
            else
            {
               $usuario = '';
            }
        }
        return $usuario;
    }

    public static function ExisteUser($usuarios, $dato)
    {
        $existe = false;
        foreach ($usuarios as $usuario) 
        {
            if($usuario['email'] == $dato)
            {
                $existe = true;
            }
        }
        return $existe;
    }

    public static function TraerTodos()
    {
        $usuarios = Usuario::all();

        return $usuarios;
    }

    public static function ValidarLogin($usuario, $email, $clave)
    {
        $rta = false;

        if($usuario['email'] == $email && password_verify($clave, $usuario->clave))
        {
            $rta = true;
        }

        return $rta;
    }

}