<nav class="navbar navbar-expand-lg navbar-light bg-nav-bar">
  <div class="container-md">
    <a class="navbar-brand" href="/"><?= getenv("SITE_CIGLA"); ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="/users">Usuário</a>
        </li>
      </ul>

      <div class="d-flex">
        <div class="btn-group">
          <button type="button" class="nav-link dropdown-toggle d-inline-flex align-items-center justify-content-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
            <?= fldIco("person", 24, "text-dark"); ?> Entrar
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><button class="dropdown-item" type="button">Perfil</button></li>
            <li><button class="dropdown-item" type="button">Plano Alimentar</button></li>
            <li><hr class="dropdown-divider"></li>
            <li><button class="dropdown-item" type="button">Sair</button></li>
          </ul>
        </div>
      </div>
      
    </div>
  </div>
</nav>