# Filid-MVC

Filid-MVC é um framework PHP simples e eficiente. Foi desenvolvido para facilitar o desenvolvimento de aplicações web, oferecendo uma estrutura organizada e fácil de entender.

## Estrutura do Projeto:
É um projeto PHP seguindo o padrão MVC (Model-View-Controller)<br>
Possui uma estrutura organizada com diretórios separados para Models, Views, Controllers, Core e Configs<br>
Utiliza um sistema de autoload para carregamento automático de classes

## Funcionalidades Principais:
Sistema de gerenciamento de usuários (UserController)<br>
Página inicial (HomeController)<br>
Sistema de rotas e gerenciamento de URLs<br>
Configuração via arquivo .env para variáveis de ambiente<br>

## Tecnologias:
PHP 7.4 ou superior<br>
MySQL/MariaDB como banco de dados<br>
Apache/Nginx como servidor web<br>
HTML, CSS e JavaScript para frontend<br>

## Organização:
Separação clara de responsabilidades (MVC)<br>
Sistema de logs para monitoramento<br>
Arquivos de configuração separados<br>
Sistema de funções auxiliares (functions.php)<br>

## 🔒 Segurança:
Utilização de variáveis de ambiente (.env)<br>
Sistema de autenticação implementado<br>
Tratamento de URLs inválidas<br>

## 📋 Índice

- [Visão Geral](#visão-geral)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Requisitos](#requisitos)
- [Instalação](#instalação)
- [Configuração](#configuração)
- [Uso](#uso)
- [Exemplos](#exemplos)
- [Licença](#licença)

## 🌟 Visão Geral

O Filid-MVC é um framework que implementa o padrão MVC, dividindo a aplicação em três camadas principais:

- **Model**: Responsável pela lógica de negócios e interação com o banco de dados
- **View**: Interface do usuário, onde os dados são exibidos
- **Controller**: Gerencia as requisições entre a View e o Model

## 📁 Estrutura do Projeto

```
filidmvc/
├── Configs/           # Arquivos de configuração
├── Controllers/       # Controladores da aplicação
├── Core/             # Classes principais do framework
├── Models/           # Modelos e lógica de negócios
├── Public/           # Arquivos públicos (CSS, JS, imagens)
├── Views/            # Arquivos de visualização
│   ├── Components/   # Componentes reutilizáveis
│   └── errors/       # Páginas de erro
├── .env              # Variáveis de ambiente
├── .htaccess        # Configurações do Apache
├── autoload.php     # Carregador automático de classes
├── functions.php    # Funções auxiliares
└── index.php        # Ponto de entrada da aplicação
```

## ⚙️ Requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Apache/Nginx
- mod_rewrite habilitado (Apache)
- Composer (opcional)

## 🚀 Instalação

1. Clone o repositório:
```bash
git clone https://github.com/seu-usuario/filid-mvc.git
cd filid-mvc
```

2. Configure seu servidor web (Apache/Nginx) para apontar para a pasta do projeto

3. Copie o arquivo de exemplo de ambiente:
```bash
cp .env.example .env
```

4. Configure as variáveis de ambiente no arquivo `.env`:
```env
DB_HOST=localhost
DB_NAME=seu_banco
DB_USER=seu_usuario
DB_PASS=sua_senha
```

## ⚙️ Configuração

### Configuração do Banco de Dados

Apenas adicione as suas informações no .env
```env
DB_HOST=localhost
DB_NAME=seu_banco
DB_USER=seu_usuario
DB_PASS=sua_senha
```

### Configuração do Apache (.htaccess)

```apache
RewriteEngine On
Options All -Indexes

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

## 💻 Uso

### Criando um Controller

```php
<?php
// Controllers/UserController.php

class UserController extends Controller
{
    public function index()
    {
        // Lógica para listar usuários
        $this->render('users/index');
    }

    public function create()
    {
        // Lógica para criar usuário
        $this->render('users/create');
    }
}
```

### Criando um Model

```php
<?php
// Models/User.php

class User
{
    public function getUserById($id)
    {
        $read = new Read();
        $read->ExeRead('users', "WHERE id={$id}");
        return $read->getResult();
    }
}
```

### Criando uma View

```php
<!-- Views/users/index.php -->
<div class="container">
    <h1>Lista de Usuários</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['nome']; ?></td>
                <td><?php echo $user['email']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
```

### Definindo Rotas

As rotas podem ser definidas da seguinte maneiras:

#### 1. Usando arquivo de configuração

Arquivo `Configs/routes.php`:

```php
<?php
return [
    // Rotas básicas
    ['GET', '/', 'HomeController', 'index'],
    ['GET', '/users', 'UserController', 'index'],

    // Rotas com parâmetros
    ['GET', '/users/edit/{id}', 'UserController', 'edit'],
    ['POST', '/users/edit/{id}', 'UserController', 'update'],

    // Rotas para criação
    ['GET', '/users/create', 'UserController', 'create'],
    ['POST', '/users/create', 'UserController', 'store'],

    // Rota para deletar
    ['GET', '/users/delete/{id}', 'UserController', 'delete'],
];
```

## 🔒 Segurança

O framework inclui várias medidas de segurança:

- Proteção contra CSRF
- Headers de segurança configurados
- Validação de dados
- Escape de saída HTML

### Exemplo de Proteção CSRF

```php
// No formulário
<form method="POST" action="/users/create">
    <input type="hidden" name="csrf_token" value="<?php echo $this->generateCsrfToken(); ?>">
    <!-- campos do formulário -->
</form>

// No controller
public function store()
{
    if ($this->validateCsrfToken($_POST['csrf_token'])) {
        // Processar dados
    }
}
```

## 🎨 Personalização

### Adicionando CSS e JavaScript

```php
<!-- Views/template.php -->
<head>
    <!-- CSS -->
    <link rel="stylesheet" href="/Public/Css/bootstrap.min.css">
    <link rel="stylesheet" href="/Public/Css/custom.css">
    
    <!-- JavaScript -->
    <script src="/Public/Js/jquery-3.6.4.min.js"></script>
    <script src="/Public/Js/bootstrap.bundle.min.js"></script>
</head>
```

## 📄 Licença

Este projeto está licenciado sob a MIT License - veja o arquivo [LICENSE](LICENSE) para detalhes.

## 📞 Suporte

Se você encontrar algum problema ou tiver sugestões, por favor abra uma issue no GitHub.
