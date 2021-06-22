<?php

namespace SIGEVframework\ConexaoBanco;

use PDO;

interface DatabaseConnectionInterface 
{
    /**
     * Abre uma conexão com o banco de dados.
     * 
     * @return PDO
     */
    public function open() : PDO;

    /**
     * Fecha a conexão com o banco de dados.
     * 
     * @return void
     */
    public function close() : void;
}