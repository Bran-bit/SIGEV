<?php

namespace SIGEVframework\LiquidORM\EntityManager;

use SIGEVframework\LiquidORM\DataMapper\DataMapper;
use SIGEVframework\LiquidORM\QueryBuilder\QueryBuilder;
use Throwable;

class Crud implements CrudInterface
{
    protected DataMapper $dataMapper;

    protected QueryBuilder $queryBuilder;

    protected string $tableSchema;

    protected string $tableSchemaID;

    public function __construct(
        dataMapper $dataMapper,
        QueryBuilder $queryBuilder,
        string $tableSchema,
        string $tableSchemaID
    ) {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    /**
     * @inheritDoc
     */
    public function getSchema(): string
    {
        return $this->tableSchema;
    }

    /**
     * @inheritDoc
     */
    public function getSchemaID(): string
    {
        return $this->tableSchemaID;
    }

    /**
     * @inheritDoc
     */
    public function lastID(): int
    {
        return $this->dataMapper->getLastId();
    }

    /**
     * @inheritDoc
     */
    public function create(array $fields = []): bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'insert', 'fields' => $fields];
            $query = $this->queryBuilder->buildQuery($args)->insertQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
            if ($this->dataMapper->numRows() == 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function read(
        array $selectors = [],
        array $conditions = [],
        array $parameters = [],
        array $optional = []
    ): array {
        try {
            $args = [
                'table' => $this->getSchema(),
                'type' => 'select',
                'selectors' => $selectors,
                'conditions' => $conditions,
                'params' => $parameters,
                'extras' => $optional
            ];
            $query = $this->queryBuilder->buildQuery($args)->selectQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters));
            if ($this->dataMapper->numRows() > 0) {
                return $this->dataMapper->results();
            }
        } catch (Throwable $throwable) {
            throw $http_response_header;
        }
    }

    /**
     * @inheritDoc
     */
    public function update(array $fields = [], string $primaryKey): bool
    {
        try {
            $args = [
                'table' => $this->getSchema(),
                'type' => 'update',
                'fields' => $fields,
                'primary_key' => $primaryKey,
            ];
            $query = $this->queryBuilder->buildQuery($args)->updateQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
            if ($this->dataMapper->numRows() == 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            return $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(array $conditions = []): bool
    {
        try {
            $args = [
                'table' => $this->getSchema(),
                'type' => 'delete',
                'conditions' => $conditions
            ];
            //TO-DO: rever essa passagem dupla do conditions, em args e pro dataMapper
            $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            if ($this->dataMapper->numRows() == 1) {
                return true;
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

     /**
     * @inheritDoc
     */
    public function search(array $selectors = [], array $conditions = []): array
    {
        try {
            $args = [
                'table' => $this->getSchema(),
                'type' => 'search',
                'selectors' => $selectors,
                'conditions' => $conditions
            ];
            $query = $this->queryBuilder->buildQuery($args)->searchQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            if ($this->dataMapper->numRows() >= 0) {
                return $this->dataMapper->results();
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

     /**
     * @inheritDoc
     */
    public function pureSqlQuery(string $query, array $conditions = [])
    {
        try {
            $args = [
                'table' => $this->getSchema(),
                'type' => 'pureSQL',
                'pureSQL' => $query,
                'conditions' => $conditions
            ];
            $query = $this->queryBuilder->buildQuery($args)->pureSqlQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            if($this->dataMapper->numRows()) {
                 
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }
}
