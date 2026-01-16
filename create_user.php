<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'god') {
    header('Location: onepage.php');
    exit;
}

$conn = new mysqli("127.0.0.1:3307", "root", "", "onepage");

$username = trim($_POST['username']);
$email = trim($_POST['email']);
$department = trim($_POST['department']);
$role = trim($_POST['role']);
$password = password_hash('123456', PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, email, department, role, password) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $username, $email, $department, $role, $password);
$stmt->execute();

header('Location: onepage.php?user_created=1');
exit;