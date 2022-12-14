<?php

namespace Adopet\Controller;

use Adopet\Model\Dao\DaoPerfil;
use Adopet\Model\Dao\DaoPets;
use Adopet\Model\Dao\DaoUser;
use Adopet\Model\Entity\Perfil;
use Adopet\Model\Entity\Pets;
use Adopet\Model\Entity\User;
use Adopet\Utils\Errors;
use Adopet\Utils\PhotoUpload;
use Adopet\Utils\View;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MyPetsController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        //---------------------------------------------------------------------------
        //cria um objeto de Usuario
        $obUser = new User($_SESSION['user']['name'], $_SESSION['user']['email']);
        $obUser->setMailValidation($_SESSION['user']['email_validation']);
        $obUser->setId($_SESSION['user']['id']);
        $obUser->setCreated_at($_SESSION['user']['created_at']);
        $obUser->setUpdated_at($_SESSION['user']['updated_at']);

        //cria um objeto de perfil
        $obPerfil = new Perfil($obUser->getId());
        if ($daoPerfil = (new DaoPerfil())->findByIdUser($obPerfil->getIdUser())) {
            $obPerfil->setId($daoPerfil->id);
            $obPerfil->setPhoto($daoPerfil->photo);
            $obPerfil->setName($daoPerfil->name);
            $obPerfil->setPhone($daoPerfil->phone);
            $obPerfil->setCity($daoPerfil->city);
            $obPerfil->setAbout($daoPerfil->about);
        }
        $obUser->setPerfil($obPerfil);
        //---------------------------------------------------------------------------


        //verifica e retorna a quantidade de paginas
        $qntyPets = (new DaoPets())->verifyQuantityPages($obUser->getId());
        if ((int)$qntyPets['Count(id)'] < 9) {
            $qntyPages = 0;
        }
        //dividi a quantidade por 9 para permitir somente 9 registros na página
        $qntyPages = ((int)((int)$qntyPets['Count(id)'] / 9) + 1);

        //verifica se existe algum parametro chamado id na queryParams
        if ($request->getQueryParams() && key_exists('id',
                $request->getQueryParams())) { //só vai deletar ou entra no if se a key do array for id
            $id = filter_var($request->getQueryParams()['id'], FILTER_VALIDATE_INT);
            $idUser = $obUser->getId();

            $obDaoPets = new DaoPets();
            $obPet = $obDaoPets->find($id); //armazena o item que será deletado

            if ($obDaoPets->delete($id,
                $idUser)) { //busca o item pelo id dele mesmo e o id do usuario para que somente o usuario delete o item.
                $drivePhoto = __DIR__."/../../public/img/pets/";
                if (file_exists($drivePhoto.$obPet->photo) && $obPet->photo !== "" && $obPet->photo !== null) { // verifica se existe alguma foto para aquele pet
                    unlink($drivePhoto.$obPet->photo); //exclui a foto Pet
                }
            }
            return new Response(200, ['location' => '/mypets']);
        }

        //VERIFICA SE EXISTEM DADOS NO POST PARA INSERIR UM PET NOVO
        if ($request->getParsedBody()) {

            $pet['pet'] = $request->getParsedBody();
            $pet['pet']['name'] = filter_var($pet['pet']['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pet['pet']['age'] = filter_var($pet['pet']['age'], FILTER_VALIDATE_INT);
            $pet['pet']['size'] = filter_var($pet['pet']['size'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pet['pet']['feature'] = filter_var($pet['pet']['feature'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pet['pet']['city'] = trim(filter_var($pet['pet']['city'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $pet['pet']['tel'] = str_replace(" ", "",
                filter_var($pet['pet']['tel'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));

//           CRIAR UM OBJETO DE PETS
            $obPet = new Pets(0,
                $pet['pet']['name'],
                $pet['pet']['age'],
                $pet['pet']['size'],
                $pet['pet']['feature'],
                $pet['pet']['city'],
                $pet['pet']['tel']
            );
            $obPet->setPhoto("");

            // DEVO MONTAR UMA CLASSE ESPECÍFICA PARA O UPLOAD DE FOTOS, POIS ESTOU USANDO NO PERFIL E NO PETS
            if ($_FILES['photo']['tmp_name'] !== "") { //VERIFICA SE VEIO ALGO NO $_FILES

                $upload = (new PhotoUpload())->addPhoto(
                    __DIR__."/../../public/img/pets/",
                    $_FILES['photo'],
                    $obPet
                );
                if(!$upload){
                    return new Response(302, ['location' => '/mypets']);
                }
            }
            //FINAL DE UPLOAD DE FOTO


            $obDaoPets = new DaoPets();
            $obDaoPets->insert($obPet, $obUser->getId());
            return new Response(200, ['location' => '/mypets']);
        }


        //VERIFICA SE EXISTE ALGUM ERRO NOS CAMPOS DE INSERÇÃO DE PET, CASO EXISTA, ELE APARECE
        $nome = $_SESSION["nome"] ?? "";
        $idade = $_SESSION["idade"] ?? "";
        $tamanho = $_SESSION["tamanho"] ?? "";
        $caracteristicas = $_SESSION["caracteristicas"] ?? "";
        $city = $_SESSION["city"] ?? "";
        $tel = $_SESSION["tel"] ?? "";
        $photo = $_SESSION["photo"] ?? "";
        //------------------------------------------------------------


        $page = 1;
        if ($request->getQueryParams() && key_exists('page',
                $request->getQueryParams())) {
            $justNumber = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
            $beginThanZero = ($justNumber) <= 1 ? 1 : $justNumber;
            $begin = ($beginThanZero) > $qntyPages ? $qntyPages : $beginThanZero;
            $page = $begin;
        }

        //CARREGA OS PETS QUE O USUARIO ADICIONOU 
        $obDaoPets = new DaoPets();


        /** @var Pets $pets */
        $pets = $obDaoPets->findAll($obUser->getId(), $page);

        $pagePets = "";
        if ($pets) {
            foreach ($pets as $pet) {
                $pagePets .= View::render('pages/pets', [
                    'id' => $pet->getId(),
                    'name' => $pet->getName(),
                    'age' => $pet->getAge(),
                    'size' => $pet->getSize(),
                    'feature' => $pet->getFeature(),
                    'city' => $pet->getCity(),
                    'tel' => $pet->getTel(),
                    'photo' => ($pet->getPhoto() == "" ? "dunga.png" : $pet->getPhoto())
                ]);
            }
        }
        //--------------------------------------


            if ($obUser->getPerfil()->getPhoto() === "") {
                $imgPerfil = '../img/user.svg';
            } else {
                $imgPerfil = $obUser->getPerfil()->getPhoto();
            }

//        }


        $content = View::render('pages/mypets', [
            'nome' => $nome,
            'idade' => $idade,
            'tamanho' => $tamanho,
            'caracteristicas' => $caracteristicas,
            'city' => $city,
            'tel' => $tel,
            "pets" => $pagePets,
            'url-avatar' => $imgPerfil,
            'photo' => $photo,
            'page' => $page,
            'pageNext' => ($page + 1) >= $qntyPages ? $qntyPages : $page+ 1,
            'pagePrev' => ($page - 1) <= 1 ? 1 : $page-1,
            'pagesTotal' => $page .' de '.$qntyPages,
            'upload' => $_SESSION['upload'] ?? ""
        ]);
        unset($_SESSION['upload']);
        // $page = parent::getPage('Adopet | Home', $content);
        return new Response(200, [], $content);
    }
}
