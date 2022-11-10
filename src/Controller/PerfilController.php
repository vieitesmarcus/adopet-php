<?php

namespace Adopet\Controller;

use Adopet\Utils\View;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PerfilController  implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        $content = View::render('pages/perfil');

        // $page = parent::getPage('Adopet | Home', $content);
        return new Response(200, [],$content);
    }
}