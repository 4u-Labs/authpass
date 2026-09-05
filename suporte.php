<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
$v = time();
$msgSent = false;
$msgError = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $assunto = trim($_POST["assunto"] ?? "Contato - AuthPass");
    $mensagem = trim($_POST["mensagem"] ?? "");

    if (!empty($nome) && !empty($email) && !empty($mensagem)) {
        $logDir = __DIR__ . "/uploads";
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $logFile = $logDir . "/messages_log.json";
        $logs = [];
        if (file_exists($logFile)) {
            $logs = json_decode(file_get_contents($logFile), true) ?: [];
        }
        $logs[] = [
            "timestamp" => date("Y-m-d H:i:s"),
            "nome" => $nome,
            "email" => $email,
            "assunto" => $assunto,
            "mensagem" => $mensagem,
            "ip" => $_SERVER["REMOTE_ADDR"] ?? "127.0.0.1"
        ];
        @file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Envio do e-mail oficial
        $to = "contato@4u.ia.br";
        $subject = "Suporte AuthPass: " . $assunto;
        $body = "Nome: $nome\nE-mail: $email\nData: " . date("d/m/Y H:i:s") . "\nAssunto: $assunto\n\nMensagem:\n$mensagem";
        $headers = "From: contato@4u.ia.br\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();
        @mail($to, $subject, $body, $headers);
        $msgSent = true;
    } else {
        $msgError = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Central de Suporte & FAQ — AuthPass</title>
  <meta name="description" content="Central de Ajuda, Suporte e Perguntas Frequentes do AuthPass. Tire dúvidas ou fale com nossa equipe.">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      background: #0b0f19;
      color: #f1f5f9;
      font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      line-height: 1.7;
    }
    .navbar {
      height: 64px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      background: rgba(15, 23, 42, 0.85);
      backdrop-filter: blur(12px);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 24px;
    }
    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      font-weight: 600;
      color: #94a3b8;
      text-decoration: none;
      padding: 8px 14px;
      border-radius: 8px;
      background: #131d31;
      border: 1px solid rgba(255,255,255,0.1);
      transition: all 0.2s;
    }
    .back-btn:hover { color: #fff; border-color: #6366f1; transform: translateX(-2px); }
    .brand-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      color: #fff;
      font-weight: 800;
      font-size: 17px;
    }
    .brand-icon {
      width: 34px;
      height: 34px;
      border-radius: 10px;
      background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
    }
    .main-container {
      max-width: 900px;
      margin: 32px auto;
      padding: 0 16px;
      width: 100%;
    }
    .support-card {
      background: rgba(19, 29, 49, 0.75);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 36px 40px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.5);
      margin-bottom: 32px;
    }
    .support-header {
      margin-bottom: 28px;
      padding-bottom: 20px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .support-header h1 {
      font-size: 2rem;
      font-weight: 800;
      background: linear-gradient(135deg, #38bdf8, #6366f1, #ec4899);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 8px;
    }
    .support-header p { font-size: 0.9rem; color: #94a3b8; }
    .faq-item {
      margin-bottom: 20px;
      padding: 18px 20px;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.06);
      border-radius: 12px;
    }
    .faq-question {
      font-size: 1.05rem;
      font-weight: 700;
      color: #e2e8f0;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .faq-question i { color: #38bdf8; font-size: 0.95rem; }
    .faq-answer { font-size: 0.9rem; color: #94a3b8; }
    .form-group {
      margin-bottom: 18px;
    }
    label {
      display: block;
      font-size: 0.85rem;
      font-weight: 600;
      color: #cbd5e1;
      margin-bottom: 6px;
    }
    input, textarea {
      width: 100%;
      padding: 12px 16px;
      background: rgba(11, 15, 25, 0.8);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 10px;
      color: #fff;
      font-family: inherit;
      font-size: 0.95rem;
      outline: none;
      transition: all 0.2s;
    }
    input:focus, textarea:focus {
      border-color: #6366f1;
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }
    .btn-submit {
      width: 100%;
      padding: 14px;
      border-radius: 10px;
      background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
      color: #fff;
      font-weight: 700;
      font-size: 1rem;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.2s;
    }
    .btn-submit:hover {
      filter: brightness(1.1);
      transform: translateY(-1px);
    }
    .alert {
      padding: 14px 18px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .alert-success {
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid rgba(16, 185, 129, 0.3);
      color: #34d399;
    }
    .alert-error {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: #f87171;
    }
    footer {
      margin-top: auto;
      text-align: center;
      padding: 24px;
      font-size: 0.85rem;
      color: #64748b;
      border-top: 1px solid rgba(255,255,255,0.05);
    }
    @media (max-width: 640px) {
      .support-card { padding: 24px 20px; }
      .support-header h1 { font-size: 1.5rem; }
    }
  </style>
</head>
<body>
  <header class="navbar">
    <a href="./" class="brand-logo">
      <div class="brand-icon"><i class="fas fa-shield-halved"></i></div>
      <span>AuthPass</span>
    </a>
    <a href="./" class="back-btn"><i class="fas fa-arrow-left"></i> Voltar ao App</a>
  </header>

  <div class="main-container">
    <div class="support-card">
      <div class="support-header">
        <h1>Central de Suporte & FAQ</h1>
        <p>Respostas rápidas e canal direto com os desenvolvedores da 4U.IA.BR</p>
      </div>

      <h2 style="font-size: 1.25rem; font-weight: 700; color: #e2e8f0; margin-bottom: 16px;">
        <i class="fas fa-circle-question" style="color: #38bdf8;"></i> Perguntas Frequentes
      </h2>

      <div class="faq-item">
        <div class="faq-question"><i class="fas fa-shield-virus"></i> O que acontece se eu perder o celular ou deletar o app?</div>
        <div class="faq-answer">Se você conectou sua conta Google no AuthPass, seus dados estão salvos com criptografia na pasta isolada do seu Google Drive. Basta abrir o AuthPass em qualquer dispositivo, fazer login com o mesmo Gmail e digitar o seu PIN ou Chave de Emergência para restaurar instantaneamente todos os seus códigos 2FA.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question"><i class="fas fa-key"></i> Para que serve a Chave Mestra de Emergência?</div>
        <div class="faq-answer">É a sua tábua de salvação caso esqueça o PIN de desbloqueio. Essa chave de alta entropia permite derivar a chave mestre do cofre e resetar o seu PIN de acesso com segurança total.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question"><i class="fas fa-wifi"></i> O AuthPass funciona sem conexão com a internet?</div>
        <div class="faq-answer">Sim! Como PWA, o AuthPass é totalmente executável offline. O cálculo do TOTP (RFC 6238) depende exclusivamente do relógio interno do seu dispositivo.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question"><i class="fas fa-server"></i> Meus códigos 2FA ficam salvos no servidor da 4U.IA.BR?</div>
        <div class="faq-answer">Não. Operamos com arquitetura estrita de Retenção Zero e Conhecimento Zero (Zero-Knowledge). Nenhum segredo seu trafega ou fica armazenado nos nossos servidores.</div>
      </div>
    </div>

    <div class="support-card">
      <h2 style="font-size: 1.25rem; font-weight: 700; color: #e2e8f0; margin-bottom: 20px;">
        <i class="fas fa-envelope" style="color: #6366f1;"></i> Enviar Mensagem para o Suporte
      </h2>

      <?php if ($msgSent): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i> Mensagem enviada com sucesso! Nossa equipe responderá em breve pelo seu e-mail.
        </div>
      <?php elseif ($msgError): ?>
        <div class="alert alert-error">
          <i class="fas fa-exclamation-triangle"></i> Por favor, preencha todos os campos do formulário.
        </div>
      <?php endif; ?>

      <form method="POST" action="suporte.php">
        <div class="form-group">
          <label for="nome">Seu Nome Completo</label>
          <input type="text" id="nome" name="nome" required placeholder="Ex: Maria Silva">
        </div>
        <div class="form-group">
          <label for="email">Seu E-mail para Retorno</label>
          <input type="email" id="email" name="email" required placeholder="Ex: maria@gmail.com">
        </div>
        <div class="form-group">
          <label for="assunto">Assunto</label>
          <input type="text" id="assunto" name="assunto" value="Dúvida / Suporte - AuthPass" required>
        </div>
        <div class="form-group">
          <label for="mensagem">Mensagem / Relato</label>
          <textarea id="mensagem" name="mensagem" rows="5" required placeholder="Descreva sua dúvida, sugestão ou dificuldade técnica..."></textarea>
        </div>
        <button type="submit" class="btn-submit">
          <i class="fas fa-paper-plane"></i> Enviar Mensagem
        </button>
      </form>
    </div>
  </div>

  <footer>
    &copy; <?php echo date("Y"); ?> 4U.IA.BR &bull; AuthPass &bull; Todos os direitos reservados.
  </footer>
</body>
</html>
