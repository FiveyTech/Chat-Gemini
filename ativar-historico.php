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

if (isset($_GET['historicalName']) && isset($_GET['mainid'])) {
    try {
        // Obtenha o nome do histórico a ser ativado da URL
        $historicalNameToActivate = $_GET['historicalName'];
        $mainid = $_GET['mainid'];

        // Inicie uma transação para garantir consistência
        $conexao->beginTransaction();

        // Desative todos os históricos ativos do usuário
        $stmtDisableAll = $conexao->prepare("UPDATE historico SET status = 'desativado' WHERE byid = :iduser AND status = 'ativo'");
        $stmtDisableAll->bindParam(':iduser', $_SESSION['iduser']);
        $stmtDisableAll->execute();

        // Atualize o status para 'ativo' no histórico escolhido
        $stmtActivate = $conexao->prepare("UPDATE historico SET status = 'ativo' WHERE mainid = :mainid AND nome = :historicalName");
        $stmtActivate->bindParam(':mainid', $mainid);
        $stmtActivate->bindParam(':historicalName', $historicalNameToActivate);
        $stmtActivate->execute();

        // Confirme a transação se tudo ocorrer bem
        $conexao->commit();

        header("Location: ./chatgp.php");
        exit();
    } catch (PDOException $e) {
        // Em caso de erro, desfaça a transação e envie uma resposta de erro
        $conexao->rollBack();
        echo 'Erro: ' . $e->getMessage();
    }
}
?>
