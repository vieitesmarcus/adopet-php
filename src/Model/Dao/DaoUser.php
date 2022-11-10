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

        return parent::insert("user", [$name, $email, $password], 'name, email, password');
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
    
}