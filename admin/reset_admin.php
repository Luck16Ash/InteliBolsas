
<?php
// Conexão com o banco
require 'conexao.php';

// Novo usuário admin e senha
$usuario = 'admin';
$senhaNova = 'admin123'; // Aqui você define a senha que quiser

// Gera o hash seguro
$hashSenha = password_hash($senhaNova, PASSWORD_DEFAULT);

// Atualiza no banco
$stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE usuario = ?");
$stmt->bind_param("ss", $hashSenha, $usuario);
if ($stmt->execute()) {
    echo "Senha do admin redefinida com sucesso!<br>";
    echo "Usuário: $usuario <br>Senha: $senhaNova";
} else {
    echo "Erro ao redefinir a senha: " . $stmt->error;
}
?>
