<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'god') {
    header('Location: onepage.php');
    exit;
}

if (isset($_POST['user_id'], $_POST['role'])) {
    $conn = new mysqli("127.0.0.1:3307", "root", "", "onepage");
    $user_id = intval($_POST['user_id']);
    $role = $conn->real_escape_string($_POST['role']);
    $conn->query("UPDATE users SET role='$role' WHERE id=$user_id");
    $conn->close();
    header("Location: onepage.php?updated=$user_id#userModal");
    exit;
} else {
    header('Location: onepage.php');
    exit;
}
?>