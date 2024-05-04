function displayWelcomeMessage() {
  // Cria o elemento h4 para o título
  const welcomeTitle = document.createElement("h4");
  welcomeTitle.textContent = "Como posso ajudá-lo hoje?";

  // Cria o elemento img para o ícone
  const icon = document.createElement("img");
  icon.src = "../../assets/img/chatgpt_icon.png";
  icon.alt = "Ícone";
  icon.style.width = "150px";
  icon.style.height = "150px";
  icon.classList.add("glow"); // Aplica o efeito de brilho

  // Cria o container para os elementos
  const welcomeElement = document.createElement("div");
  welcomeElement.id = "welcomeMessage"; // Adiciona um ID único ao elemento de boas-vindas
  welcomeElement.style.display = "flex";
  welcomeElement.style.flexDirection = "column";
  welcomeElement.style.alignItems = "center";
  welcomeElement.style.justifyContent = "center";

  // Adiciona os elementos ao container
  welcomeElement.appendChild(icon);
  welcomeElement.appendChild(document.createElement("br"));
  welcomeElement.appendChild(welcomeTitle);

  // Insere o container na página
  const responseElement = document.getElementById("answer");
  responseElement.insertAdjacentElement("afterbegin", welcomeElement);
}

document.addEventListener("DOMContentLoaded", displayWelcomeMessage);
