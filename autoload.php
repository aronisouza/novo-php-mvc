<?php
spl_autoload_register(function ($nomeClasse) {
    $caminhos = [
        'Configs/',
        'Controllers/',
        'Core/',
        'Models/'
    ];

    foreach ($caminhos as $pasta) {
        $pastaArquivo = __DIR__ . '/' . $pasta . $nomeClasse . '.php';
        if (file_exists($pastaArquivo)) {
            require_once $pastaArquivo;
            break;
        }
    }
});