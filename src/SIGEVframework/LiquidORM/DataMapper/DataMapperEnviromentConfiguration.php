<?php

namespace SIGEVframework\LiquidORM\DataMapper;

use SIGEVframework\LiquidORM\DataMapper\Exception\DataMapperInvalidArgumentException;
/**
 * Pensei em utilizar .env nesta classe, mas está
 * muito atrelada à configuração, não às variáveis de ambiente...
 */
class DataMapperEnviromentConfiguration
{
    /**
     * @var array
     */
    private array $credentials = [];

    /**
     * Construtor da classe
     * 
     * @param array @credentials
     * @return void
     */
    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Recebe informações definidas pelo usuário sobre o banco de dados a ser utilizado.
     *
     * @param string $driver
     * @return array
     */
    public function getDatabaseCredentials(string $driver): array
    {
        $connectionArray = [];
        foreach($this->credentials as $credential) {
            if(array_key_exists($driver, $credential)) {
                $connectionArray = $credential[$driver];
            }
        }
        return $connectionArray;
    }

    /**
     * Checa se as credenciais do banco de dados são válidas.
     *
     * @param string $driver
     * @return void
     */
    private function isCredentialValid(string $driver): void
    {
        if(empty($driver) && !is_string($driver)) {
            throw new DataMapperInvalidArgumentException('Argumento inválido.');
        }
        if(!is_array($this->credentials)) {
            throw new DataMapperInvalidArgumentException('Credenciais inválidas.');
        }
        if(!in_array($driver, array_keys($this->credentials[$driver]))) {
            throw new DataMapperInvalidArgumentException('Driver de banco de dados inválido ou não possui suporte para esta aplicação.');
        }
    }

}