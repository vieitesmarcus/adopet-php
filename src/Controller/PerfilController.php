<?php

namespace Adopet\Controller;

use Adopet\Helper\EntityManagerCreator;
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

        $entityManager = EntityManagerCreator::createEntityManager();
        $newPerfil = new Perfil();
        $newPerfil->setUser($entityManager->find(User::class, $obUser->getId()));

//        $obDaoPerfil = new DaoPerfil();
//        $obPerfil = new Perfil($obUser->getId());
//        $result = $obDaoPerfil->findByIdUser($obUser->getId());

        $perfilRepository = $entityManager->getRepository(Perfil::class);
        $perfil = $perfilRepository->findOneBy(['user'=>$obUser->getId()]);
//        var_dump($newPerfil);exit();
        if (!$perfil) {
            // var_dump($result);
//            $newPerfil->setId("");
            $newPerfil->setPhone("");
            $newPerfil->setCity("");
            $newPerfil->setAbout("");
            $newPerfil->setPhoto("");
            $newPerfil->setName("");
        }else{
            $newPerfil = $perfil;
        }


        if ($request->getParsedBody() && array_key_exists('perfil', $request->getParsedBody())) {
            $perfil = $request->getParsedBody()['perfil'];
            $perfil = filter_var_array($perfil, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            //VERIFICAR EXTENSÃO DA IMAGEM

            if ($_FILES['avatar']['tmp_name'] !== "") { //VERIFICA SE VEIO ALGO NO $_FILES
                $obUpload = (new PhotoUpload())->addPhoto(
                    __DIR__."/../../public/avatar/",
                    $_FILES['avatar'],
                    $newPerfil
                );
            }

            $newPerfil->setName($perfil['name']);
            $newPerfil->setPhone($perfil['phone']);
            $newPerfil->setAbout($perfil['about']);
            $newPerfil->setCity($perfil['city']);
//            var_dump($newPerfil);exit();
//            $obDaoPerfil->insert($obPerfil, $obUser->getId());
            $entityManager->persist($newPerfil);
            $entityManager->flush();
            return new Response(302, ['location' => '/perfil']);
        }


//        var_dump($obPerfil);

        $upload = isset($_SESSION['upload']) ? $_SESSION['upload'] : "";
        //VERIFICA SE EXISTE FOTO DO AVATAR SE NÃO HOUVER, SERÁ COLOCADA UMA IMG GENERICA
        if ($newPerfil->getPhoto() === "" && file_exists(__DIR__."/../../public/avatar/".$newPerfil->getPhoto())) {
            $imgPerfil = '../img/user.svg';
        } else {
            $imgPerfil = $newPerfil->getPhoto();
        }
        var_dump($newPerfil);
        $content = View::render('pages/perfil', [
            'upload' => $upload,
            'name' => $newPerfil->getName(),
            'phone' => $newPerfil->getPhone(),
            'city' => $newPerfil->getCity(),
            'about' => $newPerfil->getAbout(),
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
