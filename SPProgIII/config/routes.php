<?php

namespace Config;

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UsuarioController;
use App\Controllers\MascotaController;
use App\Controllers\Tipo_MascotaController;
use App\Controllers\TurnoController;
use App\Middleware\BeforeMiddleware;
use App\Middleware\UsuarioValidateMiddleware;
use App\Middleware\RegistroMiddleware;
use App\Middleware\ExisteUserMiddleware;
use App\Middleware\LoginMiddleware;
use App\Middleware\TurnoMiddleware;

return function ($app) {

    //PUNTO 1
    $app->post('/registro', UsuarioController::class . ':add')->add(ExisteUserMiddleware::class)->add(RegistroMiddleware::class);

    //PUNTO 2
    $app->post('/login', UsuarioController::class . ':login')->add(LoginMiddleware::class);
    
    //PUINTO 3
    $app->post('/tipo_mascota', Tipo_MascotaController::class . ':tipo_mascota')->add(UsuarioValidateMiddleware::class);
    
    //PUINTO 4
    $app->post('/mascotas', MascotaController::class . ':addMascota')->add(UsuarioValidateMiddleware::class);
     
    //PUNTO 5
    $app->group('/turnos', function (RouteCollectorProxy $group) {
         $group->post('[/mascota]', TurnoController::class . ':addTurno')->add(UsuarioValidateMiddleware::class);
         //$group->get('/:id', MascotaController::class . ':getAllMascota');
    //     $group->post('registro', UsuarioController::class . ':add')->add(RegistroMiddleware::class);
     })->add(new BeforeMiddleware());
    
    

};