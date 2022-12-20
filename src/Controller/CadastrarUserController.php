<?php

namespace Adopet\Controller;

use Adopet\Model\Dao\DaoUser;
use Adopet\Model\Entity\User;
use Adopet\Utils\Email;
use Adopet\Utils\Errors;
use Adopet\Utils\SslEncryptDecrypt;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CadastrarUserController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {


        if($request->getQueryParams() && key_exists('confirm', $request->getQueryParams())){
            $email     = filter_var($request->getQueryParams()['confirm'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            // var_dump($request->getQueryParams()['confirm']);

            //descriptografia do email
            $obSsl = new SslEncryptDecrypt();
            $emailDescriptografado = $obSsl->decrypt($email);
            // var_dump($emailDescriptografado);
            $obDao = new DaoUser();
            
            if($obDao->update([$emailDescriptografado])){
                return new Response(302, ['Location' => '/cadastro']);
            }
            // return new Response(302, ['Location' => '/cadastro']);
            exit();
        }


        //verifica se os dados no request é post
        if ($request->getParsedBody()) {

            // filtra as variaveis
            $user['user']                    = $request->getParsedBody();
            $user['user']['name']            = filter_var($user['user']['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $user['user']['email']           = filter_var($user['user']['email'], FILTER_SANITIZE_EMAIL);
            $user['user']['password']         = filter_var($user['user']['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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
                return new Response(302, ["Location" => "/cadastro"]);
                exit();
            }

            //CRIPTOGRAFAR EMAIL DO USUARIO
            $obSsl = new SslEncryptDecrypt();
            $emailCriptografado = $obSsl->encrypt($user['user']['email']);
           
            //ENVIAR EMAIL DE VALIDAÇÃO DE EMAIL PARA SER POSSÍVEL O LOGIN NA PLATAFORMA
            $obEmail = new Email([$user['user']['email']], [],[]);
            
            $bodyEmail = "Olá, {$user['user']['name']}! <br> Bem-vindo ao Adopet. <br> Confirme seu endereço de email <br> <a href='http://localhost/cadastrar?confirm={$emailCriptografado}' target='_blank'>Confirmar Email</a>";
            $subject = "Confirmação de Email";
            $obEmail->sendEmail($subject, $bodyEmail);

            // if(){ //insere um novo usuario no banco
            return new Response(302, ["Location" => "/cadastro"]);
            //};
        }

        return new Response(302, ["Location" => "/cadastro"]);
    }
}
