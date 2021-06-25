<?php

namespace SIGEVframework\Router;

use SIGEVframework\Router\Exception\RouterBadMethodCallException;
use SIGEVframework\Router\Exception\RouterException;
use SIGEVframework\Router\RouterInterface;

class Router implements RouterInterface
{
    use \SIGEVframework\Utilitarios\formatStringTrait;
    /**
     * Armazena um array da rota, vinda da tabela de rotas.
     * @var array
     */
    protected array $routes = [];

    /**
     * Armazena um vetor com os parâmetros da rota.
     * @var array
     */
    protected array $params = [];

    /**
     * Adiciona um sufixo ao nome dos controladores.
     * @var string
     */
    protected string $controllerSufix = 'controller';

    /**
     * @inheritDoc
     */
    public function add(string $route, array $params = []): void
    {
        $this->routes[$route] = $params;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(string $url): void
    {
        //TO-DO: acho melhor o lançamento de exceções primeiro, fica menos bagunçado
        if ($this->match($url)) {
            $controllerString = $this->params['controller'];
            $controllerString = $this->transformClassPattern($controllerString);
            $controllerString = $this->getNamespace($controllerString);

            if (class_exists($controllerString)) {
                $controller = new $controllerString();
                $action = $this->params['action'];
                $action = $this->transformMethodPattern($action);

                //TO-DO: ver como frameworks lidam com essas exceções
                if (\is_callable([$controller, $action])) {
                    $controller->action();
                } else {
                    throw new RouterBadMethodCallException();
                }
                //Se a classe não existe
            } else {
                throw new RouterException();
            }
        } else {
            throw new RouterException();
        }

        
        }
    

    /**
     * Verifica se há uma correspondência (match) entre
     * a rota informada pela URL com a tabela de rotas.
     * Em caso positivo, a propriedade params recebe os parâmetros da url.
     * 
     * @param string $url
     * @return bool
     */
    private function match(string $url): bool
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $param) {
                    if (is_string($key)) {
                        $params[$key] = $param;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Retorna um namespace para a classe do controller,
     * caso ele tenha sido informado nos parâmetros da rota.
     * 
     * @param string $string
     * @return string
     */
    public function getNamespace(string $string): string
    {
        //TO-DO: resgar esse diretório por um .env
        $namespace = 'App\Controller\\';
        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }
        return $namespace;
    }
}
