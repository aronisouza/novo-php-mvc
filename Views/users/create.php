<h4 class="mt-4">Editar Usuário</h4>
<form class="form-floating" method="POST" action="/users/create">
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken(); ?>">
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="name" name="name" placeholder="nome" >
        <label for="name">Nome:</label>
    </div>
    <div class="form-floating mb-3">
        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" >
        <label for="email">Email:</label>
    </div>

    <div class="form-floating mb-3">
        <input type="password" class="form-control" id="password" name="password" >
        <label for="password">Senha:</label>
    </div>

    <button type="submit" class="btn btn-warning">Criar</button>

</form>