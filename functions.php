<?php

function loadEnv($path = '.env')
{
  if (!file_exists($path)) {
    throw new Exception("Arquivo .env não encontrado em {$path}");
  }

  $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) {
      continue; // Ignora comentários
    }

    list($name, $value) = explode('=', $line, 2);
    $name = trim($name);
    $value = trim($value);

    if (!empty($name)) {
      putenv("{$name}={$value}");
    }
  }
}

function siteUrl()
{
  return getenv('SITE_URL');
}


function logError($message)
{
  error_log("[ERRO] {$message}\r", 3, __DIR__ . "/logs/error.log");
}

// Apenas para verificar alguns arrays formatado
function fldPre($string)
{
  echo '<pre>';
  print_r($string);
  echo '</pre>';
}

function fldPreDie($string)
{
  echo '<pre>';
  print_r($string);
  echo '</pre>';
  die;
}

/**
 * TIPOS:
 *- primary, secondary, success, danger, warning, info, light e dark 
 */
function fldMessage($tipo, $text)
{
  echo "
  <div class='alert alert-{$tipo} mx-5 alert-dismissible fade show mt-3' role='alert'>
    <p>
      {$text}
    </p>
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
  </div>
  ";
}

/**
 *- $tipo : 0 -> criptografa
 *- $tipo : 1 -> descriptografa
 *- $caracter : Texto/Número
 */
function fldCrip($caracter, $tipo)
{
  $key = 'FiliD_Danela-Gatins';
  $iv = '2500910555066936';
  $method = 'AES-256-CBC';
  if ($tipo == 0) {
    $encrypted = openssl_encrypt($caracter, $method, $key, 0, $iv);
    $safeEncrypted = strtr(base64_encode($encrypted), '+/', '-_');
    return $safeEncrypted;
  } elseif ($tipo == 1) {
    $decoded = base64_decode(strtr($caracter, '-_', '+/'));
    $decrypted = openssl_decrypt($decoded, $method, $key, 0, $iv);
    return $decrypted;
  }
}

function fldMesBrasil($date)
{
  $meses = [
    '01' => 'Janeiro',
    '02' => 'Fevereiro',
    '03' => 'Março',
    '04' => 'Abril',
    '05' => 'Maio',
    '06' => 'Junho',
    '07' => 'Julho',
    '08' => 'Agosto',
    '09' => 'Setembro',
    '10' => 'Outubro',
    '11' => 'Novembro',
    '12' => 'Dezembro'
  ];
  return $meses[$date];
}

/**
 *- Transformar uma data 2025-11-25
 *- Em 25 Nov, 2025
 */
function fldDateExtenso($date)
{
  $timestamp = strtotime($date);
  $dia = date('d', $timestamp);
  $mes = date('m', $timestamp);
  $ano = date('Y', $timestamp);
  return "{$dia} de " . fldMesBrasil($mes) . ", {$ano}";
}

/**
 *- Transformar texto tipo -> Cartão IT Mozão em cartaoitmozao
 *- $texto -> texto a ser modificado
 */
function fldTirarAcento($texto)
{
  // Normaliza a string para remover acentos
  $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);

  // Remover espaços e caracteres especiais
  $texto = preg_replace('/[^a-zA-Z0-9]/', '', $texto);

  // Transformar em minúsculas
  return strtolower($texto);
}

/**
 * TIPOS: primary, secondary, success, danger, warning, info, light e dark 
 */
function fldCard($tipo, $header, $title, $text)
{
  echo "
    <div class='card text-bg-{$tipo} mt-3' style='max-width: 100%;'>
      <div class='card-header'>{$header}</div>
      <div class='card-body'>
        <h5 class='card-title'>{$title}</h5>
        <p class='card-text'>{$text}</p>
      </div>
    </div>
  ";
}

function generateCsrfToken() {
  if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

/**
 * 
 * - $iconName: checar o nome do icone no site https://fonts.google.com/icons
 * - $size: ... 24, 36, 48 ... ,
 * - $cor: text-primary text-secondary text-success, text-danger, text-warning, text-info, text-light, text-dark
 * - Usar em Botão => class: d-inline-flex align-items-center justify-content-center gap-2
 */
function fldIco($iconName, $size, $cor)
{
  // Validar o tamanho (opcional)
  if (!is_numeric($size) || $size <= 0) {
    throw new InvalidArgumentException("O tamanho do icone deve ser um número positivo.");
  }

  // Gerar o HTML do ícone com o tamanho especificado
  $html = sprintf(
    '<i class="material-symbols-outlined %s" style="font-size: %dpx;">%s</i>',
    $cor,
    $size,
    htmlspecialchars($iconName, ENT_QUOTES, 'UTF-8')
  );

  return $html;
}

/**
 * Define uma mensagem de erro para ser exibida na próxima requisição
 * 
 * @param string $mensagem Mensagem de erro
 * @param string $titulo Título do erro (opcional)
 * @param string $icone Ícone do erro (opcional) - success, error, warning, info, question
 * @return void
 */
function setErrorMessage($mensagem, $titulo = 'Erro', $icone = 'error')
{
  $_SESSION['error_message'] = [
    'mensagem' => $mensagem,
    'titulo' => $titulo,
    'icone' => $icone
  ];
}

/**
 * Define uma mensagem de sucesso para ser exibida na próxima requisição
 * 
 * @param string $mensagem Mensagem de sucesso
 * @param string $titulo Título da mensagem (opcional)
 * @return void
 */
function setSuccessMessage($mensagem, $titulo = 'Sucesso')
{
  $_SESSION['success_message'] = [
    'mensagem' => $mensagem,
    'titulo' => $titulo
  ];
}

/**
 * Exibe a mensagem de erro se existir e a remove da sessão
 * 
 * @return void
 */
function displayErrorMessage()
{
  if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    fldAlertaPersonalizado(
      $error['titulo'],
      $error['mensagem'],
      $error['icone'],
      'OK'
    );
    unset($_SESSION['error_message']);
  }
}

/**
 * Exibe a mensagem de sucesso como Toast se existir e a remove da sessão
 * 
 * @return void
 */
function displaySuccessMessage()
{
  if (isset($_SESSION['success_message'])) {
    $success = $_SESSION['success_message'];
    fldToastAlert(
      $success['mensagem'],
      'success',
      'center'
    );
    unset($_SESSION['success_message']);
  }
}

/**
 ** Exibe o alerta com mensagem customizada
 *- Icones => success, error, warning, info, question
 *- titulo => Titulo da janela
 *- mensagem => Escreva a mensagem a ser exibida
 *- icone => escolha um Icon acima
 *- confirmButton => Texto do Botão
 */
function fldAlertaPersonalizado($titulo, $mensagem, $icone, $confirmButton)
{
  echo "
  <script>
    Swal.fire({
      icon: '$icone',
      title: '$titulo',
      text: '$mensagem',
      confirmButtonText: '$confirmButton',
      confirmButtonColor: '#5f5f5f',
      background: '#f9f9f9',
      color: '#333'
    });
  </script>
  ";
}

/**
 ** Exibe o alerta TOAST com mensagem customizada
 *- Icon => success, error, warning, info, question
 *- mensagem => Escreva a mensagem a ser exibida
 *- icone => escolha um Icon acima
 *- posicao => top, top-start, top-end, center, center-start, center-end, bottom, bottom-start, bottom-end
 */
function fldToastAlert($mensagem, $icone, $posicao)
{
  echo "
  <script>
    const Toast = Swal.mixin({
      toast: true,
      position: '$posicao',
      showConfirmButton: false,
      timer: 3500,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });
    Toast.fire({
      icon: '$icone',
      title: '$mensagem'
    });
  </script>
  ";
}
