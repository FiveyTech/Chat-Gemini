document.addEventListener("DOMContentLoaded", function () {
  // Aguarde o DOM ser completamente carregado

  // Obtenha uma referência à barra lateral
  var sidebar = document.querySelector(".sidebar");

  // Adicione um ouvinte de eventos à barra lateral
  sidebar.addEventListener("click", function (event) {
    // Verifique se o elemento clicado é um link com a classe 'sidebar__link'
    if (event.target.classList.contains("activate-historical")) {
      // Previna o comportamento padrão do link
      event.preventDefault();

      // Obtenha o nome do histórico do atributo de dados
      var historicalName = event.target.dataset.historicalName;

      // Realize uma requisição AJAX
      var xhr = new XMLHttpRequest();
      xhr.open("POST", window.location.href, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      // Enviar os dados, incluindo o nome do histórico
      xhr.send("activateHistorical=true&historicalName=" + encodeURIComponent(historicalName));

      // Lidar com a resposta da requisição
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Exibir a resposta do servidor (pode ser uma mensagem de sucesso, por exemplo)
          console.log(xhr.responseText);

          // Atualizar a página após ativar o histórico
          location.reload(true);
        }
      };
    }
  });
});
