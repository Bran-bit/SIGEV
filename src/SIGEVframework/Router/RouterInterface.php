<?php

namespace SIGEVframework\Router;

interface RouterInterface 
{
    /**
     * Adiciona um objeto rota à tabela de rotas.
     * 
     * @param string $router
     * @param array $params
     * @return void
     */
    public function add(string $router, array $params = []) : void;

    /**
     * A partir da URL, despacha a rota e chama o controlador
     * responsável pela ação referente à tal rota.
     * 
     * @param string $url
     * @return void 
     */
    public function dispatch(string $url): void;
}