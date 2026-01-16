<?php
// Adicione no topo do update_user.php para debug temporário:
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli("127.0.0.1:3307", "root", "", "onepage");
$user_id = $_SESSION['user_id'];

$username = trim($_POST['username']);
$password = trim($_POST['password'] ?? '');

$profile_photo = $_FILES['profile_photo'] ?? null;

// Atualiza nome
if ($username) {
    $stmt = $conn->prepare("UPDATE users SET username=? WHERE id=?");
    $stmt->bind_param("si", $username, $user_id);
    $stmt->execute();
    $_SESSION['username'] = $username;
}

// Atualiza senha se fornecida
if ($password !== '') {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->bind_param("si", $hash, $user_id);
    if ($stmt->execute()) {
        file_put_contents('debug.log', "Senha atualizada com sucesso\n", FILE_APPEND);
    } else {
        file_put_contents('debug.log', "Erro ao atualizar senha: " . $stmt->error . "\n", FILE_APPEND);
    }
}

// Atualiza foto de perfil se enviada
if ($profile_photo && $profile_photo['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($profile_photo['name'], PATHINFO_EXTENSION);
    $filename = 'uploads/profile_' . $user_id . '.' . $ext;
    move_uploaded_file($profile_photo['tmp_name'], $filename);
    $stmt = $conn->prepare("UPDATE users SET profile_photo=? WHERE id=?");
    $stmt->bind_param("si", $filename, $user_id);
    $stmt->execute();
    $_SESSION['profile_photo'] = $filename;
}

file_put_contents('debug.log', "Arquivo chamado em: " . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
file_put_contents('debug.log', "Senha recebida: '" . $password . "'" . PHP_EOL, FILE_APPEND);

header('Location: onepage.php?success=1#userModal');
exit;