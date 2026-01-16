<?php
$conn = new mysqli("127.0.0.1:3307", "root", "", "onepage");
$id = intval($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';

if ($id && in_array($action, ['aprovar', 'recusar'])) {
    $user = $conn->query("SELECT * FROM users WHERE id='$id'")->fetch_assoc();
    if (!$user) {
        echo "Usuário não encontrado.";
        exit;
    }

    require_once __DIR__ . '/vendor/autoload.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    $nome = $user['nome_completo'];
    $usuario = $user['username'];
    $senha_inicial = '123456'; // Sempre a senha padrão

    if ($action == 'aprovar') {
        $conn->query("UPDATE users SET status='ativo' WHERE id=$id");

        // Envia e-mail para o usuário aprovado
        if (!empty($user['email'])) {
            try {
                $mail->isSMTP();
                $mail->Host       = 'email-ssl.com.br';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'ti@souzacomercio.com.br';
                $mail->Password   = '@Souza159@';
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                $mail->setFrom('ti@souzacomercio.com.br', 'Comercial Souza');
                $mail->addAddress($user['email']);

                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = "Sua conta no Dashboard BI foi aprovada!";

                $body = '
                <table width="600" height="300" cellpadding="0" cellspacing="0" border="0" style="border-radius:12px; overflow:hidden; margin:40px auto; font-family:Arial,sans-serif;">
                  <tr>
                    <td width="600" height="300" valign="top" style="padding:0; margin:0;">
                      <!--[if gte mso 9]>
                      <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:600px;height:300px;">
                        <v:fill type="frame" src="https://raw.githubusercontent.com/SouzaTI/Assinatura_HTML/main/onepage.png" color="#ffffff" />
                        <v:textbox inset="0,0,0,0">
                      <![endif]-->
                      <table width="600" height="300" cellpadding="0" cellspacing="0" border="0" style="background:url(\'https://raw.githubusercontent.com/SouzaTI/Assinatura_HTML/main/onepage.png\') no-repeat top center; background-size:600px 300px;">
                        <tr>
                          <td align="center" valign="top" style="padding-top:70px;">
                            <span style="font-size:22px; color:#254c90; font-weight:bold; font-family:Arial, sans-serif;">Conta Aprovada</span>
                            <div style="font-size:15px; color:#222; line-height:1.7; margin:18px 0 10px 0; font-family:Arial, sans-serif;">
                              Olá <b>' . htmlspecialchars($nome) . '</b>,<br>
                              Sua conta no Dashboard BI foi aprovada.<br>
                              <b>Usuário:</b> <span style="color:#2563eb;">' . htmlspecialchars($usuario) . '</span><br>
                              <b>Senha inicial:</b> ' . htmlspecialchars($senha_inicial) . '<br>
                              <span style="color:#d97706; font-size:14px;">Por segurança, altere sua senha no primeiro acesso.</span>
                            </div>
                            <div style="font-size:13px; color:#444; text-align:center; margin-top:10px; font-family:Arial, sans-serif;">
                              Acesse: <a href="https://souzacomercio.dyndns.org:444/bi/login.php" style="color:#2563eb;">Dashboard BI</a>
                            </div>
                          </td>
                        </tr>
                      </table>
                      <!--[if gte mso 9]>
                        </v:textbox>
                      </v:rect>
                      <![endif]-->
                    </td>
                  </tr>
                </table>
                ';
                $mail->Body = $body;
                $mail->send();
            } catch (Exception $e) {
                // log error se quiser
            }
        }
        echo "<h2>Conta aprovada! O usuário foi notificado por e-mail.</h2>";
    } else {
        $conn->query("DELETE FROM users WHERE id=$id");

        // Envia e-mail para o usuário recusado
        if (!empty($user['email'])) {
            try {
                $mail->isSMTP();
                $mail->Host       = 'email-ssl.com.br';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'ti@souzacomercio.com.br';
                $mail->Password   = '@Souza159@';
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                $mail->setFrom('ti@souzacomercio.com.br', 'Comercial Souza');
                $mail->addAddress($user['email']);

                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = "Solicitação de conta recusada";
                $body = '
                <table width="600" height="300" cellpadding="0" cellspacing="0" border="0" style="border-radius:12px; overflow:hidden; margin:40px auto; font-family:Arial,sans-serif;">
                  <tr>
                    <td width="600" height="300" valign="top" style="padding:0; margin:0;">
                      <!--[if gte mso 9]>
                      <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:600px;height:300px;">
                        <v:fill type="frame" src="https://raw.githubusercontent.com/SouzaTI/Assinatura_HTML/main/onepage.png" color="#ffffff" />
                        <v:textbox inset="0,0,0,0">
                      <![endif]-->
                      <table width="600" height="300" cellpadding="0" cellspacing="0" border="0" style="background:url(\'https://raw.githubusercontent.com/SouzaTI/Assinatura_HTML/main/onepage.png\') no-repeat top center; background-size:600px 300px;">
                        <tr>
                          <td align="center" valign="top" style="padding-top:70px;">
                            <span style="font-size:22px; color:#ef4444; font-weight:bold; font-family:Arial, sans-serif;">Conta Recusada</span>
                            <div style="font-size:15px; color:#222; line-height:1.7; margin:18px 0 10px 0; font-family:Arial, sans-serif;">
                              Olá <b>' . htmlspecialchars($nome) . '</b>,<br>
                              Sua solicitação de conta no Dashboard BI foi <b>recusada</b>.<br>
                              Em caso de dúvidas, entre em contato com o setor responsável.
                            </div>
                          </td>
                        </tr>
                      </table>
                      <!--[if gte mso 9]>
                        </v:textbox>
                      </v:rect>
                      <![endif]-->
                    </td>
                  </tr>
                </table>
                ';
                $mail->Body = $body;
                $mail->send();
            } catch (Exception $e) {
                // log error se quiser
            }
        }
        echo "<h2>Conta recusada e removida! O usuário foi notificado por e-mail.</h2>";
    }
} else {
    echo "Solicitação inválida.";
}
?>