<?php

namespace SIGEVframework\LiquidORM\QueryBuilder;

use Exception;
use SIGEVframework\LiquidORM\QueryBuilder\QueryBuilderInterface;
use SIGEVframework\LiquidORM\QueryBuilder\Exception\QueryBuilderException;

class QueryBuilderFactory
{
      public function __construct()
      {
          
      }

      public function create(string $queryBuilderString): QueryBuilderInterface
      {
          $queryBuilderObject = new $queryBuilderString();
          if(!$queryBuilderObject instanceof QueryBuilderInterface) {
              throw new QueryBuilderException('"' . $queryBuilderObject . '" não é um objeto válido para instanciar um query builder.');
          }
          return $queryBuilderObject;
      }
}