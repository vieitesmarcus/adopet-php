<?php

namespace Adopet\Controller;

use Adopet\Model\Dao\DaoUser;
use Adopet\Model\Entity\User;
use Adopet\Utils\Errors;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CadastrarUserController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        //verifica se os dados no request é post
        if ($request->getParsedBody()) {

            // filtra as variaveis
            $user['user']                    = $request->getParsedBody();
            $user['user']['name']            = filter_var($user['user']['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $user['user']['email']           = filter_var($user['user']['email'], FILTER_SANITIZE_EMAIL);
            $user['user']['password']        = filter_var($user['user']['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $user['user']['confirmPassword'] = filter_var($user['user']['confirmPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            //verifica dados vindo do post
            $obErro = new Errors();
            // verifica se a senha é igual ao confirmPassword
            if ($user['user']['password'] !== $user['user']['confirmPassword']) {
                $obErro->addMessage($obErro::PASSWDREPEAT, "As senhas precisam ser idênticas.");
            }

            $regexName = '/^[a-zA-Z ]*$/';
            if (!isset($user['user']['name']) || strlen($user['user']['name']) < 3 || preg_match($regexName, $user['user']['name']) === 0) {
                $obErro->addMessage($obErro::NAME, "não use caracteres especiais no nome e/ou nome com menos de 3 carateres.");
            }
            if (count($obErro->getErros()) > 0) {
                return new Response(302, ['Location' => '/cadastro']);
            }

            //cria um objeto de Usuario
            $obUser = new User($user['user']['name'], $user['user']['email'], $user['user']['password']);
            //instacia o DAOUser para inserir no banco
            $obDaoUser = new DaoUser();
            if (!$obDaoUser->cadastrar($obUser)) {
                $obErro->addMessage($obErro::EMAIL, "email já cadastrado!");
            }
            // if(){ //insere um novo usuario no banco
            return new Response(302, ["Location" => "/cadastro"]);
            //};
        }

        return new Response(302, ["Location" => "/cadastro"]);
    }
}
