<?php
session_start();
$conn = new mysqli("127.0.0.1:3307", "root", "", "onepage");

$error = '';
$showChangePasswordModal = false;
$showForgotModal = false;
$forgotMsg = '';

// LOGIN por usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND status='ativo' LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['profile_photo'] = $user['profile_photo'];
            $_SESSION['department'] = $user['department'];
            $_SESSION['email'] = $user['email'];
            if ($user['must_change_password']) {
                $showChangePasswordModal = true;
            } else {
                header("Location: onepage.php");
                exit();
            }
        } else {
            $error = "Senha incorreta.";
        }
    } else {
        $error = "Usuário não encontrado.";
    }
}

// CADASTRO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $nomeCompleto = trim($_POST['username']);
    $username = $conn->real_escape_string(gerarUsuario($nomeCompleto));
    $department = $conn->real_escape_string($_POST['department']);
    $email = $conn->real_escape_string($_POST['email']);

    $password = password_hash('123456', PASSWORD_DEFAULT);

    $check = $conn->query("SELECT id FROM users WHERE username='$username' LIMIT 1");
    if ($check && $check->num_rows > 0) {
        $error = "Já existe uma conta com este nome de usuário.";
    } else {
        // Salva como pendente
        $sql = "INSERT INTO users (username, email, password, department, role, must_change_password, status, nome_completo) 
                VALUES ('$username', '$email', '$password', '$department', 'user', 1, 'pendente', '{$conn->real_escape_string($nomeCompleto)}')";
        if ($conn->query($sql) === TRUE) {
            $user_id = $conn->insert_id;

            // Envia e-mail para os responsáveis (oculto para o usuário)
            require_once __DIR__ . '/vendor/autoload.php';
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'email-ssl.com.br';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'ti@souzacomercio.com.br';
                $mail->Password   = '@Souza159@';
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                $mail->setFrom('ti@souzacomercio.com.br', 'Comercial Souza');
                $mail->addAddress('ti@souzacomercio.com.br');
                $mail->addAddress('analisededados1@comercialsouzaatacado.com.br');
                $mail->addAddress('facilities@comercialsouzaatacado.com.br');

                
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = "Solicitação de nova conta no Dashboard BI";
                $approveLink = "https://souzacomercio.dyndns.org:444/bi/aprova_usuario.php?id=$user_id&action=aprovar";
                $denyLink = "https://souzacomercio.dyndns.org:444/bi/aprova_usuario.php?id=$user_id&action=recusar";
                $mail->Body = '
                    <table style="font-family:Arial,sans-serif;max-width:540px;background:#fff;border-radius:8px;border:1px solid #eee;padding:24px;">
                        <tr>
                            <td style="text-align:center;">
                                <img src="https://souzacomercio.dyndns.org:444/bi/img/logo.svg" alt="Logo Souza" style="width:110px;margin-bottom:18px;">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h2 style="color:#254c90;margin-bottom:12px;">Solicitação de Nova Conta</h2>
                                <p style="font-size:15px;color:#222;">
                                    <b>Nome:</b> '.htmlspecialchars($user['username']).'<br>
                                    <b>Email:</b> <a href="mailto:'.htmlspecialchars($user['email']).'">'.htmlspecialchars($user['email']).'</a><br>
                                    <b>Departamento:</b> '.htmlspecialchars($user['department']).'<br>
                                    <b>Usuário sugerido:</b> <span style="color:#2563eb;font-weight:bold;">'.gerarUsuario($user['username']).'</span>
                                </p>
                                <div style="margin:18px 0;">
                                    <a href="'.$approveLink.'" style="background:#22c55e;color:#fff;text-decoration:none;padding:12px 24px;border-radius:6px;font-weight:bold;margin-right:12px;display:inline-block;">Aprovar Conta</a>
                                    <a href="'.$denyLink.'" style="background:#ef4444;color:#fff;text-decoration:none;padding:12px 24px;border-radius:6px;font-weight:bold;display:inline-block;">Recusar Conta</a>
                                </div>
                                <p style="font-size:13px;color:#666;">Ou responda este e-mail com <b>APROVAR</b> ou <b>RECUSAR</b> para processar manualmente.</p>
                            </td>
                        </tr>
                    </table>
                ';
                $mail->send();
            } catch (Exception $e) {
                // Você pode logar o erro se quiser
            }
            $error = "Solicitação enviada! Aguarde aprovação do TI.";
        } else {
            $error = "Erro ao criar conta: " . $conn->error;
        }
    }
}

// TROCA DE SENHA (primeiro acesso)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $user_id = $_SESSION['user_id'] ?? null;
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    if ($user_id && strlen($new_password) >= 6 && $new_password === $confirm_password) {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$hash', must_change_password=0 WHERE id='$user_id'");
        header("Location: onepage.php");
        exit();
    } else {
        $error = "As senhas não coincidem ou são muito curtas.";
        $showChangePasswordModal = true;
    }
}

// ESQUECEU A SENHA (simples: mostra o e-mail cadastrado, não envia e-mail real)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot'])) {
    $username = $conn->real_escape_string($_POST['forgot_username']);
    $result = $conn->query("SELECT email FROM users WHERE username='$username' LIMIT 1");
    if ($result && $row = $result->fetch_assoc()) {
        if (!empty($row['email'])) {
            $forgotMsg = "O e-mail cadastrado para este usuário é: <b>{$row['email']}</b><br>Entre em contato com o administrador para redefinir sua senha.";
        } else {
            $forgotMsg = "Usuário não possui e-mail cadastrado. Contate o administrador.";
        }
    } else {
        $forgotMsg = "Usuário não encontrado.";
    }
    $showForgotModal = true;
}

function gerarUsuario($nomeCompleto) {
    $partes = preg_split('/\s+/', trim($nomeCompleto));
    if (count($partes) < 2) return strtolower($partes[0]);
    $primeiro = strtolower($partes[0]);
    $excecoes = ['da', 'de', 'do', 'das', 'dos', 'e'];
    // Procura o último nome que não seja exceção
    for ($i = count($partes) - 1; $i > 0; $i--) {
        if (!in_array(strtolower($partes[$i]), $excecoes)) {
            $ultimo = strtolower($partes[$i]);
            break;
        }
    }
    return $primeiro . '.' . $ultimo;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dashboard BI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); }
        .login-card { backdrop-filter: blur(16px); background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(148, 163, 184, 0.2);}
        .input-focus:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);}
        .btn-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);}
        .floating-animation { animation: float 6s ease-in-out infinite;}
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .pulse-animation { animation: pulse 2s infinite;}
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl floating-animation"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl floating-animation" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/2 left-1/2 w-32 h-32 bg-green-600/10 rounded-full blur-2xl floating-animation" style="animation-delay: -1.5s;"></div>
    </div>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <img src="img/logo.svg" alt="Logo" class="mx-auto mb-4" style="width:120px; height:auto; display:block;">
            <h1 class="text-3xl font-bold text-white mb-2">Dashboard BI</h1>
            <p class="text-slate-400">Faça login para acessar o sistema</p>
        </div>

        <!-- Login Card -->
        <div class="login-card rounded-2xl p-8 shadow-2xl">
            <?php if (!empty($error)): ?>
                <div class="mb-4 text-red-400 text-center font-semibold"><?= $error ?></div>
            <?php endif; ?>
            <form id="loginForm" class="space-y-6" method="POST" action="">
                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-slate-300 mb-2">
                        Usuário
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="username" 
                            name="username"
                            required
                            class="w-full pl-10 pr-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-white placeholder-slate-400 input-focus transition-all duration-200"
                            placeholder="Seu usuário"
                        >
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                        Senha
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            required
                            class="w-full pl-10 pr-12 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-white placeholder-slate-400 input-focus transition-all duration-200"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        >
                            <svg id="eye-icon" class="h-5 w-5 text-slate-400 hover:text-slate-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit" 
                    name="login"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 btn-hover"
                >
                    Entrar
                </button>
            </form>

            <!-- Forgot Password Link -->
            <div class="mt-4 text-center">
                <button 
                    type="button" 
                    onclick="openForgotModal()"
                    class="text-blue-400 hover:underline text-sm"
                >Esqueceu a senha?</button>
            </div>

            <!-- Create Account Button -->
            <div class="mt-6">
                <button 
                    type="button" 
                    onclick="openCreateAccountModal()"
                    class="w-full bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 font-medium py-3 px-4 rounded-lg border border-slate-600 transition-all duration-200 hover:border-slate-500"
                >
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Solicitar Criação de Nova Conta
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-slate-400 text-sm">
                © 2025 Comercial Souza Atacado. Todos os direitos reservados.
            </p>
        </div>
    </div>

    <!-- Modal Criar Conta -->
    <div id="createAccountModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="login-card rounded-2xl p-8 shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-white">Solicitar Nova Conta</h3>
                <button onclick="closeCreateAccountModal()" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="createAccountForm" class="space-y-4" method="POST" action="" onsubmit="return validateRegisterForm();">
                <div>
                    <label for="newName" class="block text-sm font-medium text-slate-300 mb-2">
                        Nome Completo
                    </label>
                    <input 
                        type="text" 
                        id="newName" 
                        name="username"
                        required
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-white placeholder-slate-400 input-focus transition-all duration-200"
                        placeholder="Seu nome completo"
                    >
                </div>
                <div>
                    <label for="newEmail" class="block text-sm font-medium text-slate-300 mb-2">
                        Email Corporativo
                    </label>
                    <input 
                        type="email" 
                        id="newEmail" 
                        name="email"
                        required
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-white placeholder-slate-400 input-focus transition-all duration-200"
                        placeholder="seu@empresa.com"
                    >
                </div>
                <div>
                    <label for="department" class="block text-sm font-medium text-slate-300 mb-2">
                        Departamento
                    </label>
                    <select 
                        id="department" 
                        name="department"
                        required
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-white input-focus transition-all duration-200"
                    >
                        <option value="">Selecione seu departamento</option>
                        <option value="Comercial Geral">Comercial Geral</option>
                        <option value="Compras">Compras</option>
                        <option value="Financeiro">Financeiro</option>
                        <option value="Força de Venda">Força de Venda</option>
                        <option value="Logistica">Logística</option>
                        <option value="Recursos Humanos">Recursos Humanos</option>
                        <option value="Suprimentos">Suprimentos</option>
                        <option value="TI">Tecnologia da Informação</option>
                        <option value="Diretoria">Diretoria</option>
                        <option value="Transportes">Transportes</option>
                    </select>
                </div>
                <div class="bg-slate-700/30 p-4 rounded-lg border border-slate-600">
                    <p class="text-xs text-slate-400 mb-2">
                        <strong>Senha inicial padrão: <span class="text-white">123456</span></strong>
                    </p>
                    <ul class="text-xs text-slate-400 space-y-1">
                        <li>• Você precisará trocar a senha no primeiro acesso</li>
                        <li>• Sua conta será criada imediatamente</li>
                    </ul>
                </div>
                <button 
                    type="submit" 
                    name="register"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200"
                >
                    Solicitar Conta
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Troca de Senha -->
    <?php if ($showChangePasswordModal): ?>
    <div id="changePasswordModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="login-card rounded-2xl p-8 shadow-2xl w-full max-w-md">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-white">Defina sua nova senha</h3>
                <button onclick="closeChangePasswordModal()" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form method="POST" action="" onsubmit="return validatePasswordChange();">
                <input type="password" id="new_password" name="new_password" placeholder="Nova senha" required class="w-full px-4 py-3 mb-3 bg-slate-700/50 border border-slate-600 rounded-lg text-white placeholder-slate-400 input-focus">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirme a nova senha" required class="w-full px-4 py-3 mb-3 bg-slate-700/50 border border-slate-600 rounded-lg text-white placeholder-slate-400 input-focus">
                <div id="password-error" class="text-red-400 text-sm mb-2 hidden"></div>
                <button type="submit" name="change_password" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200">Salvar nova senha</button>
            </form>
        </div>
    </div>
    <script>
    function validatePasswordChange() {
        var pass = document.getElementById('new_password').value;
        var conf = document.getElementById('confirm_password').value;
        var errorDiv = document.getElementById('password-error');
        if (pass.length < 6) {
            errorDiv.innerText = "A senha deve ter pelo menos 6 caracteres.";
            errorDiv.classList.remove('hidden');
            return false;
        }
        if (pass !== conf) {
            errorDiv.innerText = "As senhas não coincidem.";
            errorDiv.classList.remove('hidden');
            return false;
        }
        errorDiv.classList.add('hidden');
        return true;
    }
    document.body.style.overflow = 'hidden';
    function closeChangePasswordModal() {
        document.getElementById('changePasswordModal').style.display = 'none';
        document.body.style.overflow = '';
    }
    </script>
    <?php endif; ?>

    <!-- Modal Esqueceu a Senha -->
    <div id="forgotModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm <?= $showForgotModal ? 'flex' : 'hidden' ?> items-center justify-center z-50 p-4">
        <div class="login-card rounded-2xl p-8 shadow-2xl w-full max-w-md">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-white">Esqueceu a senha?</h3>
                <button onclick="closeForgotModal()" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form method="POST" action="" class="space-y-4">
                <div>
                    <label for="forgot_username" class="block text-sm font-medium text-slate-300 mb-2">
                        Informe seu usuário
                    </label>
                    <input 
                        type="text" 
                        id="forgot_username" 
                        name="forgot_username"
                        required
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-white placeholder-slate-400 input-focus transition-all duration-200"
                        placeholder="Seu usuário"
                    >
                </div>
                <button type="submit" name="forgot" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200">Consultar</button>
            </form>
            <?php if (!empty($forgotMsg)): ?>
                <div class="mt-4 text-slate-200 text-center"><?= $forgotMsg ?></div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268-2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
        function openCreateAccountModal() {
            document.getElementById('createAccountModal').classList.remove('hidden');
            document.getElementById('createAccountModal').classList.add('flex');
        }
        function closeCreateAccountModal() {
            document.getElementById('createAccountModal').classList.add('hidden');
            document.getElementById('createAccountModal').classList.remove('flex');
        }
        function validateRegisterForm() {
            return true;
        }
        function openForgotModal() {
            document.getElementById('forgotModal').classList.remove('hidden');
            document.getElementById('forgotModal').classList.add('flex');
        }
        function closeForgotModal() {
            document.getElementById('forgotModal').classList.add('hidden');
            document.getElementById('forgotModal').classList.remove('flex');
        }
        <?php if ($showForgotModal): ?>
        openForgotModal();
        <?php endif; ?>
    </script>
</body>
</html>
