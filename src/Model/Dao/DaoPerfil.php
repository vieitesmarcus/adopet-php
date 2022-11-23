<?php

namespace Adopet\Model\Dao;

use Adopet\Model\Entity\Perfil;
use Adopet\Model\Entity\User;
use PDO;
use PDOException;

class DaoPerfil extends Conexao
{


    public function insert(Perfil $perfil, $id): bool
    {
        if ($this->findByIdUser($id)) {
            $this->update($perfil);
            return true;
        }
        $fields = [
            'photo'   => $perfil->getPhoto(),
            'name'    => $perfil->getName(),
            'phone'   => $perfil->getPhone(),
            'city'    => $perfil->getCity(),
            'about'   => $perfil->getAbout(),
            'user_id' => $perfil->getIdUser(),
        ];
        $params  = array_values($fields);
        $fields  = array_keys($fields);               //captura somente as chaves do array
        $nParams = str_repeat(",?", count($fields));  //numero de parametros que sera passado no execute
        $nParams = substr($nParams, 1);               // retira a primeira virgula da string de parametros
        $fields  = implode(",", $fields);             //transforma o array em texto para ser passado nos campos abaixo

        $sql  = "INSERT INTO perfil ($fields) VALUES ($nParams)";  //sql
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute($params);
    }

    public function update(Perfil $perfil): void
    {
        $fields = [
            'photo' => $perfil->getPhoto(),
            'name'  => $perfil->getName(),
            'phone' => $perfil->getPhone(),
            'city'  => $perfil->getCity(),
            'about' => $perfil->getAbout(),
            // 'user_id'=>$perfil->getIdUser()
        ];
        // var_dump($perfil);
        $params  = array_values($fields);
        $fields  = array_keys($fields);               //captura somente as chaves do array
        $fields  = implode(" = ?,", $fields);             //transforma o array em texto para ser passado nos campos abaixo
        $fields .= " = ?";
        $sql  = "UPDATE perfil SET $fields WHERE user_id = ?";  //sql
        $params[]= $perfil->getIdUser();
        // var_dump($sql, $params);
        // exit();
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute($params);
    }

    public function delete(Perfil $perfil): void
    {
    }

    public function read(Perfil $perfil)
    {
        try {
            $id = $perfil->getId();
            //verifica se existe no banco
            $sql = "SELECT * FROM perfil WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            if ($stmt->execute([$id])) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, 'perfil');
            }
            return false;
        } catch (PDOException $e) {
            "Error => " . $e->getMessage();
            return false;
        }
    }

    public function findByIdUser($id)
    {
        try {
            $id = (int)$id;
            //verifica se existe no banco
            $sql = "SELECT * FROM perfil WHERE user_id = ?";
            $stmt = $this->conexao->prepare($sql);
            if ($stmt->execute([$id])) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            return false;
        } catch (PDOException $e) {
            "Error => " . $e->getMessage();
        }
    }
}
