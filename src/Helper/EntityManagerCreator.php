<?php

namespace Adopet\Helper;

use Adopet\Utils\Enviroment;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\Setup;

class EntityManagerCreator
{
    public static function createEntityManager(): ?EntityManager
    {
        $isDevMode = true;
        $proxyDir = null;
        $cache = null;
        $useSimpleAnnotationReader = false;
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/../../src"), $isDevMode, $proxyDir,
            $cache,
            $useSimpleAnnotationReader);
        // or if you prefer yaml or XML
        // $config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
        // $config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

        // database configuration parameters
        Enviroment::addEnv();

            $conn = array(
                'driver' => 'pdo_mysql',
                'dbname' => 'adopet',
                'host' => 'localhost',
                'user' => 'root',
                'password' => getenv('CONF_DB_PASSWORD')
            );

            // obtaining the entity manager
            return EntityManager::create($conn, $config);


    }
}