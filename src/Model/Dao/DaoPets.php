<?php

namespace Adopet\Model\Dao;

use Adopet\Model\Entity\Pets;
use PDO;
use PDOException;

class DaoPets extends Conexao
{
    // public function cadastrar(User $user): bool
    // {
    //     $name = $user->getName();
    //     $email = $user->getEmail();
    //     $password = $user->getPassword();
        
    //     if($this->verificaSeEmailExisteNoBanco($email)){
    //         return false;
    //     }

    //     return parent::insert("user", [$name, $email, $password], 'name, email, password');
    // }


    public function loadAll($id)
    {
        $sql = "SELECT id, name, age, size,feature, city, tel FROM pets WHERE user_id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            return $result;
        }
        return false;
    }

    public function delete($id, $idUser): bool
    {
        $sql = "DELETE FROM pets WHERE id = ? and user_id = ?";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([$id, $idUser]);
    }
    
    public function insert(Pets $pet, $id): bool
    {
        $param[] = $pet->getName();
        $param[] = $pet->getAge();
        $param[] = $pet->getSize();
        $param[] = $pet->getFeature();
        $param[] = $pet->getCity();
        $param[] = $pet->getTel();
        $param[] = $id;

        $sql = "INSERT INTO pets (name, age, size, feature, city, tel, user_id) VALUES(?,?,?,?,?,?,?)";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute($param);
    }
}