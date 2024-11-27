<?php
// Exibir erros para depuração (remova em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cabeçalhos para permitir métodos e lidar com CORS
header("Access-Control-Allow-Origin: *"); // Permitir acesso de qualquer origem
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Cabeçalhos permitidos
header('Content-Type: application/json'); // Respostas no formato JSON

// Se for uma requisição OPTIONS (pré-vôo CORS), retorna com sucesso
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configuração de conexão com o banco de dados
$servername = "localhost";
$username = "root"; // Usuário padrão do MySQL no XAMPP
$password = ""; // Senha padrão
$dbname = "eleicao"; // Nome do banco de dados

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Conexão falhou: ' . $conn->connect_error]);
    exit();
}

// Verificando o método da requisição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebendo os dados enviados via POST
    $ra = $_POST['ra'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $candidate_id = $_POST['candidate_id'] ?? null;

    // Validando os dados
    if (empty($ra) || empty($nome) || empty($candidate_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos os campos são obrigatórios.']);
        exit();
    }

    // Preparando a consulta SQL para evitar SQL injection
    $stmt = $conn->prepare("INSERT INTO votos (candidato_id, ra, nome) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $candidate_id, $ra, $nome); // 'i' para integer, 's' para string

    // Executando a consulta e retornando a resposta
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Voto registrado com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao registrar o voto: ' . $stmt->error]);
    }

    // Fechando a declaração e a conexão
    $stmt->close();
    $conn->close();
} else {
    // Método não permitido
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
}
?>
