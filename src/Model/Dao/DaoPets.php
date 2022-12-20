<?php

namespace Adopet\Model\Dao;

use Adopet\Model\Entity\Pets;
use PDO;

class DaoPets extends Conexao
{


    public function verifyQuantityPagesTotally()
    {
        $sql = "SELECT Count(id) FROM pets";
        $stmt = $this->conexao->prepare($sql);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return $result;
        }
        return false;
    }

    public function verifyQuantityPages($id)
    {
        try{


        $sql = "SELECT Count(id) FROM pets WHERE user_id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return $result;
        }
        }catch(\PDOException $e){
          echo  $e->getMessage();
        }
    }

    public function find($id)
    {
        $sql = "SELECT id, name, age, size,feature, city, tel, photo FROM pets WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);

    }

    public function findById($id, $idUser)
    {
        $idUser = (int)$idUser;
        $id = (int)$id;
        try{
            $sql = "SELECT * FROM pets WHERE id = :id AND user_id = :user_id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([':id'=>$id, ':user_id'=>$idUser]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        }catch(\PDOException $e){
            echo $e->getMessage();
        }


    }

    public function findAll($idUser, $begin)
    {
        $idUser = (int)$idUser;
        $beginInt = ((int)$begin - 1) * 9;
        $sql = "SELECT id, name, age, size,feature, city, tel, photo FROM pets WHERE user_id = ? LIMIT ?, 9";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(1,$idUser,PDO::PARAM_INT);
        $stmt->bindParam(2,$beginInt,PDO::PARAM_INT);
        $stmt->execute();
//        $result = $stmt->fetchAll(PDO::FETCH_CLASS, Pets::class);

        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Pets::class);
//        if($result){
//            return $result;
//        }
//        return false;
    }

    public function index($begin)
    {
        $beginInt = ((int)$begin - 1) * 20;
        $sql = "SELECT id, name, age, size,feature, city, tel, photo FROM pets LIMIT ?, 20";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(1,$beginInt,PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            return $result;
        }
        return false;
    }

    public function update(Pets $pet, $idUser)
    {
        $sql = "UPDATE pets SET name = :name, age = :age, size = :size, feature = :feature,
                city = :city, tel = :tel, photo = :photo WHERE id = :id and user_id = :user_id";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([
            ":name"=>$pet->getName(),
            ":age"=>$pet->getAge(),
            ":size" => $pet->getSize(),
            ":feature" => $pet->getFeature(),
            ":city"=>$pet->getCity(),
            ":tel" =>$pet->getTel(),
            ":photo" => $pet->getPhoto(),
            ":id"=>$pet->getId(),
            ":user_id" =>$idUser
        ]);
    }

    public function delete($id, $idUser):bool
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
        $param[] = $pet->getPhoto();

        $sql = "INSERT INTO pets (name, age, size, feature, city, tel, user_id, photo) VALUES(?,?,?,?,?,?,?,?)";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute($param);
    }
}