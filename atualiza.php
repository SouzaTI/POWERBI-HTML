<?php
// Execute este script uma única vez para atualizar todas as senhas

$conn = new mysqli("localhost", "root", "", "onepage");
$hash = password_hash('123456', PASSWORD_DEFAULT);
$conn->query("UPDATE users SET password='$hash', must_change_password=1");
echo "Todas as senhas foram atualizadas para 123456 e marcado para trocar no primeiro acesso.";
?>