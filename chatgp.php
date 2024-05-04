<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();

if (!isset($_SESSION['iduser']) || $_SESSION['nivel'] < 2) {
    echo "<script> window.location.href='./logout.php'; </script>";
    exit();
}
if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
    echo "<script> window.location.href = './logout.php'; </script>";
    exit();
}

function conectarDB($server, $user, $pass, $db)
{
    try {
        $conexao = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit();
    }
}

include 'dbconfig/db.php';
$conexao = conectarDB($server, $user, $pass, $db);

try {
    // Preparando e executando a consulta SQL
    $stmt = $conexao->prepare("SELECT nome FROM historico");
    $stmt->execute();

    // Obtendo os resultados
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Exibindo os nomes do histórico
    foreach ($resultados as $resultado) {
        $nome = $resultado['nome'];
    }
} catch (PDOException $e) {
    echo 'Erro ao buscar dados do histórico: ' . $e->getMessage();
}

// Verifica se o botão foi clicado
if (isset($_POST['newChatButton'])) {
    try {
        // Atualiza o status para 'desativado'
        $stmt = $conexao->prepare("UPDATE historico SET status = 'desativado' WHERE byid = :iduser AND status = 'ativo'");
        $stmt->bindParam(':iduser', $_SESSION['iduser']);
        $stmt->execute();

        // Resposta para o cliente (pode ser uma mensagem de sucesso)
        echo 'Chat desativado com sucesso!';
    } catch (PDOException $e) {
        // Em caso de erro, enviar uma resposta de erro
        echo 'Erro: ' . $e->getMessage();
    }
}

if (isset($_POST['activateChatButton'])) {
    try {
        // Obtenha o nome do histórico a ser ativado
        $historicalNameToActivate = $_POST['historicalName'];

        // Inicie uma transação para garantir consistência
        $conexao->beginTransaction();

        // Desative todos os históricos ativos do usuário
        $stmtDisableAll = $conexao->prepare("UPDATE historico SET status = 'desativado' WHERE byid = :iduser AND status = 'ativo'");
        $stmtDisableAll->bindParam(':iduser', $_SESSION['iduser']);
        $stmtDisableAll->execute();

        // Atualize o status para 'ativo' no histórico escolhido
        $stmtActivate = $conexao->prepare("UPDATE historico SET status = 'ativo' WHERE byid = :iduser AND nome = :historicalName");
        $stmtActivate->bindParam(':iduser', $_SESSION['iduser']);
        $stmtActivate->bindParam(':historicalName', $historicalNameToActivate);
        $stmtActivate->execute();

        // Confirme a transação se tudo ocorrer bem
        $conexao->commit();

        // Resposta para o cliente (pode ser uma mensagem de sucesso)
        echo 'Histórico ativado com sucesso!';
    } catch (PDOException $e) {
        // Em caso de erro, desfaça a transação e envie uma resposta de erro
        $conexao->rollBack();
        echo 'Erro: ' . $e->getMessage();
    }
}


?>


<html lang="pt-br" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Chat GP</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="./assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="./assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="./assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="./assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="./assets/css/global.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="./assets/vendor/css/pages/page-auth.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.13/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/vendor/js/helpers.js"></script>
    <script src="./assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <?php include './menu.php'; ?>

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <style>
                    .textarea-container {
                        position: relative;
                    }

                    .inside-textarea {
                        position: absolute;
                        right: 10px;
                        bottom: 10px;
                        transition: all 0.3s ease-in-out;
                    }

                    .form-control {
                        resize: none;
                    }

                    .form-control:focus {
                        box-shadow: none;
                    }

                    pre {
                        /* Estilo geral do elemento pre */
                        background-color: #f8fafd;
                        border: 1px solid #ddd;
                        color: #666;
                        padding: 1em 1.5em;
                        margin: 1em 0;
                        box-shadow: 2px 1px 30px 2px rgba(20, 20, 20, 0.3);

                        /* Fonte */
                        font-family: monospace;
                        font-size: 12px;
                        line-height: 1.6;
                    }

                    code {
                        font-family: monospace, monospace;
                        display: block;
                        overflow-x: auto;
                    }

                    .container-p-y:not([class^=pt-]):not([class*=" pt-"]) {
                        padding-top: 2.625rem !important;
                    }



                    /* Estilo para a barra de ferramentas */
                    .toolbar {
                        position: relative;
                        display: flex;
                        justify-content: space-between;
                        margin-top: 5px;
                    }

                    /* Estilo para os itens da barra de ferramentas */
                    .toolbar-item {
                        margin-left: 5px;
                    }

                    /* Estilo para o botão de copiar */
                    .copy-to-clipboard-button {
                        background-color: #007bff;
                        color: #fff;
                        border: none;
                        padding: 5px 10px;
                        font-size: 14px;
                        border-radius: 5px;
                        cursor: pointer;
                        transition: background-color 0.3s ease;
                    }

                    .copy-to-clipboard-button:hover {
                        background-color: #0056b3;
                    }
                </style>

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center sticky-top" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>
                </nav>
                <?php
                if (isset($_SESSION['loginerro'])) {
                    echo "<script>Swal.fire({ icon: 'error', title: 'Erro!', html: '" . $_SESSION['loginerro'] . "' });</script>";
                    unset($_SESSION['loginerro']);
                }
                if (isset($_SESSION['loginsucesso'])) {
                    echo "<script>Swal.fire({ icon: 'success', title: 'Sucesso!', html: '" . $_SESSION['loginsucesso'] . "' });</script>";
                    unset($_SESSION['loginsucesso']);
                }
                ?>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y d-flex flex-column-reverse">
                        <div class="row">
                            <div class="container">
                                <div class="card resposta mt-3" style="margin-top: -1rem !important;" aria-label="Respostas">
                                    <div class="card-body response-container" style="overflow-y: auto; height: calc(62vh - 0px);"> <!-- Ajuste a altura conforme necessário -->
                                        <p id="answer"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-8 col-lg-8 mx-auto"> <!-- Adicionada a classe mx-auto -->
                                <form id="questionForm" class="d-flex flex-column">
                                    <div class="textarea-container">
                                        <textarea class="form-control" id="questionInput" rows="2" placeholder="Message ChatGP..." aria-label="Campo de entrada de pergunta"></textarea>
                                        <button class="btn btn-primary inside-textarea" aria-label="Enviar pergunta">
                                            <i class='bx bx-send'></i>
                                        </button>
                                    </div>
                                </form>
                                <div class="text-center mt-2" aria-label="Aviso">
                                    <p>ChatGP pode cometer erros. Considere verificar informações importantes.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Content -->
            </div>

            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <script src="./assets/vendor/libs/jquery/jquery.js"></script>
    <script src="./assets/vendor/libs/popper/popper.js"></script>
    <script src="./assets/vendor/js/bootstrap.js"></script>
    <script src="./assets/vendor/js/menu.js"></script>
    <script src="./assets/js/main.js"></script>
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <script src="./assets/js/welcomeMessage.js"></script>
    <script src="./assets/js/prism.js"></script>
    <script src="./assets/js/newChatButton.js"></script>
    <script src="./assets/js/fetchHistory.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Event listener para botões de exclusão
            document.querySelectorAll('.delete-historical').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Evita o comportamento padrão do botão

                    // Obtém o ID do histórico a ser excluído
                    const historicalId = this.dataset.historicalId;

                    // Exibe o diálogo de confirmação
                    Swal.fire({
                        title: 'Você tem certeza?',
                        text: "Esta ação não pode ser desfeita!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sim, exclua!',
                        cancelButtonText: 'Cancelar',
                        didOpen: () => {
                            // Configurações de z-index para o modal de processamento
                            Swal.getPopup().style.zIndex = '10001'; // Define o z-index do modal para um valor alto
                            Swal.getContainer().style.zIndex = '10000'; // Define o z-index do container para um valor um pouco menor
                            Swal.getBackdrop().style.zIndex = '9999';
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Se o usuário confirmar, redireciona para o script PHP para excluir o histórico
                            window.location.href = 'excluir_historico.php?id=' + historicalId;
                        }
                    });
                });
            });
        });
    </script>
    <script>
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
            return html.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function(match) {
                return escapeMap[match];
            });
        }

        function formatTextCommon(text) {
            return text;
        }

        function formatResponse(responseText) {
            return responseText.includes("```") ?
                formatCodeText(responseText) :
                formatTextCommon(responseText);
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
            .addEventListener("submit", function(e) {
                e.preventDefault();
                const questionInput = document.getElementById("questionInput");
                const responseElement = document.getElementById("answer");
                const question = questionInput.value.trim();

                if (question === "") {
                    alert("Por favor, insira uma pergunta.");
                    return;
                }

                const userIconHTML =
                    '<img src="./assets/img/user_icon.png" alt="User Icon" style="width: 25px; height: 25px; vertical-align: middle;">';
                const chatGptIconHTML =
                    '<img src="./assets/img/chatgpt_icon.png" alt="ChatGP Icon" style="width: 30px; height: 30px; vertical-align: middle;">';

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
                    body: JSON.stringify({
                        message: question
                    }),
                    headers: {
                        "Content-Type": "application/json"
                    },
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
    </script>


</body>

</html>