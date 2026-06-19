<?php
session_start();
require'../publico/conexao.php';

// Verifica se o aluno está logado
if (!isset($_SESSION['id_aluno'])) {
    header("Location: ../publico/login.php");
    exit;
}

// Verifica se veio o ID do curso
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Curso inválido.");
}

$idCurso = (int)$_GET['id'];
$idAluno = $_SESSION['id_aluno'];

// Remove a inscrição do aluno
$sql = "DELETE FROM inscricoes WHERE aluno_id = ? AND curso_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $idAluno, $idCurso);

if ($stmt->execute()) {
    // Redireciona com mensagem
    header("Location: area_aluno.php?msg=inscricao_cancelada");
    exit;
} else {
    echo "Erro ao cancelar a inscrição.";
}
