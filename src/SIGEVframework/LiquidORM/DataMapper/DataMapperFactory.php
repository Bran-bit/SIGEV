<?php

namespace SIGEVframework\LiquidORM\DataMapper;

use SIGEVframework\LiquidORM\DataMapper\DataMapper;
use SIGEVframework\ConexaoBanco\DatabaseConnectionInterface;
use SIGEVframework\LiquidORM\DataMapper\Exception\DataMapperException;
use SIGEVframework\LiquidORM\DataMapper\DataMapperInterface;

class DataMapperFactory
{
    public function __construct()
    {
        
    }

    public function create(string $databaseConnectionString,
                        string $dataMapperEnviromentConfiguration): DataMapperInterface
    {
        $credentials = (new $dataMapperEnviromentConfiguration([]))->getDatabaseCredentials();
        $databaseConnectionObject = new $databaseConnectionString($credentials);
        if (!$databaseConnectionObject instanceof DatabaseConnectionInterface) {
            throw new DataMapperException('"' . $databaseConnectionString . '" não é um objeto válido para conectar-se com o banco de dados.');
        }
        return new DataMapper($databaseConnectionObject);
    }
}