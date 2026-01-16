<?php
session_start();
if (isset($_SESSION['user_id'])) {
    $conn = new mysqli("localhost", "root", "", "onepage");
    $user_id = $_SESSION['user_id'];
    $user = $conn->query("SELECT * FROM users WHERE id='$user_id'")->fetch_assoc();
}

// Dashboards: adicione cada dashboard no grupo correspondente e defina as roles permitidas
$dashboards = [
    // Comercial Geral
    [
        'grupo' => 'Comercial Geral',
        'nome' => 'Comercial Geral',
        'iframe' => '<iframe title="Comercial Geral" width="600" height="373.5" src="https://app.powerbi.com/view?r=eyJrIjoiODBkYjExOGItY2ZmYS00OGE0LThjMjEtYjcwNDM2OGYwMTg2IiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9" frameborder="0" allowFullScreen="true"></iframe>',
        'roles' => ['god','admin','gerenciacomercial','coordenacaologistica'], 
    ],
    [
        'grupo' => 'Comercial Geral',
        'nome' => 'CurvaABC',
        'iframe' => '<iframe title="CurvaABC" width="600" height="373.5" src="https://app.powerbi.com/view?r=eyJrIjoiMGRlN2E2NmUtNjQ5OS00NjliLTkxNzYtNjgzNjU0ZDNiOWI5IiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9" frameborder="0" allowFullScreen="true"></iframe>',
        'roles' => ['god','admin','gerenciacomercial','compras','coordenacaologistica'],
    ],
    // Financeiro
    [
        'grupo' => 'Financeiro',
        'nome' => 'Financeiro',
        'iframe' => '<iframe title="Financeiro" width="600" height="373.5" src="https://app.powerbi.com/view?r=eyJrIjoiZjMzNDQ0YmUtZjk4Yy00YTUwLWJjZjYtZjBhZGI0NjA1NmRiIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9" frameborder="0" allowFullScreen="true"></iframe>',
        'roles' => ['god','admin','gerenciaadm','supervisaofinanceiro'],
    ],
    // Força de Venda
    [
        'grupo' => 'Força de Venda',
        'nome' => 'Força de Venda - Sup',
        'iframe' => '<iframe title="Força de Venda - Sup" width="600" height="373.5" src="https://app.powerbi.com/view?r=eyJrIjoiYWY3ZTliMjItYTE1Ni00N2Y1LTlmZjQtNmNmMWYwYTUzN2RlIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9" frameborder="0" allowFullScreen="true"></iframe>',
        'roles' => ['god','admin','gerenciacomercial'],
    ],
    // Logística
    [
        'grupo' => 'Logistica',
        'nome' => 'Analise de Produtividade - Att',
        'iframe' => '<iframe title="Análise de Produtividade - Att" width="600" height="373.5" src="https://app.powerbi.com/view?r=eyJrIjoiZDAxYjc4NDItZjJiMC00YjIwLWJmMjEtZThlMDVlOWIwYTcyIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9" frameborder="0" allowFullScreen="true"></iframe>',
        'roles' => ['god','admin','coordenacaologistica','supervisaooperacao'],
    ],
    [
        'grupo' => 'Logistica',
        'nome' => 'Followup Op_Com',
        'iframe' => '<iframe title="Followup Op_Com" width="600" height="373.5" src="https://app.powerbi.com/view?r=eyJrIjoiZjRhNThlZDMtZmYzNC00NDI5LTkxNTktMDQ4MjgyMmU2OTMzIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9" frameborder="0" allowFullScreen="true"></iframe>',
        'roles' => ['god','admin','gerenciacomercial','coordenacaologistica','supervisaooperacao'],
    ],
    [
        'grupo' => 'Logistica',
        'nome' => 'Logistica',
        'iframe' => '<iframe title="Logistica" width="600" height="373.5" src="https://app.powerbi.com/view?r=eyJrIjoiMjcyZTcyYTMtZDFkYy00M2Y1LWI5OGYtODQyZWZhMGE0MGQ3IiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9" frameborder="0" allowFullScreen="true"></iframe>',
        'roles' => ['god','admin','coordenacaologistica','supervisaooperacao'],
    ],
    [
        'grupo' => 'Logistica',
        'nome' => 'Picking TV',
        'iframe' => '<iframe title="Picking TV" width="600" height="373.5" src="https://app.powerbi.com/view?r=eyJrIjoiNDJkODE0ODQtYjk0My00OTRlLThjMmItZTFjZGM3OTc4Zjk3IiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9" frameborder="0" allowFullScreen="true"></iframe>',
        'roles' => ['god','admin','coordenacaologistica','supervisaooperacao'], //Criar um usuário pra operação só acessar o Picking TV
    ],
    [
        'grupo' => 'Logistica',
        'nome' => 'Planejamento 24_25 S&OP',
        'iframe' => '<iframe title="Planejamento 24_25 S&OP" width="600" height="373.5" src="https://app.powerbi.com/view?r=eyJrIjoiOWY0ZDFmOGItMWJmNy00YjAwLWI5YjgtMmMyNjNmY2UzYTgwIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9" frameborder="0" allowFullScreen="true"></iframe>',
        'roles' => ['god','admin','gerenciacomercial','coordenacaologistica'],
    ],
    // Recursos Humanos
    [
        'grupo' => 'Recursos Humanos',
        'nome' => 'Recursos Humanos',
        'iframe' => '<iframe title="Recursos Humanos" width="600" height="373.5" src="https://app.powerbi.com/view?r=eyJrIjoiMDkwMWY1ZWYtMmY4Yy00MmM5LWFjNjItZjNiNWNhNTcwNWE1IiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9" frameborder="0" allowFullScreen="true"></iframe>',
        'roles' => ['god','admin','gerenciaadm','coordenacaorh'],
    ],
    // Suprimentos
    [
        'grupo' => 'Suprimentos',
        'nome' => 'Suprimentos',
        'iframe' => '<iframe title="Suprimentos" width="600" height="373.5" src="https://app.powerbi.com/view?r=eyJrIjoiNDBkODNmM2EtY2Y5Zi00M2M0LThlOWMtMjMzNzlhZTE4NmJkIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9" frameborder="0" allowFullScreen="true"></iframe>',
        'roles' => ['god','admin','compras'],
    ],
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Comercial Souza</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --sidebar-width: 280px; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { width: var(--sidebar-width); min-height: 100vh; background-color: #2c3e50; }
        .main-content { /* margin-left: var(--sidebar-width); */ }
        @media (max-width: 768px) {
            .sidebar { width: 70%; max-width: var(--sidebar-width); position: static; }
            .main-content { margin-left: 0 !important; }
        }
    </style>
</head>
<body class="bg-gray-100 flex min-h-screen">

    <!-- Sidebar fixa à esquerda, sem margem no topo -->
    <nav id="sidebar" class="sidebar bg-[#2c3e50] text-white h-screen overflow-y-auto md:relative z-40 flex flex-col items-center justify-start pt-6" aria-label="Menu Principal">
        <img src="img/logo.svg" alt="Logo da empresa" class="mx-auto max-w-[120px] mb-6" />
        <!-- Aqui vão os itens do menu -->
        <ul class="w-full px-4" id="sidebarMenu">
            <?php
            $grupos = [];
            foreach ($dashboards as $d) {
                if (!in_array($_SESSION['role'], $d['roles'])) continue;
                $grupos[$d['grupo']][] = $d;
            }
            $grupoIndex = 0;
            foreach ($grupos as $grupo => $items): $grupoId = 'grupoMenu' . $grupoIndex++; ?>
                <li>
                    <button type="button"
                        class="w-full text-left mt-4 mb-1 font-bold text-sm text-gray-200 uppercase tracking-wider flex items-center justify-between group"
                        onclick="toggleSubmenu('<?= $grupoId ?>')">
                        <?= htmlspecialchars($grupo) ?>
                        <span class="transition-transform group-[.open]:rotate-90">&#9654;</span>
                    </button>
                    <ul id="<?= $grupoId ?>" class="ml-2 hidden">
                        <?php foreach ($items as $dash): ?>
                            <li>
                                <a href="?dashboard=<?= urlencode($dash['nome']) ?>"
                                   class="block px-3 py-2 rounded hover:bg-blue-700 transition text-white text-base">
                                    <?= htmlspecialchars($dash['nome']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Header fixo no topo -->
        <header class="w-full bg-white flex justify-end items-center px-6 py-3 border-b border-gray-200 shadow-sm z-50" style="min-height:64px;">
            <div class="flex items-center gap-3">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="login.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow text-base font-semibold transition mr-2">Entrar na conta</a>
                    <a href="register.php" class="bg-gray-200 hover:bg-gray-300 text-[#2c3e50] px-4 py-2 rounded-lg shadow text-base font-semibold transition">Criar nova conta</a>
                <?php else: ?>
                    <div class="relative">
                        <button id="userMenuButton" class="user-profile flex items-center focus:outline-none" type="button">
                            <?php if (!empty($_SESSION['profile_photo'])): ?>
                                <img src="<?= htmlspecialchars($_SESSION['profile_photo']) ?>"
                                     alt="Foto do usuário" class="w-10 h-10 rounded-full object-cover border-2 border-blue-200 shadow" />
                            <?php else: ?>
                                <img src="img/default-user.png"
                                     alt="Foto padrão" class="w-10 h-10 rounded-full object-cover border-2 border-blue-200 shadow" />
                            <?php endif; ?>
                            <div class="ml-2 text-left hidden md:block">
                                <div class="font-semibold text-sm"><?= htmlspecialchars($_SESSION['username']) ?></div>
                                <div class="text-xs text-gray-400"><?= htmlspecialchars($_SESSION['role']) ?></div>
                            </div>
                        </button>
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-50">
                            <a href="#" onclick="openUserModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Configurações</a>
                            <a href="logout.php?redirect=login" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-100">Sair</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <main id="mainContent" class="main-content flex-1 px-4 py-10 transition-all duration-300">
            <?php
            $dashboardAtivo = null;
            if (isset($_GET['dashboard'])) {
                foreach ($dashboards as $d) {
                    if ($d['nome'] === $_GET['dashboard'] && in_array($_SESSION['role'], $d['roles'])) {
                        $dashboardAtivo = $d;
                        break;
                    }
                }
            }
            if ($dashboardAtivo): ?>
                <div class="w-full h-[80vh] flex flex-col items-center justify-center">
                    <h2 class="font-bold text-2xl mb-4 text-[#2c3e50] w-full text-center"><?= htmlspecialchars($dashboardAtivo['nome']) ?></h2>
                    <div class="flex-1 flex items-center justify-center w-full">
                        <div class="w-full max-w-[1100px] h-[65vh] flex items-center justify-center bg-white rounded-lg shadow p-0 mx-auto">
                            <?= str_replace(
                                ['width="600"', 'height="373.5"'],
                                ['width="100%"', 'height="100%"'],
                                $dashboardAtivo['iframe']
                            ) ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-gray-400 text-lg text-center py-16">
                    Selecione um dashboard no menu ao lado para visualizar.
                </div>
            <?php endif; ?>
        </main>
    </div>

    <div id="userModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-3xl relative"> <!-- max-w-3xl para modal mais largo -->
            <button onclick="closeUserModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
            <h2 class="text-2xl font-semibold mb-4 text-center">Configurações do Usuário</h2>
            <?php if ($_SESSION['role'] === 'god'): ?>
                <div class="mb-4 text-center font-semibold">Gerenciar permissões dos usuários</div>
                <div class="overflow-x-auto">
                    <div class="max-h-[400px] overflow-y-auto border rounded">
                        <table class="min-w-full text-sm">
                            <thead class="sticky top-0 bg-gray-100 z-10">
                                <tr>
                                    <th class="px-4 py-2 border">Usuário</th>
                                    <th class="px-4 py-2 border">Permissão Atual</th>
                                    <th class="px-4 py-2 border">Alterar Permissão</th>
                                    <th class="px-4 py-2 border"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $users = $conn->query("SELECT id, username, role FROM users");
                                while ($u = $users->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="px-4 py-2 border"><?= htmlspecialchars($u['username']) ?></td>
                                    <td class="px-4 py-2 border"><?= htmlspecialchars($u['role']) ?></td>
                                    <td class="px-4 py-2 border">
                                        <form method="post" action="update_role.php" class="flex items-center gap-2">
                                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                            <select name="role" class="border rounded px-2 py-1 min-w-[160px]">
                                                <option value="admin" <?= $u['role']=='admin'?'selected':'' ?>>admin</option>
                                                <option value="gerenciacomercial" <?= $u['role']=='gerenciacomercial'?'selected':'' ?>>gerenciacomercial</option>
                                                <option value="compras" <?= $u['role']=='compras'?'selected':'' ?>>compras</option>
                                                <option value="coordenacaologistica" <?= $u['role']=='coordenacaologistica'?'selected':'' ?>>coordenacaologistica</option>
                                                <option value="supervisaooperacao" <?= $u['role']=='supervisaooperacao'?'selected':'' ?>>supervisaooperacao</option>
                                                <option value="gerenciaadm" <?= $u['role']=='gerenciaadm'?'selected':'' ?>>gerenciaadm</option>
                                                <option value="supervisaofinanceiro" <?= $u['role']=='supervisaofinanceiro'?'selected':'' ?>>supervisaofinanceiro</option>
                                                <option value="coordenacaorh" <?= $u['role']=='coordenacaorh'?'selected':'' ?>>coordenacaorh</option>
                                                <option value="god" <?= $u['role']=='god'?'selected':'' ?>>god</option>
                                            </select>
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">Salvar</button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <?php if (isset($_GET['updated']) && $_GET['updated'] == $u['id']): ?>
                                            <span class="text-green-600 text-xs">Atualizado!</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center text-gray-500">Você não tem permissão para alterar roles.</div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function openUserModal() {
            document.getElementById('userModal').classList.remove('hidden');
        }
        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
        }
        // Dropdown do usuário
        document.addEventListener('click', function(event) {
            const userMenuButton = document.getElementById('userMenuButton');
            const userDropdown = document.getElementById('userDropdown');
            if (userMenuButton && userDropdown) {
                if (userMenuButton.contains(event.target)) {
                    userDropdown.classList.toggle('hidden');
                } else if (!userDropdown.contains(event.target)) {
                    userDropdown.classList.add('hidden');
                }
            }
        });
        function toggleSubmenu(id) {
            const el = document.getElementById(id);
            if (el) el.classList.toggle('hidden');
        }
        // Abrir modal de usuário se a hash da URL for #userModal
        if (window.location.hash === "#userModal") {
            openUserModal();
        }
    </script>
    
</body>
</html>