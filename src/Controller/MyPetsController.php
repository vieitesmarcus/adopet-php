<?php

namespace Adopet\Controller;

use Adopet\Model\Dao\DaoPets;
use Adopet\Model\Entity\Pets;
use Adopet\Utils\View;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MyPetsController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        if($request->getQueryParams() && key_exists('id', $request->getQueryParams())){ //só vai deletar ou entra no if se a key do array for id 
            $id = filter_var($request->getQueryParams()['id'], FILTER_VALIDATE_INT);
            $idUser = $_SESSION["user"]['id'];
            
            $obDaoPets = new DaoPets();
            $obDaoPets->delete($id,$idUser);
            return new Response(200, ['location'=> '/mypets']);
        }

        if($request->getParsedBody()){
            $pet['pet'] = $request->getParsedBody();
            $pet['pet']['name'] =  filter_var($pet['pet']['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pet['pet']['age'] =  filter_var($pet['pet']['age'], FILTER_VALIDATE_INT);
            $pet['pet']['size'] =  filter_var($pet['pet']['size'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pet['pet']['feature'] =  filter_var($pet['pet']['feature'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pet['pet']['city'] =  trim(filter_var($pet['pet']['city'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $pet['pet']['tel'] =  str_replace(" ","",filter_var($pet['pet']['tel'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));

            $obPet = new Pets(
                $pet['pet']['name'],
                $pet['pet']['age'],
                $pet['pet']['size'],
                $pet['pet']['feature'],
                $pet['pet']['city'],
                $pet['pet']['tel']
            );
            $obDaoPets = new DaoPets();
            $obDaoPets->insert($obPet, $_SESSION['user']['id']);
            return new Response(200, ['location'=>'/mypets']);
        }
        
        // if($request->getQueryParams())

        //VERIFICA SE EXISTE ALGUM ERRO NOS CAMPOS DE INSERÇÃO DE PET, CASO EXISTA, ELE APARECE
        $nome = isset($_SESSION["nome"]) ? $_SESSION["nome"] : "";
        $idade = isset($_SESSION["idade"]) ? $_SESSION["idade"] : "";
        $tamanho = isset($_SESSION["tamanho"]) ? $_SESSION["tamanho"] : "";
        $caracteristicas = isset($_SESSION["caracteristicas"]) ? $_SESSION["caracteristicas"] : "";
        $city = isset($_SESSION["city"]) ? $_SESSION["city"] : "";
        $tel = isset($_SESSION["tel"]) ? $_SESSION["tel"] : "";
        //------------------------------------------------------------

        //CARREGA OS PETS QUE O USUARIO ADICIONOU 
        $obDaoPets = new DaoPets();
        $pets = $obDaoPets->loadAll($_SESSION['user']['id']);
        $page = "";
        if ($pets) {
            foreach ($pets as $pet) {
                $page .= View::render('pages/pets', [
                    'id' => $pet['id'],
                    'name' => $pet['name'],
                    'age'  => $pet['age'],
                    'size' => $pet['size'],
                    'feature' => $pet['feature'],
                    'city' => $pet['city'],
                    'tel' => $pet['tel']
                ]);
            }
        }
        //--------------------------------------



        $content = View::render('pages/mypets', [
            'nome' => $nome,
            'idade' => $idade,
            'tamanho' => $tamanho,
            'caracteristicas' => $caracteristicas,
            'city' => $city,
            'tel' => $tel,
            "pets" => $page
        ]);

        // $page = parent::getPage('Adopet | Home', $content);
        return new Response(200, [], $content);
    }
}
