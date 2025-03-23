<?php

class Router {
    private $routes = [];
    private $basePath = '';

    public function __construct($basePath = '') {
        $this->basePath = rtrim($basePath, '/');
    }

    public function addRoute($method, $uri, $controller, $action) { 
        
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch() {

        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Obtém a URI da query string ou define como '/'
        $requestUri = isset($_GET['url']) ? $_GET['url'] : '/';
        $requestUri = strtok($requestUri, '?'); // Remove query string

        // Adiciona uma barra inicial se não houver
        if (strpos($requestUri, '/') !== 0) {
            $requestUri = '/' . $requestUri;
        }

        // Remove o caminho base da URI
        $requestUri = $requestUri;
        if ($this->basePath && strpos($requestUri, $this->basePath) === 0) {
            $requestUri = substr($requestUri, strlen($this->basePath));
        }

        // Normaliza a URI removendo barras finais
        $requestUri = rtrim($requestUri, '/');

        foreach ($this->routes as $route) {
            // Verifica se a URI registrada contém parâmetros dinâmicos
            if (strpos($route['uri'], '{') !== false) {
                // Converte a URI registrada em uma expressão regular para capturar parâmetros
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['uri']);
                $pattern = "@^" . $pattern . "$@D";

                if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                    $controllerName = $route['controller'];
                    $actionName = $route['action'];

                    // Remove índices numéricos dos matches
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                    // Cria a instância do controlador e chama a ação com os parâmetros
                    $controller = new $controllerName();
                    call_user_func_array([$controller, $actionName], array_values($params));
                    return;
                }
            } else {
                // Normaliza a URI registrada
                $routeUri = rtrim($route['uri'], '/');

                if ($route['method'] === $requestMethod && $routeUri === $requestUri) {
                    $controllerName = $route['controller'];
                    $actionName = $route['action'];

                    $controller = new $controllerName();
                    $controller->$actionName();
                    return;
                }
            }
        }

        // Rota não encontrada
        http_response_code(404);
        require_once __DIR__ . '/../Views/errors/404.php';
        exit;
    }
}