<?php

namespace Adopet\Controller;

use Adopet\Utils\View;
use Error;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CadastroController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $nome            = isset($_SESSION["name"]) ? $_SESSION["name"] : "";
        $email           = isset($_SESSION["email"]) ? $_SESSION["email"] : "";
        $password        = isset($_SESSION["password"]) ? $_SESSION["password"] : "";
        $confirmPassword = isset($_SESSION["confirmPassword"]) ? $_SESSION["confirmPassword"] : "";

        $content = View::render('pages/cadastro',[
            'nome'            => $nome,
            'email'           => $email,
            'password'        => $password,
            'confirmPassword' => $confirmPassword,
        ]);
        session_destroy();
        // $page = parent::getPage('Adopet | Home', $content);
        return new Response(200, [], $content);
    }

}