<?php
// Defina as configurações de conexão
$servername = "localhost"; // Para XAMPP, o servidor é "localhost"
$username = "root"; // Usuário padrão do MySQL no XAMPP
$password = ""; // Senha padrão do MySQL no XAMPP (normalmente em branco)
$dbname = "eleicao"; // Nome do banco de dados

echo 'dasdasd';

// Crie a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifique se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
echo "Conexão bem-sucedida!";
?>