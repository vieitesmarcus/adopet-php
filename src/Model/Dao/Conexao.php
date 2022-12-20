<?php

namespace Adopet\Model\Dao;

use Adopet\Utils\Enviroment;
use Error;
use PDO;
use PDOException;

abstract class Conexao
{
    protected PDO $conexao;
    
    public function __construct()
    {
        try{
            Enviroment::addEnv();
            $this->conexao = new PDO(
                "mysql:host=".getenv('CONF_DB_HOST').";dbname=".getenv('CONF_DB_DBNAME'),
                getenv('CONF_DB_USERNAME'),
                getenv('CONF_DB_PASSWORD')
//                [
//                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAME utf8",
//                    PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION,
//                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
//                    PDO::ATTR_CASE => PDO::CASE_NATURAL
//                ]
            );
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conexao;
        }catch(PDOException $error){
            echo "ERRO => " . $error->getMessage();
            header('Location: /login',true, 302);
            return false;
        }catch(Error $error){
            echo "Cai no segundo catch " . $error->getMessage();
            return false;
        }
    }

    // public function insertUser(string $table,$params = [], $fields = null): bool
    // {
    //     $numparams = "";
    //     for ($i=0; $i < count($params); $i++) $numparams .= ",?";
    //     $numparams = substr($numparams, 1);
    //     try{
    //         $sql = "INSERT INTO $table ($fields) VALUES ($numparams)";  
    //         $stmt = $this->conexao->prepare($sql);
    //         return $stmt->execute($params);
    //     }catch(PDOException $e){
    //         echo 'erro ao cadastrar no banco => '. $e->getMessage();
    //         $_SESSION["email"] = 'email existente';
    //         return false;
    //     }
    // }
   
}