<?php

namespace SIGEVframework\ConexaoBanco;

use PDO;
use PDOException;
use SIGEVframework\ConexaoBanco\DatabaseConnectionInterface;
use SIGEVframework\ConexaoBanco\Exception\DatabaseConnectionException;

class DatabaseConnection implements DatabaseConnectionInterface
{
    /**
     * @var PDO
     */
    protected PDO $databasePDO;

    /**
     * @var array
     */
    protected array $credentials;

    /**
     * Construtor
     * 
     * @return void
     */
    public function __constructor(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @inheritDoc
     */
    public function open(): PDO
    {
        try {
            $params = [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
            $this->databasePDO = new PDO(
                $this->credentials['dsn'],
                $this->credentials['username'],
                $this->credentials['password'],
                $params
            );
        } catch(PDOException $exception) {
            throw new DatabaseConnectionException($exception->getMessage(), (int)$exception->getCode());
        }
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
        $this->databasePDO = null;
    }
}
