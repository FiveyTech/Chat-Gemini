<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();
include 'dbconfig/db.php';

ini_set('display_errors', 0);  // Desativar exibição de erros no ambiente de produção
error_reporting(0);

// Estabelece conexão com o banco de dados
function conectarDB($server, $user, $pass, $db)
{
    try {
        $conexao = new PDO("mysql:host=$server;dbname=$db;charset=utf8mb4", $user, $pass);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;
    } catch (PDOException $e) {
        // Registra o erro em logs
        error_log('Connection failed: ' . $e->getMessage());
        $_SESSION['loginerro'] = 'Erro no sistema.';
        exit();
    }
}

// Verifica se o email já está cadastrado
function emailExistente($conexao, $email)
{
    $stmt = $conexao->prepare("SELECT COUNT(*) FROM accounts WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
}

// Realiza o processo de cadastro
function cadastrar($conexao, $email, $senha, $nome)
{
    // Validação do Nome
    if (!preg_match('/^[a-zA-Z ]+$/', $nome)) {
        $_SESSION['loginerro'] = 'Nome inválido.';
        header("Location: ./cadastra.php");
        exit();
    }

    // Verifica se o email é válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['loginerro'] = 'Email inválido.';
        header("Location: ./cadastra.php");
        exit();
    }

    // Verifica se a senha tem no mínimo 8 caracteres
    if (strlen($senha) < 8) {
        $_SESSION['loginerro'] = 'A senha deve ter no mínimo 8 caracteres.';
        header("Location: ./cadastra.php");
        exit();
    }

    // Verifica se o email já está cadastrado
    if (emailExistente($conexao, $email)) {
        $_SESSION['loginerro'] = 'Este email já está cadastrado no sistema.';
        header("Location: ./cadastra.php");
        exit();
    }

    // Hash da senha antes de armazenar no banco de dados usando PASSWORD_BCRYPT
    $hashedPassword = password_hash($senha, PASSWORD_BCRYPT);

    // Prepara a query de inserção
    $stmt = $conexao->prepare("INSERT INTO accounts (email, senha, nome, nivel) VALUES (:email, :senha, :nome, 3)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $hashedPassword);
    $stmt->bindParam(':nome', $nome);

    // Executa a query
    if ($stmt->execute()) {
        $_SESSION['loginsucesso'] = 'Cadastro realizado com sucesso!';
        return true;
    }

    // Registra o erro em logs
    error_log('Erro no cadastro: ' . implode(' | ', $stmt->errorInfo()));
    $_SESSION['loginerro'] = 'Erro no sistema.';
    exit();
}

$conexao = conectarDB($server, $user, $pass, $db);

if (isset($_POST['email'], $_POST['senha'], $_POST['nome'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $nome = $_POST['nome'];

    // Verifica se algum dos campos está vazio
    if (empty($email) || empty($senha) || empty($nome)) {
        $_SESSION['loginerro'] = 'Por favor, preencha todos os campos.';
        header("Location: ./cadastra.php");
        exit();
    }

    // Chama a função de cadastro
    if (cadastrar($conexao, $email, $senha, $nome)) {
        // Cadastro bem-sucedido, você pode redirecionar para a página desejada
        header("Location: ./index.php");
        exit();
    } else {
        // Algum erro ocorreu durante o cadastro
        header("Location: ./cadastra.php");
        exit();
    }
}
