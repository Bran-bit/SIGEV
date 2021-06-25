<?php

namespace SIGEVframework\LiquidORM\EntityManager;

use SIGEVframework\LiquidORM\EntityManager\EntityManagerInterface;
use SIGEVframework\LiquidORM\EntityManager\CrudInterface;

class EntityManager implements EntityManagerInterface
{
    /**
     * @var CrudInterface
     */
    protected CrudInterface $crud;

    public function __construct(CrudInterface $crud)
    {
        $this->crud = $crud;
    }

    /**
     *@inheritDoc
     */
    public function getCrud(): Object
    {
        return $this->crud;
    }
}