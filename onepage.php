<?php
session_start();
if (isset($_SESSION['user_id'])) {
    $conn = new mysqli("127.0.0.1:3307", "root", "", "onepage");
    $user_id = $_SESSION['user_id'];
    $user = $conn->query("SELECT * FROM users WHERE id='$user_id'")->fetch_assoc();
}

// BUSCA DINÂMICA DAS ROLES DO BANCO
$roles = [];
if (isset($conn)) {
    $res = $conn->query("SELECT DISTINCT role FROM users WHERE role IS NOT NULL AND role != ''");
    while ($row = $res->fetch_assoc()) {
        $roles[] = $row['role'];
    }
}

// Dashboards: adicione cada dashboard no grupo correspondente e defina as roles permitidas
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
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiYjIwY2JiMmYtZjE5Zi00MzNkLWFiZDEtNzljYWRkMzRhNGRkIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9&culture=pt-BR&locale=pt-BR',
        'roles' => ['god','admin','coordenacaologistica','supervisaooperacao'],
    ],
    [
        'grupo' => 'Logistica',
        'nome' => 'Planejamento 25_26 S&OP',
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiZmZiODAwNDctODA1Yi00MDhhLWEwNjEtMjBlZTlmYWQ1OGYyIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','gerenciacomercial','coordenacaologistica'],
    ],
    [
        'grupo' => 'Logistica',
        'nome' => 'Logistica OP',
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiYzU3YTUzZDUtNzRiNy00MTc5LWJjYjgtMzJlOWFmNGQxZTVmIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','suplogistica','coordlogistica'],
    ],
    [
        'grupo' => 'Logistica',
        'nome' => 'Followup OP',
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiMTliN2Y5NjQtMGM1Zi00ZmEzLTk3NjAtYWExMGMxNDc0MDhmIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','coordlogistica'],
    ],
    [
        'grupo' => 'Recursos Humanos',
        'nome' => 'Recursos Humanos',
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiMDkwMWY1ZWYtMmY4Yy00MmM5LWFjNjItZjNiNWNhNTcwNWE1IiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','gerenciaadm','coordenacaorh'],
    ],
    [
        'grupo' => 'Fornecedores',
        'nome' => 'Fornecedores', 
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiNDBkODNmM2EtY2Y5Zi00M2M0LThlOWMtMjMzNzlhZTE4NmJkIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','compras'],
    ],
    [
        'grupo' => 'Fornecedores',
        'nome' => 'Saldo por Industria', 
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiZmI0Y2Y1OTItMmE0ZS00NDI4LTgwOGEtMTdjOGExZjBjOWYwIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','assistentecompras'],
    ],
    [
        'grupo' => 'Fornecedores',
        'nome' => 'Análise de giro', 
        'iframe' => 'https://app.powerbi.com/view?r=eyJrIjoiNWQ5ZjhlYjctZWVkMC00YzhmLTliMTItM2JiN2ViNTMxMDBiIiwidCI6Ijg4ZjhhYzhhLWM5ZTgtNDI5MC05OGEyLWYxZGZhM2U0ZmM5MyJ9',
        'roles' => ['god','admin','assistentecadastro'],
    ],
];

// Carrega permissões dinâmicas do banco
$permissoes = [];
if (isset($conn)) {
    $res = $conn->query("SELECT dashboard_nome, role FROM dashboard_roles");
    while ($row = $res->fetch_assoc()) {
        $permissoes[$row['dashboard_nome']][] = $row['role'];
    }
}

// Agrupar dashboards por grupo e filtrar por permissão dinâmica
$grupos = [];
if (isset($_SESSION['role'])) {
    foreach ($dashboards as $d) {
        // Usa permissões do banco se existirem, senão usa as do array (fallback)
        $rolesPermitidas = $permissoes[$d['nome']] ?? $d['roles'];
        if (in_array($_SESSION['role'], $rolesPermitidas)) {
            $grupos[$d['grupo']][] = $d;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard BI - Navegação por Setores</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.3);}
        .submenu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease;}
        .submenu.active { max-height: 500px;}
        .gradient-bg { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);}
        .icon-bounce { animation: bounce 2s infinite;}
        @keyframes bounce {0%,20%,50%,80%,100%{transform:translateY(0);}40%{transform:translateY(-10px);}60%{transform:translateY(-5px);}}
        /* Estilos personalizados */
        :root {
            --modal-max-width: 90vw; /* ou 80vw, 95vw, etc */
            --dashboard-height: 80vh; /* ou 90vh, etc */
        }
        #dashboard-container .bg-slate-900 {
            max-width: var(--modal-max-width);
            width: 100%;
            height: calc(var(--dashboard-height) + 2rem);
            max-height: calc(100vh - 2rem);
        }
        #dashboard-frame {
            height: var(--dashboard-height);
        }
        
        #userModal,
        #createUserModal,
        #permissoesModal,
        #dashboard-container {
            z-index: 9999 !important;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen text-white">
    <!-- Header -->
    <header class="bg-slate-800/70 backdrop-blur-sm border-b border-slate-700 px-8 py-3 shadow-lg">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <!-- Logo maior, mas sem aumentar o header -->
            <div class="flex items-center space-x-4">
                <div class="relative flex items-center">
                    <img src="img/logo.svg" alt="Logo" class="w-20 h-20 md:w-32 md:h-32 object-contain -my-6 md:-my-10" style="z-index:1;" />
                    <!-- O -my-6 e -my-10 "vazam" a logo para fora do header sem aumentar o header -->
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight leading-tight">Dashboard BI</h1>
                    <p class="text-sm text-slate-300 font-medium">Sistema de Business Intelligence</p>
                </div>
            </div>
            <!-- Usuário -->
            <div class="flex items-center space-x-4">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="login.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow text-base font-semibold transition mr-2">Entrar</a>
                    <a href="register.php" class="bg-gray-200 hover:bg-gray-300 text-[#2c3e50] px-4 py-2 rounded-lg shadow text-base font-semibold transition">Criar Conta</a>
                <?php else: ?>
                    <div class="relative flex items-center">
                        <button id="userMenuButton" class="flex items-center gap-3 focus:outline-none group" type="button">
                            <div class="w-12 h-12 rounded-full border-4 border-blue-400 shadow-lg overflow-hidden bg-white flex items-center justify-center">
                                <img src="<?= !empty($_SESSION['profile_photo']) ? htmlspecialchars($_SESSION['profile_photo']) : 'img/default-user.png' ?>" alt="Foto do usuário" class="w-full h-full object-cover" />
                            </div>
                            <div class="text-left hidden md:block">
                                <div class="font-bold text-lg leading-tight"><?= htmlspecialchars($user['username']) ?></div>
                                <div class="text-xs text-blue-200 font-semibold"><?= htmlspecialchars($user['department'] ?? '') ?></div>
                            </div>
                            <svg class="w-5 h-5 text-blue-300 group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="userDropdown" class="hidden absolute left-full top-0 ml-4 w-56 bg-white rounded-xl shadow-xl border z-50">
                            <a href="#" onclick="openUserModal(); return false;" class="block px-5 py-3 text-base text-blue-700 hover:bg-blue-50 font-semibold">Configurações</a>
                            <?php if ($_SESSION['role'] === 'god'): ?>
                                <a href="#" onclick="openPermissoesModal();return false;" class="block px-5 py-3 text-base text-blue-700 hover:bg-blue-50 font-semibold">Permissões</a>
                                <a href="#" onclick="openCreateUserModal(); return false;" class="block px-5 py-3 text-base text-blue-700 hover:bg-blue-50 font-semibold">Criar Usuário</a>
                            <?php endif; ?>
                            <a href="logout.php?redirect=login" class="block px-5 py-3 text-base text-red-600 hover:bg-red-50 font-semibold">Sair</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4">Navegação por Setores</h2>
            <p class="text-slate-400 text-lg">Selecione um setor para acessar os relatórios específicos</p>
        </div>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="text-center text-lg text-slate-400 py-16">Faça login para visualizar os dashboards.</div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($grupos as $grupo => $items): ?>
                <div class="bg-slate-800/60 backdrop-blur-sm rounded-2xl p-6 border border-slate-700 card-hover cursor-pointer" onclick="toggleSubmenu('<?= md5($grupo) ?>')">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center icon-bounce">
                            <span class="text-2xl">
                                <?php
                                // Ícones por grupo
                                $icons = [
                                    'Comercial Geral' => '💼',
                                    'Financeiro' => '💰',
                                    'Força de Venda' => '📈',
                                    'Logistica' => '🚚',
                                    'Recursos Humanos' => '🧑‍💼',
                                    'Fornecedores' => '📦', // Alterado de 'Suprimentos'
                                ];
                                echo isset($icons[$grupo]) ? $icons[$grupo] : '📊';
                                ?>
                            </span>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 transform transition-transform" id="arrow-<?= md5($grupo) ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($grupo) ?></h3>
                    <p class="text-slate-400 mb-4">Dashboards do setor <?= htmlspecialchars($grupo) ?></p>
                    <div class="submenu" id="submenu-<?= md5($grupo) ?>">
                        <div class="space-y-2 mt-4 pt-4 border-t border-slate-600">
                            <?php foreach ($items as $dash): ?>
                                <button class="w-full flex items-center gap-3 text-left p-3 bg-gradient-to-r <?= $sectorColors[$grupo] ?? 'from-blue-600 to-blue-400 hover:from-blue-700 hover:to-blue-500' ?> text-white font-semibold rounded-lg shadow transition-colors"
                                    onclick="openDashboard('<?= htmlspecialchars($dash['nome']) ?>', '<?= htmlspecialchars($dash['iframe']) ?>'); event.stopPropagation();">
                                    <span>
                                        <?php
                                        // Ícones por dashboard
                                        $dashIcons = [
                                            'Comercial Geral' => '📊',
                                            'CurvaABC' => '📉',
                                            'Financeiro' => '💵',
                                            'Força de Venda - Sup' => '🧑‍💼',
                                            'Analise de Produtividade - Att' => '⚙️',
                                            'Followup Op_Com' => '🔎',
                                            'Logistica' => '🚚',
                                            'Picking TV' => '📺',
                                            'Planejamento 24_25 S&OP' => '🗓️',
                                            'Recursos Humanos' => '👥',
                                            'Fornecedores' => '📦', // Alterado de 'Suprimentos'
                                        ];
                                        echo isset($dashIcons[$dash['nome']]) ? $dashIcons[$dash['nome']] : '📈';
                                        ?>
                                    </span>
                                    <?= htmlspecialchars($dash['nome']) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>

    <!-- Dashboard Container -->
    <div id="dashboard-container" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50">
        <div id="dashboard-modal" class="bg-slate-900 rounded-2xl shadow-2xl w-full max-h-[96vh] p-0 flex flex-col items-center justify-center relative" style="max-width: 90vw;">
            <button onclick="closeDashboard()" class="absolute top-4 right-6 text-slate-400 hover:text-white text-3xl z-10">&times;</button>
            <h2 id="dashboard-title" class="text-2xl font-bold mb-2 mt-8 text-white w-full text-center"></h2>
            <div id="dashboard-frame" class="flex-1 flex items-center justify-center w-full h-full px-0 pb-8" style="width:100%;">
                <!-- O iframe será inserido aqui -->
            </div>
        </div>
    </div>

    <!-- Modal de Configurações do Usuário -->
    <div id="userModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg relative">
            <button onclick="closeUserModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
            <h2 class="text-2xl font-bold mb-6 text-center text-blue-900">Configurações do Usuário</h2>
            <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 text-center font-semibold">
                    Alterações salvas com sucesso!
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['user_created'])): ?>
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 text-center font-semibold">
                    Usuário criado com sucesso!
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form id="userConfigForm" method="post" action="update_user.php" enctype="multipart/form-data" class="space-y-5">
                    <div class="flex flex-col items-center gap-2">
                        <label for="profile_photo" class="cursor-pointer relative group">
                            <img id="profilePreview" src="<?= !empty($_SESSION['profile_photo']) ? htmlspecialchars($_SESSION['profile_photo']) : 'img/default-user.png' ?>" alt="Foto do usuário" class="w-24 h-24 rounded-full object-cover border-4 border-blue-300 shadow">
                            <span class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 text-xs opacity-80 group-hover:opacity-100 transition">Trocar</span>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="hidden" onchange="previewProfilePhoto(event)">
                        </label>
                    </div>
                    <div>
                        <label class="block text-blue-900 font-semibold mb-1" for="username">Nome</label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($_SESSION['username']) ?>" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-900" required>
                    </div>
                    <div>
                        <label class="block text-blue-900 font-semibold mb-1" for="password">Nova Senha</label>
                        <input type="password" id="password" name="password" placeholder="Deixe em branco para não alterar" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-900">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow">Salvar Alterações</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal de Permissões (apenas GOD vê) -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'god'): ?>
    <div id="permissoesModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden px-2">
        <div class="bg-white rounded-2xl shadow-2xl p-4 sm:p-8 w-full max-w-4xl relative max-h-[95vh] flex flex-col">
            <button onclick="closePermissoesModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
            <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 text-center text-blue-900">Painel de Permissões dos Dashboards</h2>
            <!-- PAINEL DINÂMICO DE PERMISSÕES -->
            <div id="permissoesDashList" class="grid grid-cols-1 sm:grid-cols-2 gap-2 overflow-y-auto flex-1"></div>
            <!-- Mini-modal para editar roles de um dashboard -->
            <div id="rolesModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden px-2">
                <div class="bg-white rounded-xl shadow-xl p-4 sm:p-8 w-full max-w-md relative">
                    <button onclick="closeRolesModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
                    <h3 id="rolesModalTitle" class="text-lg sm:text-xl font-bold mb-4 text-blue-900"></h3>
                    <form id="rolesForm" onsubmit="return salvarRolesDashboard();">
                        <div id="rolesCheckboxes" class="space-y-2 mb-4 text-blue-900"></div>
                        <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded-lg font-semibold shadow block mx-auto text-base sm:text-lg">Salvar Permissões</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div id="permissoesDashList" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
    <div id="rolesModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-md relative">
            <button onclick="closeRolesModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
            <h3 id="rolesModalTitle" class="text-xl font-bold mb-4 text-blue-900"></h3>
            <form id="rolesForm" onsubmit="return salvarRolesDashboard();">
                <div id="rolesCheckboxes" class="space-y-2 mb-4"></div>
                <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-8 py-2 rounded-lg font-semibold shadow block mx-auto">Salvar Permissões</button>
            </form>
        </div>
    </div>
    <!-- Modal de Criar Usuário (apenas GOD vê) -->
    <div id="createUserModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg relative">
            <button onclick="closeCreateUserModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
            <h2 class="text-2xl font-bold mb-6 text-center text-blue-900">Criar Novo Usuário</h2>
            <form id="createUserForm" method="post" action="create_user.php" class="space-y-5">
                <div>
                    <label class="block text-blue-900 font-semibold mb-1" for="username">Nome Completo</label>
                    <input type="text" id="username" name="username" required class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-900">
                </div>
                <div>
                    <label class="block text-blue-900 font-semibold mb-1" for="email">Email Corporativo</label>
                    <input type="email" id="email" name="email" required class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-900">
                </div>
                <div>
                    <label class="block text-blue-900 font-semibold mb-1" for="department">Departamento</label>
                    <input type="text" id="department" name="department" required class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-900">
                </div>
                <div>
                    <label class="block text-blue-900 font-semibold mb-1" for="role">Role</label>
                    <select id="role" name="role" required class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-900">
                        <option value="">Selecione a role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="bg-gray-100 text-gray-700 px-4 py-2 rounded mb-2 text-sm">
                    Senha inicial padrão: <b>123456</b><br>
                    O usuário precisará trocar a senha no primeiro acesso.
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow">Criar Usuário</button>
                </div>
            </form>
        </div>
    </div>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'god'): ?>
    <script>
    const dashboards = <?= json_encode($dashboards) ?>;
    const roles = <?= json_encode($roles) ?>;
    const permissoesAtuais = <?= json_encode($permissoes) ?>;
    </script>
    <?php endif; ?>
    <script>
        function toggleSubmenu(sector) {
            const submenu = document.getElementById(`submenu-${sector}`);
            const arrow = document.getElementById(`arrow-${sector}`);
            const allSubmenus = document.querySelectorAll('.submenu');
            const allArrows = document.querySelectorAll('[id^="arrow-"]');
            allSubmenus.forEach(menu => { if (menu.id !== `submenu-${sector}`) menu.classList.remove('active'); });
            allArrows.forEach(arr => { if (arr.id !== `arrow-${sector}`) arr.style.transform = 'rotate(0deg)'; });
            submenu.classList.toggle('active');
            if (submenu.classList.contains('active')) { arrow.style.transform = 'rotate(180deg)'; }
            else { arrow.style.transform = 'rotate(0deg)'; }
        }
        // Variáveis para ajuste dinâmico
        const MODAL_MAX_WIDTH = '60vw'; // ajuste aqui
        const DASHBOARD_HEIGHT = '75vh'; // ajuste aqui

        document.getElementById('dashboard-modal').style.maxWidth = MODAL_MAX_WIDTH;

        function openDashboard(title, powerbiUrl) {
            const container = document.getElementById('dashboard-container');
            const titleElement = document.getElementById('dashboard-title');
            const frameContainer = document.getElementById('dashboard-frame');
            titleElement.textContent = title;
            frameContainer.innerHTML = `
                <iframe 
                    title="${title}" 
                    width="100%" 
                    height="${DASHBOARD_HEIGHT}" 
                    src="${powerbiUrl}" 
                    frameborder="0" 
                    allowFullScreen="true"
                    style="border: none; border-radius: 18px; width: 100%; height: ${DASHBOARD_HEIGHT}; background: #fff;">
                </iframe>
            `;
            container.classList.remove('hidden');
            container.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        function closeDashboard() {
            const container = document.getElementById('dashboard-container');
            const frameContainer = document.getElementById('dashboard-frame');
            container.classList.add('hidden');
            frameContainer.innerHTML = '';
        }
        function openUserModal() {
            document.getElementById('userModal').classList.remove('hidden');
        }
        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
        }
        function openPermissoesModal() {
            document.getElementById('permissoesModal').classList.remove('hidden');
        }
        function closePermissoesModal() {
            document.getElementById('permissoesModal').classList.add('hidden');
        }
        function openCreateUserModal() {
            document.getElementById('createUserModal').classList.remove('hidden');
        }
        function closeCreateUserModal() {
            document.getElementById('createUserModal').classList.add('hidden');
        }
        function salvarPermissoes(form) {
            var formData = new FormData(form);
            fetch('permissoes.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.text())
            .then(html => {
                alert('Permissões salvas com sucesso!');
                closePermissoesModal();
            });
            return false;
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
        // Abrir modal de usuário se a hash da URL for #userModal
        if (window.location.hash === "#userModal") {
            openUserModal();
        }
        // Preview da imagem de perfil no modal
        function previewProfilePhoto(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        const data = new Date('2025-08-18');
        const dataBR = data.toLocaleDateString('pt-BR'); // "18/08/2025"

        if (window.location.search.includes('success=1')) {
            setTimeout(() => {
                closeUserModal();
                window.history.replaceState({}, document.title, window.location.pathname + '#');
            }, 2000);
        }
    </script>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'god'): ?>
    <script>
function renderDashboardsPermissoes() {
    const dashList = document.getElementById('permissoesDashList');
    dashList.innerHTML = '';
    dashboards.forEach(d => {
        const btn = document.createElement('button');
                btn.className = "w-full flex items-center justify-between px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-900 font-semibold rounded-lg shadow transition text-base";
        btn.innerHTML = `<span>${d.nome}</span><span class="text-xs text-blue-600">${(permissoesAtuais[d.nome] ? permissoesAtuais[d.nome].length : 0)} roles</span>`;
        btn.onclick = () => openRolesModal(d.nome);
        dashList.appendChild(btn);
    });
}
renderDashboardsPermissoes();

let dashboardSelecionado = null;
function openRolesModal(dashNome) {
    dashboardSelecionado = dashNome;
    document.getElementById('rolesModalTitle').textContent = "Permissões para: " + dashNome;
    const checkboxes = document.getElementById('rolesCheckboxes');
    checkboxes.innerHTML = '';
    roles.forEach(role => {
        const checked = permissoesAtuais[dashNome] && permissoesAtuais[dashNome].includes(role) ? 'checked' : '';
        checkboxes.innerHTML += `
            <label class="flex items-center gap-2">
                <input type="checkbox" name="role" value="${role}" ${checked} class="accent-blue-700">
                <span>${role}</span>
            </label>
        `;
    });
    document.getElementById('rolesModal').classList.remove('hidden');
}
function closeRolesModal() {
    document.getElementById('rolesModal').classList.add('hidden');
}
function salvarRolesDashboard() {
    const form = document.getElementById('rolesForm');
    const formData = new FormData();
    formData.append('dashboard', dashboardSelecionado);
    form.querySelectorAll('input[type=checkbox][name=role]').forEach(cb => {
        if (cb.checked) formData.append('roles[]', cb.value);
    });
    fetch('permissoes.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        permissoesAtuais[dashboardSelecionado] = data.roles;
        closeRolesModal();
        renderDashboardsPermissoes();
        alert('Permissões salvas!');
    });
    return false;
}
    </script>
    <?php endif; ?>
<script>
setTimeout(function() {
    location.reload(true);
}, 1200 * 1000); // 20 minutos em milissegundos
</script>
</body>
</html>
