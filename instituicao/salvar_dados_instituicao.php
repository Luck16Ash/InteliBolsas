<?php
// ======= INÍCIO DO PHP =======

// Inicia sessão
session_start();
require '../publico/conexao.php';

// Verifica se instituição está logada
if (!isset($_SESSION['id_instituicao'])) {
    header("Location: ../publico/login.php");
    exit;
}

$idInstituicao = $_SESSION['id_instituicao'];

// Captura dados do formulário
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$endereco = $_POST['endereco'] ?? '';

// ======= Validação simples =======
if ($nome && $email && $telefone && $endereco) {
    $stmt = $conn->prepare("UPDATE instituicoes SET nome=?, email=?, telefone=?, endereco=? WHERE id=?");
    $stmt->bind_param("ssssi", $nome, $email, $telefone, $endereco, $idInstituicao);
    $stmt->execute();
}

// Redireciona de volta para área da instituição
$_SESSION['alterado'] = true;
header("Location: area_instituicao.php");
exit;
?>
