<?php

namespace Adopet\Controller;

use Adopet\Model\Dao\DaoUser;
use Adopet\Model\Entity\User;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginUserController  implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user['user']             = $request->getParsedBody();
        $user['user']['email']    = filter_var($user['user']['email'], FILTER_SANITIZE_EMAIL);
        $user['user']['password'] = filter_var($user['user']['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $obUser = new User("", $user['user']['email'], $user['user']['password']);
//        var_dump($obUser);exit();
        $obDaoUser = new DaoUser();
        if(!$obDaoUser->load($obUser)){
            return new Response(302, ['Location'=>'/login']);
        }
        
        $_SESSION["logado"] = true;
        return new Response(200, ['location'=>'/mypets']);
    }
}
