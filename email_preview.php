<?php
// Removido o PHP de envio e os botões de envio
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Preview - E-mails Dashboard BI</title>
    <style>
        body { background: #f3f4f6; font-family: Arial, sans-serif; padding: 0; margin: 0; }
        .preview-area { max-width: 600px; margin: 40px auto 0 auto; }
    </style>
</head>
<body>
    <div class="preview-area">

        <!-- Card Solicitação de Nova Conta -->
        <table width="600" height="300" cellpadding="0" cellspacing="0" border="0" style="border-radius:12px; overflow:hidden; margin:40px auto; font-family:Arial,sans-serif;">
          <tr>
            <td width="600" height="300" valign="top" style="padding:0; margin:0;">
              <!--[if gte mso 9]>
              <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:600px;height:300px;">
                <v:fill type="frame" src="https://raw.githubusercontent.com/SouzaTI/Assinatura_HTML/main/onepage.png" color="#ffffff" />
                <v:textbox inset="0,0,0,0">
              <![endif]-->
              <table width="600" height="300" cellpadding="0" cellspacing="0" border="0" style="background:url('https://raw.githubusercontent.com/SouzaTI/Assinatura_HTML/main/onepage.png') no-repeat top center; background-size:600px 300px;">
                <tr>
                  <td align="center" valign="top" style="padding-top:60px;">
                    <table width="90%" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td align="center" style="font-size:24px; color:#254c90; font-weight:bold; padding-bottom:12px;">
                          Solicitação de Nova Conta
                        </td>
                      </tr>
                      <tr>
                        <td align="center" style="font-size:15px; color:#222; padding-bottom:8px;">
                          <b>Nome:</b> Matheus Cabral de Souza
                        </td>
                      </tr>
                      <tr>
                        <td align="center" style="font-size:15px; color:#222; padding-bottom:8px;">
                          <b>Email:</b> <a href="mailto:analisededados1@comercialsouzaatacado.com.br" style="color:#2563eb;">analisededados1@comercialsouzaatacado.com.br</a>
                        </td>
                      </tr>
                      <tr>
                        <td align="center" style="font-size:15px; color:#222; padding-bottom:18px;">
                          <b>Departamento:</b> TI
                        </td>
                      </tr>
                      <tr>
                        <td align="center" style="padding-bottom:18px;">
                          <!-- Botões como tabelas para Outlook -->
                          <table cellpadding="0" cellspacing="0" border="0" style="display:inline-block;">
                            <tr>
                              <td style="background:#22c55e; border-radius:6px; padding:8px 24px; font-size:15px; color:#fff; font-weight:bold; text-align:center;">
                                Aprovar
                              </td>
                              <td width="12"></td>
                              <td style="background:#ef4444; border-radius:6px; padding:8px 24px; font-size:15px; color:#fff; font-weight:bold; text-align:center;">
                                Recusar
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td align="center" style="font-size:13px; color:#444;">
                          Responda este e-mail com <b>APROVAR</b> ou <b>RECUSAR</b> para processar manualmente.
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

        <!-- Card Conta Aprovada -->
        <table width="600" height="300" cellpadding="0" cellspacing="0" border="0" style="border-radius:12px; overflow:hidden; margin:40px auto;">
          <tr>
            <td width="600" height="300" valign="top" style="padding:0; margin:0;">
              <!--[if gte mso 9]>
              <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:600px;height:300px;">
                <v:fill type="frame" src="https://raw.githubusercontent.com/SouzaTI/Assinatura_HTML/main/onepage.png" color="#ffffff" />
                <v:textbox inset="0,0,0,0">
              <![endif]-->
              <div style="width:600px;height:300px;background:url('https://raw.githubusercontent.com/SouzaTI/Assinatura_HTML/main/onepage.png') no-repeat top center; background-size:600px 300px;">
                <table width="100%" height="300" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td align="center" valign="top" style="padding-top:70px;">
                      <span style="font-size:22px; color:#254c90; font-weight:bold; font-family:Arial, sans-serif;">Conta Aprovada</span>
                      <div style="font-size:15px; color:#222; line-height:1.7; margin:18px 0 10px 0; font-family:Arial, sans-serif;">
                        Olá <b>Matheus Cabral de Souza</b>,<br>
                        Sua conta no Dashboard BI foi aprovada.<br>
                        <b>Usuário:</b> <span style="color:#2563eb;">matheus.souza</span><br>
                        <b>Senha inicial:</b> 123456<br>
                        <span style="color:#d97706; font-size:14px;">Por segurança, altere sua senha no primeiro acesso.</span>
                      </div>
                      <div style="font-size:13px; color:#444; text-align:center; margin-top:10px; font-family:Arial, sans-serif;">
                        Acesse: <a href="https://souzacomercio.dyndns.org:444/bi/login.php" style="color:#2563eb;">Dashboard BI</a>
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
              <!--[if gte mso 9]>
                </v:textbox>
              </v:rect>
              <![endif]-->
            </td>
          </tr>
        </table>

        <!-- Card Conta Recusada -->
        <table width="600" height="300" cellpadding="0" cellspacing="0" border="0" style="border-radius:12px; overflow:hidden; margin:40px auto;">
          <tr>
            <td width="600" height="300" valign="top" style="padding:0; margin:0;">
              <!--[if gte mso 9]>
              <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:600px;height:300px;">
                <v:fill type="frame" src="https://raw.githubusercontent.com/SouzaTI/Assinatura_HTML/main/onepage.png" color="#ffffff" />
                <v:textbox inset="0,0,0,0">
              <![endif]-->
              <div style="width:600px;height:300px;background:url('https://raw.githubusercontent.com/SouzaTI/Assinatura_HTML/main/onepage.png') no-repeat top center; background-size:600px 300px;">
                <table width="100%" height="300" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td align="center" valign="top" style="padding-top:70px;">
                      <span style="font-size:22px; color:#ef4444; font-weight:bold; font-family:Arial, sans-serif;">Conta Recusada</span>
                      <div style="font-size:15px; color:#222; line-height:1.7; margin:18px 0 10px 0; font-family:Arial, sans-serif;">
                        Olá <b>Matheus Cabral de Souza</b>,<br>
                        Sua solicitação de conta no Dashboard BI foi <b>recusada</b>.<br>
                        Em caso de dúvidas, entre em contato com o setor responsável.
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
              <!--[if gte mso 9]>
                </v:textbox>
              </v:rect>
              <![endif]-->
            </td>
          </tr>
        </table>

    </div>
</body>
</html>