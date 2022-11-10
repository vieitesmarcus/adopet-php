<?php

namespace Adopet\Controller;

use Adopet\Utils\View;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginController  implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $loginError = isset($_SESSION["login"]) ? $_SESSION["login"] : "";
        session_destroy();
        $content = View::render('pages/login',[
            "error"=> $loginError
        ]);

        return new Response(200, [], $content);
    }
}