<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
$v = time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Termos de Uso — AuthPass</title>
  <meta name="description" content="Termos e condições de uso do AuthPass. Segurança, custódia de senhas e responsabilidade de backup de tokens 2FA.">
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
    .legal-container {
      max-width: 860px;
      margin: 32px auto;
      padding: 36px 40px;
      background: rgba(19, 29, 49, 0.75);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.5);
    }
    .legal-header {
      margin-bottom: 28px;
      padding-bottom: 20px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .legal-header h1 {
      font-size: 2rem;
      font-weight: 800;
      background: linear-gradient(135deg, #38bdf8, #6366f1, #ec4899);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 8px;
    }
    .legal-header p { font-size: 0.9rem; color: #94a3b8; }
    h2 { font-size: 1.25rem; font-weight: 700; color: #e2e8f0; margin: 24px 0 10px; display: flex; align-items: center; gap: 8px; }
    h2 i { color: #38bdf8; font-size: 1rem; }
    p, ul { font-size: 0.925rem; color: #94a3b8; margin-bottom: 16px; }
    ul { padding-left: 20px; }
    li { margin-bottom: 8px; }
    footer {
      margin-top: auto;
      text-align: center;
      padding: 24px;
      font-size: 0.85rem;
      color: #64748b;
      border-top: 1px solid rgba(255,255,255,0.05);
    }
    @media (max-width: 640px) {
      .legal-container { padding: 24px 20px; margin: 16px; }
      .legal-header h1 { font-size: 1.5rem; }
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

  <main style="flex: 1; padding: 0 16px;">
    <div class="legal-container">
      <div class="legal-header">
        <h1>Termos de Uso</h1>
        <p>Condições gerais de serviço &bull; Versão 1.0 (Setembro de <?php echo date("Y"); ?>)</p>
      </div>

      <h2><i class="fas fa-check-circle"></i> 1. Aceitação dos Termos</h2>
      <p>Ao utilizar o <strong>AuthPass</strong>, desenvolvido pela <strong>4U.IA.BR</strong>, você concorda expressamente com os presentes Termos de Uso. Caso não concorde com qualquer disposição aqui estabelecida, recomendamos a descontinuação imediata do uso do aplicativo.</p>

      <h2><i class="fas fa-shield-halved"></i> 2. Natureza da Aplicação & Não-Custódia</h2>
      <p>O AuthPass é um gerador de senhas descartáveis baseadas em tempo (TOTP - RFC 6238) executado no lado do cliente (*client-side*). Em razão do modelo Zero-Knowledge:</p>
      <ul>
        <li>A 4U.IA.BR <strong>não possui custódia</strong> do seu PIN, Senha Mestra, Chave de Emergência ou chaves secretas TOTP.</li>
        <li>Não temos capacidade técnica de redefinir sua senha mestra nem de recuperar contas caso você perca simultaneamente seu PIN e sua Chave de Emergência.</li>
      </ul>

      <h2><i class="fas fa-triangle-exclamation"></i> 3. Responsabilidade do Usuário</h2>
      <p>O usuário é o único responsável pela guarda segura de suas credenciais, devendo:</p>
      <ul>
        <li>Anotar e manter em local seguro a <strong>Chave Mestra de Emergência</strong> gerada no momento da criação do cofre.</li>
        <li>Realizar exportações de backup periódicas (arquivo criptografado <code>.authpass</code>) ou manter a sincronização com o Google Drive ativa.</li>
        <li>Garantir que os dispositivos onde o aplicativo é executado estejam livres de malwares, keyloggers ou acessos não autorizados.</li>
      </ul>

      <h2><i class="fas fa-scale-balanced"></i> 4. Limitação de Responsabilidade</h2>
      <p>A aplicação é fornecida "como está" (*as is*). A 4U.IA.BR empenha as melhores práticas de engenharia de software e criptografia moderna, mas não se responsabiliza por perdas decorrentes de esquecimento de senhas pelo usuário, falhas em dispositivos locais ou perda de acesso às contas de terceiros.</p>

      <h2><i class="fas fa-comments"></i> 5. Dúvidas e Atendimento</h2>
      <p>Em caso de dúvidas sobre o funcionamento do AuthPass, utilize nossa <a href="suporte.php" style="color: #38bdf8; font-weight: 600; text-decoration: underline;">Central de Suporte</a>.</p>
    </div>
  </main>

  <footer>
    &copy; <?php echo date("Y"); ?> 4U.IA.BR &bull; AuthPass &bull; Todos os direitos reservados.
  </footer>
</body>
</html>
