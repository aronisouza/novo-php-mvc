<?php

class UserController extends Controller
{
    public function index()
    {
        // Lista todos os usuários
        $userModel = new UserModel();
        $users = $userModel->getAllUsers();

        $this->render('users/index', ['users' => $users]);
    }

    public function create()
    {
        // Mostra o formulário para criar um novo usuário
        $this->render('users/create');
    }

    public function store()
    {
        // Valida o token CSRF
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->setErrorAndRedirect(
                "Requisição inválida. Token CSRF inválido.",
                "/users/create",
                "Erro de Segurança",
                "error"
            );
            return;
        }

        if (empty($_POST['name']) || empty($_POST['email'])) {
            $this->setErrorAndRedirect(
                "Todos os campos são obrigatórios.",
                "/users/create",
                "Erro de Validação",
                "warning"
            );
            return;
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $this->setErrorAndRedirect(
                "Email inválido. Por favor, informe um email válido.",
                "/users/create",
                "Erro de Validação",
                "warning"
            );
            return;
        }

        // Remove o campo csrf_token dos dados
        unset($_POST['csrf_token']);

        $_POST['password'] = fldCrip($_POST['password'], 0);
        $data = $_POST;
        $userModel = new UserModel();
        $result = $userModel->createUser($data);
        
        if ($result) {
            $this->setSuccessAndRedirect(
                "Usuário criado com sucesso!",
                "/users"
            );
        } else {
            $this->setErrorAndRedirect(
                "Erro ao criar usuário. Por favor, tente novamente.",
                "/users/create",
                "Erro no Sistema",
                "error"
            );
        }
    }

    public function edit($idg)
    {
        $id = fldCrip($idg, 1);
        // Recupera o usuário pelo ID
        $userModel = new UserModel();
        $user = $userModel->getUserById($id);

        if (!$user) {
            http_response_code(404);
            $this->setErrorAndRedirect(
                "Usuário não encontrado.",
                "/users",
                "Erro de Validação",
                "error"
            );
            return;
        }

        // Passa o usuário para a view
        $this->render('users/edit', ['user' => $user]);
    }

    public function update($idg)
    {
        // Valida o token CSRF
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            $this->setErrorAndRedirect(
                "Requisição inválida. Token CSRF inválido.",
                "/users",
                "Erro de Segurança",
                "error"
            );
            return;
        }

        if (empty($_POST['name']) || empty($_POST['email'])) {
            http_response_code(400);
            $this->setErrorAndRedirect(
                "Todos os campos são obrigatórios.",
                "/users",
                "Erro de Validação",
                "error"
            );
            return;
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            $this->setErrorAndRedirect(
                "Email inválido.",
                "/users",
                "Erro de Validação",
                "error"
            );
            return;
        }

        // Remove o campo csrf_token dos dados
        unset($_POST['csrf_token']);
        $id = fldCrip($idg, 1);
        // Atualiza um usuário no banco de dados
        $data = $_POST;
        $userModel = new UserModel();
        $result = $userModel->updateUser($id, $data);
        
        if ($result) {
            $this->setSuccessAndRedirect(
                "Usuário atualizado com sucesso!",
                "/users"
            );
        } else {
            $this->setErrorAndRedirect(
                "Erro ao atualizar usuário. Por favor, tente novamente.",
                "/users",
                "Erro no Sistema",
                "error"
            );
        }
    }

    public function delete($idg)
    {
        $id = fldCrip($idg, 1);
        // Exclui um usuário do banco de dados
        $userModel = new UserModel();
        $result = $userModel->deleteUser($id);
        
        if ($result) {
            $this->setSuccessAndRedirect(
                "Usuário excluído com sucesso!",
                "/users"
            );
        } else {
            $this->setErrorAndRedirect(
                "Erro ao excluir usuário. Por favor, tente novamente.",
                "/users",
                "Erro no Sistema",
                "error"
            );
        }
    }

}
