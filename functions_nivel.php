<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();

// Função para redirecionar com base no nível de acesso
function redirecionarConformeNivel($nivel) {
    if ($nivel == 3) {
        echo "<script> window.location.href='./chatgp.php'; </script>";
    } else {
        echo "<script> window.location.href='./index.php'; </script>";
    }
}

// Verifica se o usuário está logado
if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
    echo "<script> window.location.href='./logout.php'; </script>";
    exit();
}

// Chama a função com o nível de acesso do usuário
redirecionarConformeNivel($_SESSION['nivel']);
?>
