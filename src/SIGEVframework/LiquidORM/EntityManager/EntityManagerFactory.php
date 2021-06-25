<?php

namespace SIGEVframework\LiquidORM\EntityManager;

use SIGEVframework\LiquidORM\DataMapper\DataMapperInterface;
use SIGEVframework\LiquidORM\QueryBuilder\QueryBuilderInterface;
use SIGEVframework\LiquidORM\EntityManager\Exception\CrudException;

class EntityManagerFactory
{
    protected DataMapperInterface $dataMapper;

    protected QueryBuilderInterface $queryBuilder;

    public function __construct(DataMapperInterface $dataMapper, QueryBuilderInterface $queryBuilder)
    {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
    }

    public function create(
        string $crudString,
        string $tableSchema,
        string $tableSchemaID,
        array $options = []
    ): EntityManagerInterface {
        $crudObject = new $crudString(
            $this->dataMapper,
            $this->queryBuilder,
            $tableSchema,
            $tableSchemaID
        );
        //não seria melhor verificar antes se é uma instância?
        //Essas verificações de se um objeto é válido ou n deveria pertencer a uma mesma classe
        if (!$crudObject instanceof CrudInterface) {
            throw new CrudException('"' . $crudObject . '" não é um objeto válido para realizar um CRUD.');
        }
        return new EntityManager($crudObject);
    }
}
