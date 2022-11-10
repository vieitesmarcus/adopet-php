<?php 

namespace Adopet\Utils;

class Errors
{
    const EMAIL = 'email';
    const NAME = 'name';
    const PASSWD = 'password';
    const PASSWDREPEAT = 'confirmPassword';
    
    public $errors = [];

    public function addMessage(string $error, string $message): void
    {
        $_SESSION[$error] = $message;
        $this->errors[] = $_SESSION[$error];
    }

    public function getErros(): array
    {
        return $this->errors;
    }
}