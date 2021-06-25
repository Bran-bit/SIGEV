<?php

namespace SIGEVframework\LiquidORM\EntityManager;

interface CrudInterface
{
    /**
     * Método responsável por retornar o nome da tabela do banco de dados.
     *
     * @return string
     */
    public function getSchema(): string;

    /**
     * Método responsável por retornar a chave primária da tabela.
     *
     * @return string
     */
    public function getSchemaID(): string;

    /**
     * Método responsável por retornar a chave primária 
     * do último registro adicionado na tabela.
     *
     * @return integer
     */
    public function lastID(): int;

    /**
     * Método responsável pela criação de um registro na tabela.
     *
     * @param array $field
     * @return boolean
     */
    public function create(array $field = []): bool;

    /**
     * Método responsável pela leitura de registros da tabela.
     *
     * @param array $selectors Define os seletores da consulta.
     * @param array $conditions Define as condições da consulta.
     * @param array $parameters Define os parâmetros da consulta.
     * @param array $optional Define parâmetros adicionais da consulta.
     * @return array
     */
    public function read(
        array $selectors = [],
        array $conditions = [],
        array $parameters = [],
        array $optional = []
    ): array;

    /**
     * Método responsável pela atualização de campos da tabela.
     *
     * @param array $fields Campos a serem atualizados.
     * @param string $primaryKey Valor da chave primária.
     * @return boolean
     */
    public function update(array $fields = [], string $primaryKey): bool;

    /**
     * Método responsável pela exclusão de registros da tabela.
     *
     * @param array $conditions
     * @return void
     */
    public function delete(array $conditions = []): bool;

    /**
     * Método responsável por realizar uma busca mais complexa que um SELECT comum.
     *
     * @param array $selectors
     * @param array $conditions
     * @return array
     */
    public function search(array $selectors = [], array $conditions = []): array;

    public function pureSqlQuery(string $query, array $conditions = []);
}
