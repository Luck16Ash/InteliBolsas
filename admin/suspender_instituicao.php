<?php
session_start();
include 'conexao.php';

// ======= VALIDAÇÃO DE LOGIN ADMIN =======
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 'admin') {
    header("Location: login.php");
    exit;
}

// ======= SUSPENDER INSTITUIÇÃO =======
if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    
    // Atualiza status para 'suspenso'
    $stmt = $conn->prepare("UPDATE instituicoes SET status='suspenso' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: area_admin.php");
exit;
?>
