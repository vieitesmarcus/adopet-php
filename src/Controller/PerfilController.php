<?php

namespace Adopet\Controller;

use Adopet\Model\Dao\DaoPerfil;
use Adopet\Model\Entity\Perfil;
use Adopet\Model\Entity\User;
use Adopet\Utils\Errors;
use Adopet\Utils\View;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PerfilController  implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        //instancia de User para poder criar um perfil
        $obUser = new User(
            $_SESSION['user']['name'],
            $_SESSION['user']['email']
        );
        $obUser->setId($_SESSION['user']['id']);

        $obDaoPerfil = new DaoPerfil();
        $obPerfil = new Perfil($obUser->getId());
        $result = $obDaoPerfil->findByIdUser($obUser->getId());

        if ($result) {
            // var_dump($result);
            $obPerfil->setId($result[0]['id']);
            $obPerfil->setPhone($result[0]['phone']);
            $obPerfil->setCity($result[0]['city']);
            $obPerfil->setAbout($result[0]['about']);
            $obPerfil->setPhoto($result[0]['photo']);
            $obPerfil->setName($result[0]['name']);
        }

        if ($request->getParsedBody() && array_key_exists('perfil', $request->getParsedBody())) {
            $perfil = $request->getParsedBody()['perfil'];
            $perfil = filter_var_array($perfil, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            //VERIFICAR EXTENSÃO DA IMAGEM
            
            if ($_FILES['avatar']['tmp_name'] !== "") { //VERIFICA SE VEIO ALGO NO $_FILES
                $extensoesPermitidas = ['jpeg', 'jpg', 'png'];
                $upload = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                if (!in_array($upload, $extensoesPermitidas)) {
                    unset($perfil, $_FILES);
                    $obError = new Errors();
                    $obError->addMessage('upload', "Formato inválido");
                    return new Response(302, ['Location' => '/perfil']);
                }
                $drivePhoto   = __DIR__ . "/../../public/avatar/";  //aonde será armazenado os avatares do perfil
                $photoTmp     = $_FILES['avatar']['tmp_name'];     // armazena o nome temporario da foto
                $newNamePhoto = uniqid() . ".$upload";               // cria uma novo nome para a foto ser unica e não haver substituição de arquivo
                move_uploaded_file($photoTmp, $drivePhoto . $newNamePhoto);  // move o arquivo temporario com novo nome para a pasta de destino
                if (file_exists($drivePhoto . $obPerfil->getPhoto()) && $obPerfil->getPhoto() !== "") { // verifica se existe alguma foto para aquele perfil
                    unlink($drivePhoto . $obPerfil->getPhoto()); //exclui a foto antiga do perfil
                }
                $obPerfil->setPhoto($newNamePhoto);
            }

            $obPerfil->setName($perfil['name']);
            $obPerfil->setPhone($perfil['phone']);
            $obPerfil->setAbout($perfil['about']);
            $obPerfil->setCity($perfil['city']);
            $obDaoPerfil->insert($obPerfil, $obUser->getId());
            return new Response(302, ['location' => '/perfil']);
        }






        var_dump($obPerfil);

        $upload = isset($_SESSION['upload']) ? $_SESSION['upload'] : "";

        if (!file_exists( __DIR__ . "/../../public/avatar/".$obPerfil->getPhoto())) {
            $imgPerfil = 'user.svg';
        } else {
            $imgPerfil = $obPerfil->getPhoto();
        }
        $content = View::render('pages/perfil', [
            'upload'     => $upload,
            'name'       => $obPerfil->getName(),
            'phone'      => $obPerfil->getPhone(),
            'city'       => $obPerfil->getCity(),
            'about'      => $obPerfil->getAbout(),
            'url-avatar' => $imgPerfil

        ]);
        unset($_SESSION['upload']);

        // $page = parent::getPage('Adopet | Home', $content);
        return new Response(200, [], $content);
    }

    public function getPerfil(): void
    {
        # code...
    }
}
