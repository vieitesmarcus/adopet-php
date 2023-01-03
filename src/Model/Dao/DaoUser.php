<?php

namespace Adopet\Model\Dao;

use Adopet\Model\Entity\User;
use Adopet\Utils\Errors;
use PDO;
use PDOException;

class DaoUser extends Conexao
{
    public function cadastrar(User $user)
    {
        $name = $user->getName();
        $email = $user->getEmail();
        $password = $user->getPassword();

        if($this->verificaSeEmailExisteNoBanco($email)){
            return false;
        }

        $sql = "INSERT INTO user (name, email, password) VALUES (:name, :email, :password)";

        try{

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        return $stmt->execute();
        }catch(PDOException $e){
            echo "error => " . $e->getMessage();
        }
    }

    public function verificaSeEmailExisteNoBanco($email): bool
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $sql = "SELECT email FROM user WHERE email = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return true;
        }
        return false;
    }

    public function load(User $user)
    {
        $obError = new Errors();
        try {
            $email = $user->getEmail();
            $password = $user->getPassword();
            $sql = "SELECT id, name, email, mailValidation, password,created_at, updated_at FROM user WHERE email = ? AND password = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$email, $password]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $_SESSION["user"] = $result;
                if ($_SESSION['user']['mailValidation'] === "0") {
                    throw new PDOException("email não validado");
                    return false;
                }
                return true;
            }
            $obError->addMessage('login', 'email ou senha inválidos!');
            return false;
        } catch (PDOException $e) {
            // echo "error => ". $e->getMessage();
            unset($_SESSION['user']);
            $obError = new Errors();
            $obError->addMessage('login', 'email não validado!');
            return false;
        }
    }

    public function update(array $params = []): bool
    {
        try {
            $sql = "UPDATE user SET mailValidation = 1 WHERE email = ?"; //sql
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            "Erro => ".$e->getMessage();
            return false;
        }

    }

    public function find($id)
    {
        $id = (int)$id;
        $sql = "SELECT id, name, email, email_validation, created_at FROM user WHERE id = :id";

        try{
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([":id"=>$id]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        }catch(PDOException $exception){
            echo $exception->getMessage();
        }
    }


}