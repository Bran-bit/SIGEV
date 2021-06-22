<?php

namespace SIGEVframework\LiquidORM\DataMapper;

interface DataMapperInterface
{
    /**
     * Método responsável por preparar a string da instrução SQL.
     * 
     * @param string $sqlQuery
     * @return self
     */
    public function prepare(string $sqlQuery): self;

    /**
     * Define qual o tipo do dado de um parâmetro.
     * 
     * @param mixed $value
     * @return mixed
     */
    public function bind($value);

    /**
     * Decidirá de
     */
    public function bindParameters(array $fields, bool $isSearch = false);

    /**
     * retorna o número de linhas afetadas pela instrução armazenada na classe.
     * 
     * @return int|null
     */
    public function numRows(): ?int;

    /**
     * Executa a consulta preparada.
     *
     * @return void
     */
    public function execute();

    /**
     * Retorna uma única linha da consulta ao banco de dados como um objeto.
     *
     * @return Object
     */
    public function result(): Object;

    /**
     * Retorna todas as linhas da consulta como um array.
     *
     * @return array
     */
    public function results(): array;

    /**
     * Retorna uma coluna do banco de dados
     *
     * @return mixed
     */
    public function column();

    /**
     * Retorna o identificador da última linha inserida na tabela do banco de dados.
     *
     * @return int
     * @throws Throwable
     */
    public function getLastId():int;
}
