<?php
//ROTAS AUTENTICADAS
use Adopet\Controller\HomeController;
use Adopet\Controller\PerfilController;

return [
    '/home' => HomeController::class,
    '/perfil' => PerfilController::class
];