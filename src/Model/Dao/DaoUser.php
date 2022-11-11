<?php

namespace Adopet\Model\Dao;

use Adopet\Model\Entity\User;
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
        $email = $user->getEmail();
        $password = $user->getPassword();
        $sql = "SELECT id, name, email FROM user WHERE email = ? AND password = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$email, $password]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            $_SESSION["user"] = $result;
            return true;
        }
        return false;
    }
    
}