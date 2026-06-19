<?php
// ============================
// Atualizar Dados do Aluno - InteliBolsas
// ============================

// Inicia sessão
session_start();

// Importa a conexão com o banco MariaDB
require '../publico/conexao.php';

// ============================
// VERIFICA SE O ALUNO ESTÁ LOGADO
// ============================
if (!isset($_SESSION['id_aluno'])) {
    // Redireciona para login caso não esteja logado
    header("Location: login.php");
    exit;
}

// Captura ID do aluno logado
$idAluno = $_SESSION['id_aluno'];

// ============================
// CAPTURA DADOS DO FORMULÁRIO
// ============================
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$area_interesse = $_POST['area_interesse'] ?? '';

// ============================
// VALIDAÇÃO SIMPLES
// ============================
if ($nome && $email && $telefone && $area_interesse) {
    // Prepara statement para atualizar os dados
    $stmt = $conn->prepare("UPDATE alunos SET nome=?, email=?, telefone=?, area_interesse=? WHERE id=?");
    $stmt->bind_param("ssssi", $nome, $email, $telefone, $area_interesse, $idAluno);
    $stmt->execute();
}

// ============================
// REDIRECIONA DE VOLTA PARA ÁREA DO ALUNO
// ============================
$_SESSION['alterado'] = true;
header("Location: area_aluno.php#Alter");
exit;
?>
