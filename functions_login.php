<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();
include 'dbconfig/db.php';

ini_set('display_errors', 1);  // Ativar exibição de erros
error_reporting(E_ALL);

// Estabelece conexão com o banco de dados
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

// Realiza o processo de login
function login($conexao, $entrada, $senha)
{
    $stmt = $conexao->prepare("SELECT email, senha, id, nivel FROM accounts WHERE (email = :entrada) AND (nivel = 3)");
    $stmt->bindParam(':entrada', $entrada);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashedPassword = $row['senha'];

        // Verifica a senha usando password_verify
        if (password_verify($senha, $hashedPassword)) {
            return $row;
        }
    }

    return false;
}

$conexao = conectarDB($server, $user, $pass, $db);

if (isset($_POST['email'], $_POST['senha'])) {
    $entrada = $_POST['email']; // Pode ser um login ou um email
    $senha = $_POST['senha'];

    // Verifica campos vazios
    if (empty($entrada) || empty($senha)) {
        $_SESSION['loginerro'] = '<div>Preencha todos os campos!</div>';
        header("Location: ./index.php");
        exit();
    }

    $usuario = login($conexao, $entrada, $senha);

    if ($usuario) {
        // Inicializa as variáveis de sessão
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['senha'] = $usuario['senha'];
        $_SESSION['iduser'] = $usuario['id'];
        $_SESSION['nivel'] = $usuario['nivel'];

        $location = ($_SESSION['nivel'] == 3) ? 'functions_nivel.php' : 'functions_nivel.php';
        header("Location: $location");
        exit();
    } else {
        $_SESSION['loginerro'] = '<div>Email ou senha incorretos!</div>';
        header("Location: ./index.php");
        exit();
    }
}
