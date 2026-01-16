<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'god') {
    http_response_code(403);
    exit('Acesso negado');
}
$conn = new mysqli("127.0.0.1:3307", "root", "", "onepage");
if ($conn->connect_error) { http_response_code(500); exit("Erro: " . $conn->connect_error); }

$dashboards = [/* ...cole aqui o array do onepage.php... */];
$roles = ['god','admin','gerenciacomercial','coordenacaologistica','compras','gerenciaadm','supervisaofinanceiro','supervisaooperacao','coordenacaorh'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dashboard'])) {
    $dashboard = $_POST['dashboard'];
    $rolesSelecionadas = isset($_POST['roles']) ? $_POST['roles'] : [];
    $conn->query("DELETE FROM dashboard_roles WHERE dashboard_nome = '".$conn->real_escape_string($dashboard)."'");
    foreach ($rolesSelecionadas as $role) {
        if (in_array($role, $roles)) {
            $stmt = $conn->prepare("INSERT INTO dashboard_roles (dashboard_nome, role) VALUES (?, ?)");
            $stmt->bind_param('ss', $dashboard, $role);
            $stmt->execute();
        }
    }
    header('Content-Type: application/json');
    echo json_encode(['ok'=>true, 'roles'=>$rolesSelecionadas]);
    exit;
}
$permissoes = [];
$result = $conn->query("SELECT * FROM dashboard_roles");
while ($row = $result->fetch_assoc()) {
    $permissoes[$row['dashboard_nome']][] = $row['role'];
}
?>