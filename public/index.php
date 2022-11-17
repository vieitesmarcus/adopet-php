<?php
session_start();

var_dump($_SESSION);

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

// PEGA OS DADOS DA URL
$url = $_SERVER['PATH_INFO'] ?? '/login';
require_once __DIR__ . '/../vendor/autoload.php';
$routes = ""; //VERIFICA SE O USUARIO ESTÁ AUTENTICADO OU NÃO
if(isset($_SESSION['logado']) && $_SESSION['logado'] === true){
    $routes = require __DIR__ . '/../config/routesAutenticadas.php';
}else{
    $routes = require __DIR__ . '/../config/routes.php';
}

// VERIFICA SE A ROTA EXISTE COM A URL QUE VEM 
if(!array_key_exists($url, $routes)){
    http_response_code(404);
    if(isset($_SESSION['logado']) && $_SESSION['logado'] === true){
        header('Location:/mypets');
        exit();
    }
    header('Location:/login');
    exit();
}

$psr17Factory = new Psr17Factory();

$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$serverRequest = $creator->fromGlobals();

$controller = $routes[$url];

$page = new $controller();

$response = $page->handle($serverRequest);

foreach ($response->getHeaders() as $name => $values) {
    foreach($values as $value){
        header(sprintf('%s: %s', $name, $value), false);
    }
}

echo $response->getBody();