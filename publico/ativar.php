<?php
/**
 * Arquivo: publico/ativar.php
 * Descrição: Ativa a conta de um usuário através do token enviado por e-mail.
 */

require_once __DIR__ . '/conexao.php'; // Usa o $pdo

$mensagem = "";
$sucesso = false;

// Pega o email e o token da URL (Ex: ativar.php?email=...&token=...)
$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';

if (empty($email) || empty($token)) {
    $mensagem = "Link de ativação inválido ou incompleto.";
} else {
    try {
        // 1. Busca o usuário pelo token E pelo email
        $sql = "UPDATE usuarios 
                SET ativo = 1, token_ativacao = NULL 
                WHERE token_ativacao = :token 
                AND ativo = 0 
                AND id IN (
                    SELECT id_usuario FROM alunos WHERE email = :email_aluno
                    UNION 
                    SELECT id_usuario FROM instituicoes WHERE email = :email_instituicao
                )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':email_aluno', $email);
        $stmt->bindParam(':email_instituicao', $email);
        $stmt->execute();

        // 2. Verifica quantas linhas foram afetadas
        if ($stmt->rowCount() === 1) {
            $mensagem = "Sua conta foi ativada com sucesso! Você já pode fazer login.";
            $sucesso = true;
        } else {
            // Se 0 linhas foram afetadas: o token expirou, o email não existe ou a conta já estava ativa.
            $mensagem = "O link de ativação é inválido ou sua conta já estava ativa. Tente fazer login.";
        }

    } catch (PDOException $e) {
        $mensagem = "Ocorreu um erro no sistema. Tente novamente mais tarde.";
        error_log("Erro na Ativação: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ativação de Conta - InteliBolsas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/css_global.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Ativação de Conta</h1>
        
        <div class="alert <?= $sucesso ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($mensagem) ?>
        </div>

        <p>
            <a href="login.php" class="btn btn-primary">Ir para a página de Login</a>
        </p>
    </div>
</body>
</html>