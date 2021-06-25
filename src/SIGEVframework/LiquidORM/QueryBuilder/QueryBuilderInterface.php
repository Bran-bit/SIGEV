<?php

namespace SIGEVframework\LiquidORM\QueryBuilder;

/**
 * O mínimo que esta interface deve garantir é um CRUD.
 */
interface QueryBuilderInterface
{
    public function insertQuery(): string;

    public function selectQuery(): string;

    public function updateQuery(): string;

    public function deleteQuery(): string;
    
    public function searchQuery(): string;

    public function pureSqlQuery(): string;
}