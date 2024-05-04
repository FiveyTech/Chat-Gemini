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

// Verifica se o ID foi enviado via GET
if (isset($_GET['id'])) {
    // Obtém o ID enviado via GET e sanitiza
    $historicalId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        // Preparando e executando a consulta SQL para excluir o histórico com base no ID
        $stmt = $conexao->prepare("DELETE FROM historico WHERE id = :historicalId");
        $stmt->bindParam(':historicalId', $historicalId, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['loginsucesso'] = 'Histórico excluído com sucesso!';
        header("Location: ./chatgp.php");
        exit();
    } catch (PDOException $e) {
        echo 'Erro ao excluir histórico: ' . $e->getMessage();
        exit();
    }
} else {
    // Se nenhum ID foi enviado via GET, redireciona para alguma página de erro
    $_SESSION['loginerro'] = 'nenhum ID foi enviado.';
    header("Location: ./chatgp.php");
    exit();
}
