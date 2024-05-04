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

function conectarDB($server, $user, $pass, $db) {
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

// Recuperar coluna 'historico' da tabela 'historico'
$iduser = $_SESSION['iduser'];
$query = "SELECT historico FROM historico WHERE byid = :iduser AND status = 'ativo'";
$stmt = $conexao->prepare($query);
$stmt->bindParam(':iduser', $iduser, PDO::PARAM_INT);

try {
    $stmt->execute();
    $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retornar a coluna 'historico' como JSON para ser processada no lado do cliente
    header('Content-Type: application/json');
    echo json_encode($historico);
} catch (PDOException $e) {
    echo 'Erro ao recuperar a coluna historico: ' . $e->getMessage();
}
?>
