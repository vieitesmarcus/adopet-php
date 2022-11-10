<?php

namespace Adopet\Controller;

use Adopet\Model\Dao\DaoUser;
use Adopet\Model\Entity\User;
use Adopet\Utils\Errors;
use Adopet\Utils\View;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginUserController  implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $obError = new Errors();
        $user['user'] = $request->getParsedBody();
        $user['user']['email'] = filter_var($user['user']['email'], FILTER_SANITIZE_EMAIL);
        $user['user']['password'] = filter_var($user['user']['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $obUser = new User("",$user['user']['email'], $user['user']['password']);

        $obDaoUser = new DaoUser();
        if(!$obDaoUser->load($obUser)){
            $obError->addMessage('login', 'email ou senha inválidos!');
            return new Response(302, ['Location'=>'/login']);
        }
        // $content = View::render('pages/login');
        $_SESSION["logado"]=true;
        return new Response(200, ['location'=>'/home']);
    }
}
