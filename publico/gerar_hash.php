<?php
// gerar_hash.php
// ======================================
// Gera um hash seguro para uma senha
// ======================================

// Digite aqui a senha que deseja converter:
$senha = "123456";

// Gera o hash usando o algoritmo padrão (bcrypt)
$hash = password_hash($senha, PASSWORD_DEFAULT);

// Exibe o resultado
echo "<h3>Senha original:</h3> $senha";
echo "<h3>Hash gerado:</h3> <code>$hash</code>";
?>