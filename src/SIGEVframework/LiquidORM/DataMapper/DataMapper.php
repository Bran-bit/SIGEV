<?php

namespace SIGEVframework\LiquidORM\DataMapper;

use PDO;
use Throwable;
use PDOStatement;
use SIGEVframework\ConexaoBanco\DatabaseConnectionInterface;
use SIGEVframework\LiquidORM\DataMapper\Exception\DataMapperException;
use SIGEVframework\LiquidORM\DataMapper\DataMapperInterface;

class DataMapper implements DataMapperInterface
{
    /**
     * @var DatabaseConnectionInterface
     */
    private DatabaseConnectionInterface $database;

    /**
     * @var PDOStatement
     */
    private PDOStatement $statement;

    public function __construct(DatabaseConnectionInterface $database)
    {
        $this->database = $database;
    }

    /**
     * Checa se os valores recebidos são válidos.
     *
     * @param mixed $value
     * @param string|null $errorMessage
     * @return void
     * @throws DataMapperException
     */

    private function isEmpty($value, string $errorMessage = null)
    {
        if (empty($value)) {
            throw new DataMapperException($errorMessage);
        }
    }

    /**
     * Checa se o valor recebido é um vetor.
     * Caso contrário, retorna uma exceção.
     * 
     * @param array $value
     * @return void
     * @throws BaseInvalidArgumentException
     */
    private function isArray(array $value)
    {
        if (!is_array($value)) {
            throw new BaseInvalidArgumentException('Seu argumento precisa ser um array');
        }
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $sqlQuery): self
    {
        $this->statement = $this->database->open()->prepare($sqlQuery);
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param [type] $value
     * @return void
     */
    public function bind($value)
    {
        try {
            switch ($value) {
                case is_bool($value):
                case intval($value):
                    $dataType = PDO::PARAM_INT;
                    break;
                case is_null($value):
                    $dataType = PDO::PARAM_NULL;
                    break;
                default:
                    $dataType = PDO::PARAM_STR;
                    break;
            }
            return $dataType;
        } catch (BaseException $exception) {
            throw $exception;
        }
    }

    public function bindParameters(array $fields, bool $isSearch = false)
    {
        $this->isArray($fields);
        if (is_array($fields)) {
            $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);
            if ($type) {
                return $this;
            }
        }
        return false;
    }

     /**
     * Vincula um valor a um nome correspondente ou a algum marcador (?)
     * na instrução SQL para prepará-la, de forma compatível com search queries.
     * 
     * @param array $fields
     * @return PDOStatement
     * @throws BaseInvalidArgumentException
     */
    protected function bindValues(array $fields): PDOStatement
    {
        $this->isArray($fields);
        foreach($fields as $key => $value) {
            $this->statement->bindValue(':' . $key, $value, $this->bind($value));
        }
        return $this->statement;
    }

      /**
     * Vincula um valor a um nome correspondente ou a algum marcador (?)
     * na instrução SQL para prepará-la, de forma compatível com search queries.
     * 
     * @param array $fields
     * @return mixed
     * @throws BaseInvalidArgumentException
     */
    protected function bindSearchValues(array $fields) :  PDOStatement
    {
        $this->isArray($fields); 
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key,  '%' . $value . '%', $this->bind($value));
        }
        return $this->statement;
    }

     /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($this->statement) 
            return $this->statement->execute();
    }

    /**
     * @inheritDoc
     */
    public function numRows() : int
    {
        if ($this->statement) return $this->statement->rowCount();
    }

    /**
     * @inheritDoc
     */
    public function result() : Object
    {
        if ($this->statement) return $this->statement->fetch(PDO::FETCH_OBJ);
    }
    /**
     * @inheritDoc
     */
    public function results() : array
    {
        if ($this->statement) return $this->statement->fetchAll();
    }

    /**
     * @inheritDoc
     */
    public function column()
    {
        if ($this->statement) return $this->statement->fetchColumn();
    }

    /**
     * @inheritDoc
     */
    //TO-DO: rever este if
    public function getLastId() : int
    {
        try {
            if ($this->dbh->database()) {
                $lastID = $this->database->open()->lastInsertId();
                if (!empty($lastID)) {
                    return intval($lastID);
                }
            }
        }catch(Throwable $throwable) {
            throw $throwable;
        }
    }

    public function buildQueryParameters(array $conditions = [], array $parameters = []) 
    {
        return (!empty($parameters) || (!empty($conditions)) ? array_merge($conditions, $parameters) : $parameters);
    }

    public function persist(string $sqlQuery, array $parameters) 
    {
        try {
            return $this->prepare($sqlQuery)->bindParameters($parameters)->execute();
        } catch(Throwable $throwable) {

        }
    }
}
