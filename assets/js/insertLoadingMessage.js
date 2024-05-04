// Funções auxiliares
function escapeHtml(text) {
  return text
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

function unescapeHtml(html) {
  var escapeMap = {
    "&amp;": "&",
    "&lt;": "<",
    "&gt;": ">",
    "&quot;": '"',
    "&#039;": "'",
  };
  return html.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function (match) {
    return escapeMap[match];
  });
}

function formatTextCommon(text) {
  return text;
}

function formatResponse(responseText) {
  return responseText.includes("```")
    ? formatCodeText(responseText)
    : formatTextCommon(responseText);
}

function formatCodeText(text) {
  const codeRegex = /<pre><code class="language-(.*?)">(.*?)<\/code><\/pre>/gs;
  let formattedResponse = "";
  let lastIndex = 0;
  let match = null;

  while ((match = codeRegex.exec(text)) !== null) {
    const languageLabel = match[1];
    const codeContent = match[2];

    formattedResponse += unescapeHtml(text.slice(lastIndex, match.index));
    formattedResponse += createCodeBlock(languageLabel, codeContent);
    lastIndex = codeRegex.lastIndex;
  }

  if (lastIndex < text.length) {
    formattedResponse += unescapeHtml(text.slice(lastIndex));
  }

  return formattedResponse;
}

function createCodeBlock(languageLabel, codeContent) {
  const codeBlock = document.createElement("pre");
  codeBlock.classList.add("code-container");

  const code = document.createElement("code");
  code.classList.add(`language-${languageLabel}`);
  code.textContent = codeContent;
  codeBlock.appendChild(code);

  return codeBlock.outerHTML;
}

function insertUserMessage(responseElement, userIconHTML, message, formattedResponse) {
  responseElement.insertAdjacentHTML(
    "beforeend",
    `
        <div style="display: flex; align-items: center;">
            ${userIconHTML}
            <strong style="margin-left: 5px;">Você</strong>
		</div>
        <div style="margin-left: 35px;">
            ${escapeHtml(message)}
        </div>
		<br>
    `
  );
    // Aplica o realce de sintaxe do Prism.js
  Prism.highlightAllUnder(document.getElementById(responseContainerId));
}

function insertBotMessage(responseElement, chatGptIconHTML, formattedResponse) {
  const responseContainerId = "response-" + Date.now();

  responseElement.insertAdjacentHTML(
    "beforeend",
    `
        <div style="display: flex; align-items: center;">
            ${chatGptIconHTML}
            <strong style="margin-left: 5px;">ChatGP</strong>
        </div>
        <div style="margin-left: 35px;" id="${responseContainerId}" class="fade-in-text">
            ${formattedResponse}
        </div>
        <br>
    `
  );

  // Aplica o realce de sintaxe do Prism.js
  Prism.highlightAllUnder(document.getElementById(responseContainerId));

  // Adicionar a classe 'visible' para acionar a animação de opacidade
  setTimeout(() => {
    document.getElementById(responseContainerId).classList.add("visible");
  }, 100); // Ajuste o atraso conforme necessário
}

document
  .getElementById("questionForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    const questionInput = document.getElementById("questionInput");
    const responseElement = document.getElementById("answer");
    const question = questionInput.value.trim();

    if (question === "") {
      alert("Por favor, insira uma pergunta.");
      return;
    }

    const userIconHTML =
      '<img src="../../assets/img/user_icon.png" alt="User Icon" style="width: 25px; height: 25px; vertical-align: middle;">';
    const chatGptIconHTML =
      '<img src="../../assets/img/chatgpt_icon.png" alt="ChatGP Icon" style="width: 30px; height: 30px; vertical-align: middle;">';

    insertUserMessage(responseElement, userIconHTML, question);
    fetchQuestion(question, responseElement, chatGptIconHTML);
    questionInput.value = "";
    removeWelcomeMessage();
  });

function insertLoadingMessage(responseElement, chatGptIconHTML) {
  const loadingMessageHTML = `
        <div class="loading-message" style="display: flex; align-items: center;">
            ${chatGptIconHTML}
            <strong style="margin-left: 5px;">Carregando...</strong>
        </div>
		<br>
    `;

  responseElement.insertAdjacentHTML("beforeend", loadingMessageHTML);
}

function fetchQuestion(question, responseElement, chatGptIconHTML) {
  insertLoadingMessage(responseElement, chatGptIconHTML);

  fetch("process_question.php", {
    method: "POST",
    body: JSON.stringify({ message: question }),
    headers: { "Content-Type": "application/json" },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Falha na comunicação com o servidor");
      }
      return response.json();
    })
    .then((responseData) => {
      responseElement.querySelector(".loading-message").remove();
      const formattedResponse = formatResponse(responseData.formattedResponse);
      insertBotMessage(responseElement, chatGptIconHTML, formattedResponse);
    })
    .catch((error) => {
      console.error("Erro ao enviar a pergunta via AJAX: ", error);
      insertBotMessage(
        responseElement,
        chatGptIconHTML,
        "Ocorreu um erro. Por favor, tente novamente."
      );
    });
}

function removeWelcomeMessage() {
  const welcomeElement = document.getElementById("welcomeMessage");
  if (welcomeElement) {
    welcomeElement.remove();
  }
}
