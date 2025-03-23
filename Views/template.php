<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= getenv('SITE_TITLE'); ?></title>
    <link rel="stylesheet" href="/Public/Css/bootstrap.min.css">
    <link rel="stylesheet" href="/Public/Css/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <link rel="stylesheet" href="/Public/Css/base.css">
    <script src="/Public/Js/sweetalert2.js" nonce="<?= $_SESSION['csp_nonce']; ?>"></script>
    <script src="/Public/Js/alertas.js"  nonce="<?= $_SESSION['csp_nonce']; ?>"></script>
</head>
<body class="bg-base-site">
    <?php include __DIR__ . '/components/navbar.php'; ?>
    <?php displayErrorMessage(); ?>
    <?php displaySuccessMessage(); ?>
    <div class="container">
        <?php require_once $content; ?>
    </div>
    <script src="/Public/Js/jquery-3.6.4.min.js"  nonce="<?= $_SESSION['csp_nonce']; ?>"></script>
    <script src="/Public/Js/bootstrap.bundle.min.js"  nonce="<?= $_SESSION['csp_nonce']; ?>"></script>
    <script src="/Public/Js/Chartjs-v4.4.7.js"  nonce="<?= $_SESSION['csp_nonce']; ?>"></script>
</body>
</html>