<?php

namespace Adopet\Model\Dao;

use Adopet\Model\Entity\User;
use Adopet\Utils\Errors;
use PDO;
use PDOException;

class DaoUser extends Conexao
{
    public function cadastrar(User $user): bool
    {
        $name = $user->getName();
        $email = $user->getEmail();
        $password = $user->getPassword();
        
        if($this->verificaSeEmailExisteNoBanco($email)){
            return false;
        }

        return parent::insertUser("user", [$name, $email, $password], 'name, email, password');
    }

    public function verificaSeEmailExisteNoBanco($email):bool
    {
        $sql = "SELECT email FROM user WHERE email = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return true;
        }
        return false;
    }

    public function load(User $user)
    {
        $obError                  = new Errors();
        try{
            $email = $user->getEmail();
            $password = $user->getPassword();
            $sql = "SELECT id, name, email, email_validation FROM user WHERE email = ? AND password = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$email, $password]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result){
                $_SESSION["user"] = $result;
                if($_SESSION['user']['email_validation'] === "0"){
                    throw new PDOException("email não validado");
                    return false;
                }
                return true;
            }
            $obError->addMessage('login', 'email ou senha inválidos!');
            return false;
        }catch(PDOException $e){
            // echo "error => ". $e->getMessage();
            unset($_SESSION['user']);
            $obError                  = new Errors();
            $obError->addMessage('login', 'email não validado!');
            return false;
        }
    }
    
}