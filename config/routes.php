<?php
// ROTAS NÃO AUTENTICADAS

use Adopet\Controller\CadastrarUserController;
use Adopet\Controller\CadastroController;
use Adopet\Controller\HomeController;
use Adopet\Controller\IndexController;
use Adopet\Controller\LoginController;
use Adopet\Controller\LoginUserController;

return [
    '/login' => LoginController::class,
    '/index' => IndexController::class,
    '/cadastro' => CadastroController::class,
    '/cadastrar' => CadastrarUserController::class,
    '/loginUser' => LoginUserController::class,
    '/home' => HomeController::class
];