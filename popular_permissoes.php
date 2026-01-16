<?php
$conn = new mysqli("127.0.0.1:3307", "root", "", "onepage");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Copie o array $dashboards do seu onepage.php aqui:
$dashboards = [
    [
        'grupo' => 'Comercial Geral',
        'nome' => 'Comercial Geral',
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiODBkYjExOGItY2ZmYS00OGE0LThjMjEtYjcwNDM2OGYwMTg2IiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','gerenciacomercial','coordenacaologistica'],
    ],
    [
        'grupo' => 'Comercial Geral',
        'nome' => 'CurvaABC',
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiMGRlN2E2NmUtNjQ5OS00NjliLTkxNzYtNjgzNjU0ZDNiOWI5IiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','gerenciacomercial','compras','coordenacaologistica'],
    ],
    [
        'grupo' => 'Financeiro',
        'nome' => 'Financeiro',
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiZjMzNDQ0YmUtZjk4Yy00YTUwLWJjZjYtZjBhZGI0NjA1NmRiIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','gerenciaadm','supervisaofinanceiro'],
    ],
    [
        'grupo' => 'Força de Venda',
        'nome' => 'Força de Venda - Sup',
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiYWY3ZTliMjItYTE1Ni00N2Y1LTlmZjQtNmNmMWYwYTUzN2RlIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','gerenciacomercial'],
    ],
    [
        'grupo' => 'Logistica',
        'nome' => 'Analise de Produtividade - Att',
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiZDAxYjc4NDItZjJiMC00YjIwLWJmMjEtZThlMDVlOWIwYTcyIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','coordenacaologistica','supervisaooperacao'],
    ],
    [
        'grupo' => 'Logistica',
        'nome' => 'Followup Op_Com',
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiZjRhNThlZDMtZmYzNC00NDI5LTkxNTktMDQ4MjgyMmU2OTMzIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','gerenciacomercial','coordenacaologistica','supervisaooperacao'],
    ],
    [
        'grupo' => 'Logistica',
        'nome' => 'Logistica',
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiMjcyZTcyYTMtZDFkYy00M2Y1LWI5OGYtODQyZWZhMGE0MGQ3IiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','coordenacaologistica','supervisaooperacao'],
    ],
    [
        'grupo' => 'Logistica',
        'nome' => 'Picking TV',
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiYjIwY2JiMmYtZjE5Zi00MzNkLWFiZDEtNzljYWRkMzRhNGRkIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9&culture=pt-BR',
        'roles' => ['god','admin','coordenacaologistica','supervisaooperacao'],
    ],
    [
        'grupo' => 'Logistica',
        'nome' => 'Planejamento 24_25 S&OP',
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiOWY0ZDFmOGItMWJmNy00YjAwLWI5YjgtMmMyNjNmY2UzYTgwIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','gerenciacomercial','coordenacaologistica'],
    ],
    [
        'grupo' => 'Recursos Humanos',
        'nome' => 'Recursos Humanos',
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiMDkwMWY1ZWYtMmY4Yy00MmM5LWFjNjItZjNiNWNhNTcwNWE1IiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','gerenciaadm','coordenacaorh'],
    ],
    [
        'grupo' => 'Fornecedores', // Alterado de 'Suprimentos'
        'nome' => 'Fornecedores', // Alterado de 'Suprimentos'
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiNDBkODNmM2EtY2Y5Zi00M2M0LThlOWMtMjMzNzlhZTE4NmJkIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','compras'],
    ],
];

// Limpa a tabela antes de popular (opcional, cuidado!)
$conn->query("DELETE FROM dashboard_roles");

// Insere as permissões
foreach ($dashboards as $d) {
    foreach ($d['roles'] as $role) {
        $stmt = $conn->prepare("INSERT INTO dashboard_roles (dashboard_nome, role) VALUES (?, ?)");
        $stmt->bind_param('ss', $d['nome'], $role);
        $stmt->execute();
    }
}

echo "Permissões populadas com sucesso!";
?>