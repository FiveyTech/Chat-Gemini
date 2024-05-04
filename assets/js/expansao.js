const textarea = document.getElementById("questionInput");
const form = document.getElementById("questionForm");

textarea.addEventListener("input", function() {
  // Salve a altura original do textarea
  const originalHeight = this.clientHeight;

  // Defina a altura do textarea para 1px para obter a altura de scrollHeight correta
  this.style.height = "1px";
  
  // Ajuste a altura com base no scrollHeight (evita que o textarea se expanda para baixo)
  this.style.height = Math.min(this.scrollHeight, 200) + "px";

  // Obtenha a altura ajustada do textarea
  const adjustedHeight = this.clientHeight;

  // Calcule a diferença entre a altura original e a altura ajustada
  const heightDifference = adjustedHeight - originalHeight;

  // Ajuste o scroll do formulário para cima ou para baixo
  form.style.marginBottom = heightDifference + "px";
});

form.addEventListener("submit", function(event) {
  // Código de envio do formulário

  // Redefina a altura do campo de texto e o margin do formulário após o envio
  textarea.style.height = "";
  form.style.marginBottom = "";
});
