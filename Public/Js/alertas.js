/**
 ### Exibe o alerta com mensagem customizada
 *- Icones => success, error, warning, info, question
 *- titulo => Titulo da janela
 *- mensagem => Escreva a mensagem a ser exibida
 *- icone => escolha um Icon acima
 *- confirmButton => Texto do Botão
*/
function alertaPersonalizado(titulo, mensagem, icone, confirmButton) {
  Swal.fire({
    icon: icone,
    title: titulo,
    text: mensagem,
    confirmButtonText: confirmButton,
    confirmButtonColor: '#5f5f5f',
    showCancelButton: true,
    cancelButtonColor: "#d33",
    background: '#f9f9f9',
    color: '#333'
  });
}

/**
 ### Exibe o alerta TOAST com mensagem customizada
 *- Icon => success, error, warning, info, question
 *- mensagem => Escreva a mensagem a ser exibida
 *- icone => escolha um Icon acima
 *- posicao => top, top-start, top-end, center, center-start, center-end, bottom, bottom-start, bottom-end
*/
function toastAlert(mensagem, icone, posicao)
{
  const Toast = Swal.mixin({
    toast: true,
    position: posicao,
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    }
  });
  Toast.fire({
    icon: icone,
    title: mensagem
  });
}
