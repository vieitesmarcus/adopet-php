<?php
//ROTAS AUTENTICADAS

use Adopet\Controller\LogoutController;
use Adopet\Controller\MyPetsController;
use Adopet\Controller\PerfilController;

return [
    '/mypets' => MyPetsController::class,
    '/perfil' => PerfilController::class,
    '/logout' => LogoutController::class
];