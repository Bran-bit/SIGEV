<?php

namespace SIGEVframework\Utilitarios;

trait formatStringTrait 
{
    /**
     * Formata uma string no padrão UpperCamelCase.
     * 
     * @param string $string
     * @return string
     */
    public function transformUpperCamelCase(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Formata uma string no padrão camelCase.
     * 
     * @param string $string
     * @return string
     */
    public function transformCamelCase(string $string): string 
    {
        return \lcfirst($this->transformUpperCamelCase($string));
    }
}