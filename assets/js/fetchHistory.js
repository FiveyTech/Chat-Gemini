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

function insertUserMessage(responseElement, userIconHTML, message) {
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
        <div style="margin-left: 35px;" id="${responseContainerId}">
            ${formattedResponse}
        </div>
        <br>
    `
  );

  Prism.highlightAllUnder(document.getElementById(responseContainerId));
}

function fetchHistory(responseElement) {
  const userIconHTML = '<img src="./assets/img/user_icon.png" alt="User Icon" style="width: 25px; height: 25px; vertical-align: middle;">';
  const chatGptIconHTML = '<img src="../../assets/img/chatgpt_icon.png" alt="ChatGP Icon" style="width: 30px; height: 30px; vertical-align: middle;">';

  fetch("fetch_history.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Falha na comunicação com o servidor");
      }
      return response.json();
    })
    .then((historyData) => {
      if (historyData && historyData.length > 0) {
        const historicoString = historyData[0].historico;
        const historicoObj = JSON.parse(historicoString);

        removeWelcomeMessage();

        historicoObj.forEach((entry) => {
          if (entry.role === "user") {
            insertUserMessage(responseElement, userIconHTML, entry.text);
          } else if (entry.role === "model") {
            const formattedEntry = formatResponse(entry.text);
            insertBotMessage(responseElement, chatGptIconHTML, formattedEntry);
          }
        });
      } else {
        return;
      }
    })
    .catch((error) => {
      console.error("Erro ao recuperar o histórico via AJAX: ", error);
      insertBotMessage(
        responseElement,
        chatGptIconHTML,
        "Ocorreu um erro ao recuperar o histórico. Por favor, tente novamente."
      );
    });
}

function removeWelcomeMessage() {
  const welcomeElement = document.getElementById("welcomeMessage");
  if (welcomeElement) {
    welcomeElement.remove();
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const responseElement = document.getElementById("answer");
  fetchHistory(responseElement);
  // Aguarde um breve momento antes de chamar Prism.highlightAllUnder
  setTimeout(() => {
    Prism.highlightAllUnder(responseElement);
  }, 100);
});
