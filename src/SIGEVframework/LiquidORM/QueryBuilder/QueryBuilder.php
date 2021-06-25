<?php

namespace SIGEVframework\LiquidORM\QueryBuilder;

use SIGEVframework\LiquidORM\QueryBuilder\Exception\QueryBuilderInvalidArgumentException;

class QueryBuilder implements QueryBuilderInterface
{
    protected array $key;

    protected string $sqlQuery = '';

    protected const SQL_DEFAULT = [
        'conditions' => [],
        "selectors" => [],
        "replace" => false,
        "distinct" => false,
        "from" => [],
        "where" => null,
        "and" => [],
        "or" => [],
        "orderBy" => [],
        "fields" => [],
        "primary_key" => ''
    ];

    protected const QUERY_TYPES = [
        'insert',
        'select',
        'update',
        'delete',
        'pureSQL'
    ];

    public function __construct()
    {
    }

    private function isQueryTypeValid(string $type): bool
    {
        if (in_array($type, self::QUERY_TYPES)) {
            return true;
        }
        return false;
    }

    /*TO-DO: ver se não é viável usar um template method 
    para os métodos que fazem o CRUD das queries, tendo em vista que
    sempre seguem o passo-a-passo de verificar se é uma query válida
    e o "esquema de chaves";
    rever se retorno uma string ou armazeno a instrução em uma propriedade.*/
    /**
     * Método responsável por preparar a instrução SQL que insere um registro
     * em uma tabela.
     * @return string
     */
    public function insertQuery(): string
    {
        if ($this->isQueryTypeValid('insert')) {
            if (is_array($this->key['fields']) && count($this->key['fields']) > 0) {
                $keys = array_keys($this->key['fields']);
                //[0] => (valor, valor2, valor3)   [1] => (:valor1, :valor2, :valor3)
                $valuesQueryString = array(implode(', ', $keys), ":" . implode(', :', $keys));
                $this->sqlQuery = "INSERT INTO {$this->key['table']} ({$valuesQueryString[0]}) VALUES ({$valuesQueryString[1]})";
                return $this->sqlQuery;
            }
        }
        return false;
    }

    /**
     * Método responsável por preparar a instrução SQL que insere um registro
     * em uma tabela.
     *
     * @return string
     */
    public function selectQuery(): string
    {
        if ($this->isQueryTypeValid('select')) {
            //Caso nenhum seletor tenha sido informado, todas as colunas serão selecionadas (*)
            $selectors = (!empty($this->key['selectors'])) ? implode(', ', $this->key['selectors']) : '*';
            $this->sqlQuery = "SELECT {$selectors} FROM {$this->key['table']}";
            $this->sqlQuery = $this->hasConditions();
            return $this->sqlQuery;
        }
        return false;
    }

    /**
     * Método responsável por preparar a instrução SQL que atualiza um registro
     * em uma tabela.
     *
     * @return string
     */
    public function updateQuery() : string
    {
        if ($this->isQueryTypeValid('update')) {
            if (is_array($this->key['fields']) && count($this->key['fields']) > 0) {
                $values = '';
                foreach ($this->key['fields'] as $field) {
                    if ($field !== $this->key['primary_key']) {
                        //campo = :campo, campo2 = :campo2
                        $values .= $field . " = :" . $field . ", ";
                    }
                }
                //retira a vírgula colocada no último item dos valores pelo for acima
                $values = substr_replace($values, '', -2);
                if (count($this->key['fields']) > 0) {
                    //TO-DO: tentar simplificar este trecho
                    $this->sqlQuery = "UPDATE {$this->key['table']} SET {$values} WHERE {$this->key['primary_key']} = :{$this->key['primary_key']} LIMIT 1";
                    if (isset($this->key['primary_key']) && $this->key['primary_key'] === '0') {
                        unset($this->key['primary_key']);
                        $this->sqlQuery = "UPDATE {$this->key['table']} SET {$values}";
                    }
                }
                return $this->sqlQuery;
            }
        }
        return false;
    }

    public function deleteQuery(): string
    {
        if($this->isQueryTypeValid('delete')) {
            $keys = array_keys($this->key['conditions']);
            $this->sqlQuery = "DELETE FROM {$this->key['table']} WHERE {$keys[0]} = :{$keys[0]} LIMIT 1";
            $values = array_values($this->key['fields']);
            if(is_array($values) && count($values) > 1) {
                for($i = 0; $i < count($values); $i++) {
                    $this->sqlQuery = "DELETE FROM {$this->key['table']} WHERE {$keys[0]} = :{$keys[0]}";
                }
            }
            return $this->sqlQuery;
        }
        return false;
    }

    public function hasConditions()
    {
        if (isset($this->key['conditions']) && $this->key['conditions'] !='') {
            if (is_array($this->key['conditions'])) {
                $sort = [];
                foreach (array_keys($this->key['conditions']) as $where) {
                    if (isset($where) && $where !='') {
                        $sort[] = $where . " = :" . $where;
                    }
                }
                if (count($this->key['conditions']) > 0) {
                    $this->sqlQuery .= " WHERE " . implode(" AND ", $sort);
                }
            }
        } else if (empty($this->key['conditions'])) {
            $this->sqlQuery = " WHERE 1";
        }
        $this->sqlQuery .= $this->orderByQuery();
        $this->sqlQuery .= $this->queryOffset();

        return $this->sqlQuery;
    }

    public function buildQuery(array $args = []): self
    {
        if (count($args) < 0) {
            throw new QueryBuilderInvalidArgumentException();
        }
        $this->key = array_merge(self::SQL_DEFAULT, $args);
        return $this;
    }
}
