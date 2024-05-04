<?php

date_default_timezone_set('America/Sao_Paulo');
session_start();
include 'dbconfig/db.php';
require_once 'lib/vendor/autoload.php';

// Carrega as variáveis de ambiente a partir do arquivo .env na pasta dbconfig
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/dbconfig');
$dotenv->load();

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

// Configurações do Google
$clientID = $_ENV['GOOGLE_CLIENT_ID_CADASTRO'];
$clientSecret = $_ENV['GOOGLE_CLIENT_SECRET_CADASTRO'];
$redirectURI = $_ENV['GOOGLE_REDIRECT_URI_CADASTRO'];

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectURI);
$client->addScope("email");
$client->addScope("profile");

try {
    // Verifica se o código de autorização está presente
    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token);

        // Obtém informações do usuário
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        // Conecta-se ao banco de dados
        $conexao = conectarDB($server, $user, $pass, $db);

        // Verifica se o usuário já está registrado na tabela accounts
        $stmt = $conexao->prepare("SELECT id, email, senha, nivel, nome FROM accounts WHERE email = :email");
        $stmt->bindParam(':email', $google_account_info->email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            // Gere uma senha aleatória para o novo usuário
            $senhaAleatoria = bin2hex(random_bytes(8)); // 16 caracteres

            // Insere o novo usuário na tabela accounts
            $stmtInsert = $conexao->prepare("INSERT INTO accounts (email, senha, nivel, nome) VALUES (:email, :senha, :nivel, :nome)");
            $stmtInsert->bindParam(':email', $google_account_info->email);
            $senhaHash = password_hash($senhaAleatoria, PASSWORD_BCRYPT); // Hash da senha
            $stmtInsert->bindParam(':senha', $senhaHash);
            $stmtInsert->bindParam(':nivel', $nivel); // Defina o nível conforme necessário
            $stmtInsert->bindParam(':nome', $google_account_info->name);
            $nivel = 3; // Defina o nível conforme necessário
            $stmtInsert->execute();

            // Salva mensagem de sucesso na sessão
            $_SESSION['loginsucesso'] = "Cadastro realizado com sucesso!";
            header("Location: index.php");
            exit();
        } else {
            // Usuário já cadastrado, salva mensagem de erro na sessão
            $_SESSION['loginerro'] = "Email já cadastrado.";
            header("Location: cadastra.php");
            exit();
        }
    } else {
        // Redireciona para a página de login do Google
        $authUrl = $client->createAuthUrl();
        header("Location: $authUrl");
        exit();
    }
} catch (Exception $ex) {

    // Em caso de erro, registra no log e redireciona para a página de login com mensagem de erro
    $errorMessage = 'Ocorreu um erro durante o processo de login: ' . $ex->getMessage();
    error_log($errorMessage, 0);

    // Em caso de erro, salva mensagem de erro na sessão
    $_SESSION['loginerro'] = "Ocorreu um erro durante o processo de login";
    header("Location: cadastra.php");
    exit();
}
