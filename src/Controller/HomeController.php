<?php

namespace Adopet\Controller;

use Adopet\Model\Dao\DaoPets;
use Adopet\Utils\View;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HomeController  implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        //CARREGA OS PETS QUE ESTÃO PARA ADOÇÃO 
        $obDaoPets = new DaoPets();
        $pets = $obDaoPets->index();
        $page = "";
        if ($pets) {
            foreach ($pets as $pet) {
                $page .= View::render('pages/pets-home', [
                    'id'      => $pet['id'],
                    'name'    => $pet['name'],
                    'age'     => $pet['age'],
                    'size'    => $pet['size'],
                    'feature' => $pet['feature'],
                    'city'    => $pet['city'],
                    'tel'     => $pet['tel']
                ]);
            }
        }
        //--------------------------------------

        $content = View::render('pages/home',[
            "pets" => $page
        ]);

        // $page = parent::getPage('Adopet | Home', $content);
        return new Response(200, [],$content);
    }
}