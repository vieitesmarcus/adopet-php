<?php

namespace Adopet\Controller;

use Adopet\Model\Dao\DaoPerfil;
use Adopet\Model\Entity\Perfil;
use Adopet\Model\Entity\User;
use Adopet\Utils\Errors;
use Adopet\Utils\PhotoUpload;
use Adopet\Utils\View;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PerfilController implements RequestHandlerInterface
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
            $obPerfil->setId($result->id);
            $obPerfil->setPhone($result->phone);
            $obPerfil->setCity($result->city);
            $obPerfil->setAbout($result->about);
            $obPerfil->setPhoto($result->photo);
            $obPerfil->setName($result->name);
        }

        if ($request->getParsedBody() && array_key_exists('perfil', $request->getParsedBody())) {
            $perfil = $request->getParsedBody()['perfil'];
            $perfil = filter_var_array($perfil, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            //VERIFICAR EXTENSÃO DA IMAGEM

            if ($_FILES['avatar']['tmp_name'] !== "") { //VERIFICA SE VEIO ALGO NO $_FILES
                $obUpload = (new PhotoUpload())->addPhoto(
                    __DIR__."/../../public/avatar/",
                    $_FILES['avatar'],
                    $obPerfil
                );
            }

            $obPerfil->setName($perfil['name']);
            $obPerfil->setPhone($perfil['phone']);
            $obPerfil->setAbout($perfil['about']);
            $obPerfil->setCity($perfil['city']);
            $obDaoPerfil->insert($obPerfil, $obUser->getId());
            return new Response(302, ['location' => '/perfil']);
        }


//        var_dump($obPerfil);

        $upload = isset($_SESSION['upload']) ? $_SESSION['upload'] : "";
        //VERIFICA SE EXISTE FOTO DO AVATAR SE NÃO HOUVER, SERÁ COLOCADA UMA IMG GENERICA
        if ($obPerfil->getPhoto() === "" && file_exists(__DIR__."/../../public/avatar/".$obPerfil->getPhoto())) {
            $imgPerfil = '../img/user.svg';
        } else {
            $imgPerfil = $obPerfil->getPhoto();
        }

        $content = View::render('pages/perfil', [
            'upload' => $upload,
            'name' => $obPerfil->getName(),
            'phone' => $obPerfil->getPhone(),
            'city' => $obPerfil->getCity(),
            'about' => $obPerfil->getAbout(),
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
