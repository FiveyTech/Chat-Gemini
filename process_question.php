<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();

include 'lib/vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['iduser']) || $_SESSION['nivel'] < 2) {
    echo "<script> window.location.href='./logout.php'; </script>";
    exit();
}

if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
    echo "<script> window.location.href = './logout.php'; </script>";
    exit();
}

// Carrega as variáveis de ambiente a partir do arquivo .env na pasta dbconfig
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/dbconfig');
$dotenv->load();

$apiKey = $_ENV['API_KEY'];

use Parsedown;

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

function saveHistoryToDatabase($conexao, $byId, $userMessage, $modelMessage, $nome)
{
    try {
        // Verificar se já existe um mainid para este registro
        $mainidQuery = "SELECT mainid FROM historico WHERE byid = ? AND status = 'ativo'";
        $stmtMainid = $conexao->prepare($mainidQuery);
        $stmtMainid->execute([$byId]);
        $existingMainid = $stmtMainid->fetchColumn();

        if ($existingMainid) {
            // Se já existir, usar o mainid existente
            $mainid = $existingMainid;
            $action = 'update';
        } else {
            // Se não existir, gerar um novo mainid único de 6 dígitos
            $mainid = mt_rand(100000, 999999);
            $action = 'insert';
        }

        $statusCheckQuery = "SELECT id, historico FROM historico WHERE byid = ? AND status = 'ativo'";
        $stmtStatusCheck = $conexao->prepare($statusCheckQuery);
        $stmtStatusCheck->execute([$byId]);
        $activeHistory = $stmtStatusCheck->fetch(PDO::FETCH_ASSOC);

        if ($activeHistory) {
            $existingHistory = json_decode($activeHistory['historico'], true);
            $existingHistory[] = $userMessage;
            $existingHistory[] = $modelMessage;
            if ($action === 'update') {
                $queryUpdate = "UPDATE historico SET historico = ?, nome = ? WHERE id = ? AND status = 'ativo'";
                $stmtUpdate = $conexao->prepare($queryUpdate);
                $stmtUpdate->execute([json_encode($existingHistory), $nome, $activeHistory['id']]);
            } else {
                $queryUpdate = "UPDATE historico SET historico = ?, nome = ?, mainid = ? WHERE id = ? AND status = 'ativo'";
                $stmtUpdate = $conexao->prepare($queryUpdate);
                $stmtUpdate->execute([json_encode($existingHistory), $nome, $mainid, $activeHistory['id']]);
            }
        } else {
            $newHistory = [$userMessage, $modelMessage];
            $queryInsert = "INSERT INTO historico (byid, historico, status, nome, mainid) VALUES (?, ?, 'ativo', ?, ?)";
            $stmtInsert = $conexao->prepare($queryInsert);
            $stmtInsert->execute([$byId, json_encode($newHistory), $nome, $mainid]);
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
        exit();
    }
}


function getHistoryFromDatabase($conexao, $byId)
{
    try {
        $query = "SELECT historico FROM historico WHERE byid = ? AND status = 'ativo'";
        $stmt = $conexao->prepare($query);
        $stmt->execute([$byId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return json_decode($result['historico'], true);
        } else {
            return [];
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
        exit();
    }
}

function sendApiRequest($url, $question, $history)
{
    $contents = [];

    foreach ($history as $entry) {
        if (isset($entry['role']) && isset($entry['text'])) {
            $contents[] = ["role" => $entry['role'], "parts" => [["text" => $entry['text']]]];
        }
    }

    if (!empty($contents) && $contents[count($contents) - 1]['role'] === 'user') {
        array_pop($contents);
    }

    $contents[] = ["role" => "user", "parts" => [["text" => $question]]];

    $apiData = ["contents" => $contents];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiData));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception('Erro de cURL: ' . curl_error($ch));
    }

    curl_close($ch);

    return json_decode($response, true);
}

function processApiResponse($apiResponse)
{
    $parsedown = new Parsedown();

    if (isset($apiResponse['candidates'][0]['content']['parts'][0]['text'])) {
        return $parsedown->text($apiResponse['candidates'][0]['content']['parts'][0]['text']);
    } else {
        error_log('Resposta inesperada da API: ' . json_encode($apiResponse));
        return 'Desculpe, houve um erro ao processar sua pergunta.';
    }
}

function extractNameFromQuestion()
{
    // Extrai a data e hora no formato brasileiro como nome
    return date('d/m/Y H:i');
}

$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $apiKey;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $question = $data['message'];
    $nome = extractNameFromQuestion();

    if (empty($question)) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Mensagem não fornecida']);
        exit;
    }

    $byId = $_SESSION['iduser'];
    $history = getHistoryFromDatabase($conexao, $byId);

    if (!is_array($history)) {
        $history = [];
    }

    try {
        $apiResponse = sendApiRequest($url, $question, $history);
        $formattedResponse = processApiResponse($apiResponse);

        $userMessage = ["role" => "user", "text" => $question];
        $modelMessage = ["role" => "model", "text" => $formattedResponse];

        saveHistoryToDatabase($conexao, $byId, $userMessage, $modelMessage, $nome);

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        echo json_encode(['formattedResponse' => $formattedResponse]);
    } catch (Exception $e) {
        error_log($e->getMessage());
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Erro ao fazer a solicitação para a API']);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}
