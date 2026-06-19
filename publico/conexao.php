<?php
// ============================
// Conexão com o Banco de Dados - InteliBolsas
// ============================

// Configurações do banco
$servername = "localhost"; // Servidor MySQL
$username = "root";        // Usuário do banco
$password = "";            // Senha do usuário
$dbname = "intelibolsas";  // Nome do banco de dados

// ============================
// CRIAR CONEXÃO
// ============================
$conn = new mysqli($servername, $username, $password, $dbname);

// ============================
// CHECAR CONEXÃO
// ============================
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Opcional: definir charset para evitar problemas de acentuação
$conn->set_charset("utf8mb4");
?>
