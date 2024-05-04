document.addEventListener("DOMContentLoaded", function () {
  // Aguarde o DOM ser completamente carregado

  // Obtenha uma referência ao botão
  var newChatButton = document.getElementById("newChatButton");

  // Adicione um ouvinte de eventos para o clique no botão
  newChatButton.addEventListener("click", function (event) {
    // Previna o comportamento padrão do link
    event.preventDefault();

    // Realize uma requisição AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", window.location.href, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Enviar os dados
    xhr.send("newChatButton=true");

    // Lidar com a resposta da requisição
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        // Exibir a resposta do servidor (pode ser uma mensagem de sucesso, por exemplo)
        console.log(xhr.responseText);

        // Recarregar a página após desativar
        location.reload(true);
      }
    };
  });
});
