<?php

//use Config\Database;
//namespace Config;
use Slim\App;
use App\Middleware\AfterMiddleware;
use App\Middleware\BeforeMiddleware;


return function (App $app) {
    $app->addBodyParsingMiddleware();

    $app->add(new BeforeMiddleware());
    $app->add(new AfterMiddleware());
    //$app->add(BeforeMiddleware::class);
    
};