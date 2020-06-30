<?php

namespace App\Controllers;

use Config\DataBase as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Turno;
use App\Models\Usuario;
use App\Models\Mascota;
use App\Utils\ValidadorJWT;
use App\Utils\Funciones;
use DateTime;
use Symfony\Component\Console\Helper\Helper;

class TurnoController {

    public function getAllTurnos(Request $request, Response $response, $args)
    {
        $rta = json_encode(Turno::all());

        // $response->getBody()->write("Controller");
        $response->getBody()->write($rta);

        return $response;
    }

    public function addTurno(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        
        if(TurnoController::ValidarTurno($body["fecha-hora"], $body["veterinario_id"]))
        {
            if(TurnoController::TipoUsuario($body["id_veterinario"]) == 1)
            {
                $turno = new Turno;
                $turno->id_mascota = $body["mascota_id"];
                $turno->fecha = $body["fecha-hora"];
                $turno->id_veterinario = $body["veterinario_id"];
                $rta = json_encode(array("ok" => $turno->save()));
            }
            else
            {
                $rta = json_encode("Id No pertenece a Veterinario");
            }
        }
        else
        {
            $rta = json_encode("Turno No Disponible");
        }

        $response->getBody()->write($rta);

        return $response;
    }

    public function id_usuario(Request $request, Response $response, $args)
    {
       $id_usuario = $args['id'];
       $veterinario = TurnoController::TipoUsuario($id_usuario);
       
        if($veterinario)
        {
            $turnos = Turno::where('id_veterinario', $id_usuario)
            ->join('mascotas', 'mascotas.id', 'turnos.id_mascota')
            ->join('usuario', 'usuario.id', 'mascotas.id_cliente')
            //$turnos = Turno::join('mascotas', 'mascota.id', 'turnos.id_mascota')
            ->get();
            $rta = json_encode($turnos);
        }
        else
        {
            $mascotas = Mascota::where('id_cliente', '=', $id_usuario)
            ->join('usuario', 'usuario.id', 'mascotas.id_cliente')
            ->get();

            //$turnos = Turno::join('turnos', '=', 'turnos.id_mascota')->get();
            //$turnos = Turno::where('id_veterinario', $id_usuario)->get();
            $rta = json_encode($mascotas);
        }

        $response->getBody()->write($rta);

        return $response;
    }

    public static function ValidarTurno($fecHr, $id_veterinario)
    {
        $hrMin = array('horaMin'=> '09:00:00');
        $hrMax =  array('horaMax'=> '17:00:00');
        
        //$formatDate = array('fecha'=> $fecha, 'hora'=>$hora);
        $hora = $fecHr['hora'];
        $fecha = $fecHr['fecha'];
        if($hrMin >= $hora && $hora < $hrMax)
        {
            $turnos = Turno::all();

            foreach ($turnos as $turno) 
            {
                //$turnoTime = array('hora'=>$turno->hora);
                if($turno->fecha['fecha'] == $fecha && $turno->fecha['hora'] == $hora && $turno->veterinario_id == $id_veterinario)
                {
                    return false;
                }
            }
        }

        return true;
    }

    public static function TipoUsuario($id_veterinario)
    {
        $tipo = 0;
        // 0 Cliente
        // 1 Veterinario
        // 2 Admin
        $user = Usuario::find($id_veterinario);
        //var_dump($user);
        if($user['tipo']  == 1)
        {
            $tipo = 1;
        }
        else if($user['tipo'] == 2)
        {
            $tipo = 2;
        }
        return $tipo;
    }

}