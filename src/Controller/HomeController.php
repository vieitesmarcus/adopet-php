<?php

namespace Adopet\Controller;

use Adopet\Model\Dao\DaoPets;
use Adopet\Utils\View;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HomeController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $qntyPets = (new DaoPets())->verifyQuantityPagesTotally();
        if ((int)$qntyPets['Count(id)'] < 20) {
            $qntyPages = 0;
        }
        $qntyPages = ((int)((int)$qntyPets['Count(id)'] / 20) + 1);
        $pageNumber = 1;

        if ($request->getQueryParams() && key_exists('page',
                $request->getQueryParams())) {
            $justNumber = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
            $beginThanZero = ($justNumber) <= 1 ? 1 : $justNumber;
            $begin = ($beginThanZero) > $qntyPages ? $qntyPages : $beginThanZero;
            $pageNumber = $begin;
        }

        //CARREGA OS PETS QUE ESTÃO PARA ADOÇÃO 
        $obDaoPets = new DaoPets();
        $pets = $obDaoPets->index($pageNumber);
        $page="";

        if ($pets) {
            foreach ($pets as $pet) {
                $page .= View::render('pages/pets-home', [
                    'id' => $pet['id'],
                    'name' => $pet['name'],
                    'age' => $pet['age'],
                    'size' => $pet['size'],
                    'feature' => $pet['feature'],
                    'city' => $pet['city'],
                    'tel' => $pet['tel'],
                    'photo' => ($pet['photo'] == "" ? "dunga.png" : $pet['photo']),

                ]) ;
            }
        }
        //--------------------------------------

        $content = View::render('pages/home', [
            "pets" => $page,
            'page' => $pageNumber,
            'pageNext' => ($pageNumber + 1) >= $qntyPages ? $qntyPages : $pageNumber +1,
            'pagePrev' => ($pageNumber - 1) <= 1 ? 1 : $pageNumber - 1,
            'pagesTotal' => $pageNumber . ' de '. $qntyPages
        ]);

        // $page = parent::getPage('Adopet | Home', $content);
        return new Response(200, [], $content);
    }
}