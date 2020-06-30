<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Utils\Funciones;

class ExisteUserMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        //$response = $handler->handle($request);
        $body = $request->getParsedBody();
    
        $email = $body["email"];
        $usuarios = Funciones::TraerTodos();

        $existe = Funciones::ExisteUser($usuarios, $email);

        if($existe)
        {
            $response = new Response();
            $response->getBody()->write('Usuario Existente');
            $response->withStatus(403);
            return $response;
        }
        else
        {
            $response = $handler->handle($request);
            $existingContent = (string) $response->getBody();
            $resp = new Response();
            $resp->getBody()->write($existingContent); 
            return $resp; 
        }

    }
}