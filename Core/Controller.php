<?php

class Controller {

    protected function render($view, $data = []) {
        // Extrai os dados para variáveis no escopo local
        extract($data);

        // Define o caminho para a view
        $viewPath = __DIR__ . "/../Views/{$view}.php";

        // Verifica se a view existe
        if (file_exists($viewPath)) {
            // Define a variável $content com o caminho da view
            $content = $viewPath;
            // Carrega o template
            require_once __DIR__ . "/../Views/template.php";
        } else {
            // Se a view não existir, carrega a página de erro 404
            require_once __DIR__ . "/../Views/errors/404.php";
        }
    }

    protected function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    protected function validateCsrfToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Define uma mensagem de erro e redireciona para a URL fornecida
     * 
     * @param string $mensagem Mensagem de erro
     * @param string $redirectUrl URL para redirecionamento
     * @param string $titulo Título do erro (opcional)
     * @param string $icone Ícone do erro (opcional)
     * @return void
     */
    protected static function setErrorAndRedirect($mensagem, $redirectUrl, $titulo = 'Erro', $icone = 'error') {
        setErrorMessage($mensagem, $titulo, $icone);
        header("Location: {$redirectUrl}");
        exit;
    }
    
    /**
     * Define uma mensagem de sucesso e redireciona para a URL fornecida
     * 
     * @param string $mensagem Mensagem de sucesso
     * @param string $redirectUrl URL para redirecionamento
     * @param string $titulo Título da mensagem (opcional)
     * @return void
     */
    protected function setSuccessAndRedirect($mensagem, $redirectUrl, $titulo = 'Sucesso') {
        setSuccessMessage($mensagem, $titulo);
        header("Location: {$redirectUrl}");
        exit;
    }
    
}