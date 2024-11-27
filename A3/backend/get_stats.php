<?php
// Exibir erros para depuração (remova em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cabeçalhos para habilitar CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

// Se for uma requisição OPTIONS (pré-vôo CORS), finalize com sucesso
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configuração de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eleicao";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao conectar ao banco de dados: ' . $conn->connect_error]);
    exit();
}

// Consultar o total de votos geral
$totalVotosQuery = "SELECT COUNT(*) AS total FROM votos";
$totalVotosResult = $conn->query($totalVotosQuery);
$totalVotosRow = $totalVotosResult->fetch_assoc();
$totalVotos = (int)$totalVotosRow['total'];

// Consultar votos por candidato
$sql = "SELECT candidatos.id, candidatos.nome, COUNT(votos.id) AS total_votos
        FROM candidatos
        LEFT JOIN votos ON candidatos.id = votos.candidato_id
        GROUP BY candidatos.id";

$result = $conn->query($sql);

if ($result) {
    $candidatos = [];
    while ($row = $result->fetch_assoc()) {
        $votosCandidato = (int)$row['total_votos'];
        $porcentagem = $totalVotos > 0 ? round(($votosCandidato / $totalVotos) * 100, 2) : 0; // Calcula a porcentagem
        $candidatos[] = [
            'candidato' => $row['nome'],
            'total_votos' => $votosCandidato,
            'porcentagem' => $porcentagem
        ];
    }
    echo json_encode(['status' => 'success', 'data' => $candidatos]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar dados: ' . $conn->error]);
}

$conn->close();
?>