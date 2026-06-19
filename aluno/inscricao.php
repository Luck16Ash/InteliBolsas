<?php
// ============================
// Inscrição em Curso - InteliBolsas
// ============================

// Inicia sessão
session_start();
require '..\publico\conexao.php'; // Conexão com MariaDB

// ============================
// VERIFICA SE O ALUNO ESTÁ LOGADO
// ============================
if (!isset($_SESSION['id_aluno'])) {
    header("Location: ..\publico\login.php");
    exit;
}

// ============================
// CAPTURA ID DO ALUNO E DO CURSO
// ============================
$aluno_id = $_SESSION['id_aluno'];
$curso_id = $_POST['curso_id'] ?? null;

// ============================
// PROCESSA INSCRIÇÃO
// ============================
if ($curso_id) {

    // Verifica se já existe inscrição
    $stmtCheck = $conn->prepare("SELECT id FROM inscricoes WHERE aluno_id = ? AND curso_id = ?");
    $stmtCheck->bind_param("ii", $aluno_id, $curso_id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows == 0) {
        // Insere inscrição
        $stmt = $conn->prepare("INSERT INTO inscricoes (aluno_id, curso_id, data, status) VALUES (?, ?, NOW(), 'Inscrito')");
        $stmt->bind_param("ii", $aluno_id, $curso_id);
        $stmt->execute();

        // Incrementa contador de acessos do curso
        $stmtUpdate = $conn->prepare("UPDATE cursos SET acessos = acessos + 1 WHERE id = ?");
        $stmtUpdate->bind_param("i", $curso_id);
        $stmtUpdate->execute();
    }

    // Redireciona para o link externo do curso
    $stmtCurso = $conn->prepare("SELECT link_externo FROM cursos WHERE id = ?");
    $stmtCurso->bind_param("i", $curso_id);
    $stmtCurso->execute();
    $resultCurso = $stmtCurso->get_result();
    $curso = $resultCurso->fetch_assoc();

    if ($curso && !empty($curso['link_externo'])) {
        header("Location: " . $curso['link_externo']);
        exit;
    } else {
        echo "Link do curso não encontrado.";
        exit;
    }

} else {
    echo "Curso inválido.";
    exit;
}
?>
