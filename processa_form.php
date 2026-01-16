<?php
// Conexão com o banco
$conn = new mysqli('127.0.0.1:3307', 'root', '', 'formcompras');
if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}

// Função para converter em maiúsculo
function to_upper($str) {
    return mb_strtoupper($str, 'UTF-8');
}

// Função para pegar valor do POST de forma segura
function get_post($key, $default = '') {
    return isset($_POST[$key]) && trim($_POST[$key]) !== '' ? $_POST[$key] : $default;
}

// Recebe e trata os dados do formulário
$solicitante   = to_upper(get_post('nome', 'NÃO INFORMADO'));
$produto       = to_upper(get_post('produto', 'NÃO INFORMADO'));
$quantidade    = intval(get_post('quantidade', 0));
$fornecedor    = to_upper(get_post('fornecedor', 'NÃO INFORMADO'));
$data_br       = get_post('data', date('d/m/Y H:i'));
$data          = DateTime::createFromFormat('d/m/Y H:i', $data_br) ? DateTime::createFromFormat('d/m/Y H:i', $data_br)->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
$email         = get_post('email', '');
$departamento  = to_upper(get_post('departamento', 'NÃO INFORMADO'));
$quemAprovou   = to_upper(get_post('quemAprovou', 'NÃO INFORMADO'));
$protocolo     = get_post('protocolo', 'NÃO INFORMADO');

// Salva no banco
$stmt = $conn->prepare("INSERT INTO requisicoes (solicitante, produto, quantidade, fornecedor, data_solicitacao, email, departamento, quem_aprovou, protocolo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssissssss", $solicitante, $produto, $quantidade, $fornecedor, $data, $email, $departamento, $quemAprovou, $protocolo);
$stmt->execute();

// Monta mensagem do e-mail em HTML
$subject = "Comprovante de Requisição - Protocolo $protocolo";
$message = '
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
          <td align="center" valign="top" style="padding-top:60px;">
            <table width="90%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td align="center" style="font-size:22px; color:#254c90; font-weight:bold; padding-bottom:12px;">
                  Comprovante de Requisição
                </td>
              </tr>
              <tr>
                <td align="center" style="font-size:15px; color:#222; padding-bottom:8px;">
                  <b>Protocolo:</b> ' . htmlspecialchars($protocolo) . '<br>
                  <b>Solicitante:</b> ' . htmlspecialchars($solicitante) . '<br>
                  <b>Produto/Trabalho:</b> ' . htmlspecialchars($produto) . '<br>
                  <b>Quantidade:</b> ' . htmlspecialchars($quantidade) . '<br>
                  <b>Fornecedor:</b> ' . htmlspecialchars($fornecedor) . '<br>
                  <b>Data da Solicitação:</b> ' . htmlspecialchars($data_br) . '<br>
                  <b>E-mail para contato:</b> ' . htmlspecialchars($email) . '<br>
                  <b>Departamento:</b> ' . htmlspecialchars($departamento) . '<br>
                  <b>Quem aprovou:</b> ' . htmlspecialchars($quemAprovou) . '
                </td>
              </tr>
              <tr>
                <td align="center" style="font-size:13px; color:#444;">
                  Este é um comprovante gerado automaticamente pelo sistema de compras.<br>
                  Em caso de dúvidas, entre em contato com o setor responsável.
                </td>
              </tr>
            </table>
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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Envia e-mail de confirmação para todos os e-mails informados
if (!empty($email)) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'email-ssl.com.br';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ti@souzacomercio.com.br';
        $mail->Password   = '@Souza159@';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('ti@souzacomercio.com.br', 'Comercial Souza');
        // Permite múltiplos e-mails separados por vírgula
        foreach (explode(',', $email) as $dest) {
            $dest = trim($dest);
            if (filter_var($dest, FILTER_VALIDATE_EMAIL)) {
                $mail->addAddress($dest);
            }
        }
        $mail->addAddress('ti@souzacomercio.com.br'); // Cópia para empresa

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
    } catch (Exception $e) {
        // error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
    }
}

echo "OK";
?>