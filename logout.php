<?php
// Inicia a sessão (ou resume a sessão existente)
session_start();

// Função para limpar todas as variáveis de sessão
function clearSessionVariables() {
    // Adicione aqui todas as variáveis de sessão que deseja limpar
    $_SESSION['login'] = null;
    $_SESSION['senha'] = null;
    $_SESSION['iduser'] = null;
    $_SESSION['byid'] = null;
    $_SESSION['mainid'] = null;
    $_SESSION['nivel'] = null;
    $_SESSION['nivel'] = null;
    $_SESSION['iduser'] = null;
}

// Função para redirecionar o usuário para uma página específica
function redirectTo($page) {
    header("Location: $page");
    exit;
}

// Verifica se o usuário está autenticado antes de encerrar a sessão
if (isset($_SESSION['iduser'])) {
    // Limpa todas as variáveis de sessão
    clearSessionVariables();

    // Destroi a sessão atual
    session_destroy();

    // Se estiver usando cookie de sessão, desative-o
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Limpa o cache de informações do sistema de arquivos
    clearstatcache();
}

// Redireciona para a página de login ou para a página inicial do site
redirectTo("index.php"); // Substitua "index.php" pela página desejada
?>
