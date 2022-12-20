<?php

namespace Adopet\Controller;

use Adopet\Model\Dao\DaoPerfil;
use Adopet\Model\Dao\DaoPets;
use Adopet\Model\Dao\DaoUser;
use Adopet\Model\Entity\Perfil;
use Adopet\Model\Entity\Pets;
use Adopet\Model\Entity\User;
use Adopet\Utils\PhotoUpload;
use Adopet\Utils\View;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EditPetController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $obDaoUser = (new DaoUser())->find($_SESSION['user']['id']);
        $obUser = new User($obDaoUser->name, $obDaoUser->email);
        $obUser->setId($obDaoUser->id);
        $obUser->setMailValidation($obDaoUser->email_validation);
        $obUser->setCreated_at($obDaoUser->created_at);


        /*
         *@var Perfil $obPerfil
         */
        $obPerfil = (new DaoPerfil())->findByIdUser($obUser->getId());



        if(array_key_exists('id', $request->getQueryParams()) && $request->getQueryParams()['id'] !== ""){
            if(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)){
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRIPPED);
                $obPet = (new DaoPets())->findById($id, $_SESSION['user']['id']);
                if(!$obPet){
                    return new Response(302, ['location'=> '/mypets']);
                }
            }

            $content = View::render('pages/edit-pets',[
                'upload' => $_SESSION['upload']??"",//possiveis erros
                'nome' => $_SESSION['nome']??"",//possiveis erros
                'idade' => $_SESSION['idade']??"",//possiveis erros
                'tamanho' => $_SESSION['tamanho']??"",//possiveis erros
                'caracteristicas' => $_SESSION['caracteristicas']??"",//possiveis erros
                'cidade' => $_SESSION['cidade']??"",//possiveis erros
                'telefone' => $_SESSION['telefone']??"",//possiveis erros
                'url-pet'=> $photo = ($obPet->photo === "") ? 'dunga.png':$obPet->photo,
                'id'=>$obPet->id,
                'name' => $obPet->name??"",//pets
                'age' => $obPet->age??"",//pets
                'size' => $obPet->size??"",//pets
                'feature' => $obPet->feature??"",//pets
                'city' => $obPet->city??"",//pets
                'tel' => $obPet->tel??"",//pets
                'url-avatar' => $obPerfil->photo??"../img/user.svg"
            ]);


            return new Response(200, [], $content);

        }

        if(array_key_exists('pet', $request->getParsedBody()??[]) && $request->getParsedBody()){
            $pets = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
            $pet = (new DaoPets())->findById($pets['pet']['id'], $obUser->getId());

            $petNovo = new Pets(
                (int)$pets['pet']['id'],
                $pets['pet']['name'],
                $pets['pet']['age'],
                $pets['pet']['size'],
                $pets['pet']['feature'],
                $pets['pet']['city'],
                $pets['pet']['tel'],
            );
            $petNovo->setPhoto($pet->photo);


            if ($_FILES['photo']['tmp_name'] !== "") { //VERIFICA SE VEIO ALGO NO $_FILES
                $obUpload = (new PhotoUpload())->addPhoto(
                    __DIR__."/../../public/img/pets/",
                    $_FILES['photo'],
                    $petNovo
                );
            }

            $update = (new DaoPets())->update($petNovo, $obUser->getId());

        }



        return new Response(302, ['location'=> '/mypets']);
    }
}