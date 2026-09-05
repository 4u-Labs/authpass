<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
$v = time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Política de Privacidade & LGPD — AuthPass</title>
  <meta name="description" content="Política de Privacidade e conformidade LGPD do AuthPass. Criptografia Zero-Knowledge, Retenção Zero e privacidade absoluta para seus tokens 2FA.">
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
    .badge-card {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 16px 20px;
      background: rgba(56, 189, 248, 0.08);
      border: 1px solid rgba(56, 189, 248, 0.25);
      border-radius: 12px;
      margin-bottom: 24px;
    }
    .badge-card i { font-size: 24px; color: #38bdf8; }
    .badge-card-text { font-size: 0.9rem; color: #cbd5e1; }
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
        <h1>Política de Privacidade & LGPD</h1>
        <p>Última atualização: Setembro de <?php echo date("Y"); ?> &bull; Arquitetura Zero-Knowledge</p>
      </div>

      <div class="badge-card">
        <i class="fas fa-shield-cat"></i>
        <div class="badge-card-text">
          <strong>Arquitetura Zero-Knowledge & Retenção Zero:</strong> O AuthPass nunca recebe, transmite ou armazena suas chaves secretas 2FA em nossos servidores. Tudo é criptografado localmente no seu dispositivo.
        </div>
      </div>

      <h2><i class="fas fa-lock"></i> 1. Nosso Compromisso com a Privacidade</h2>
      <p>A <strong>4U.IA.BR</strong> opera em estrita conformidade com a Lei Geral de Proteção de Dados (Lei nº 13.709/2018 - LGPD). O <strong>AuthPass</strong> foi concebido com privacidade por design (*Privacy by Design*): suas credenciais de segundo fator pertencem exclusivamente a você.</p>

      <h2><i class="fas fa-key"></i> 2. Criptografia Militar no Cliente (Client-Side)</h2>
      <p>Todos os segredos TOTP adicionados ao AuthPass são protegidos antes de qualquer persistência:</p>
      <ul>
        <li>O PIN ou Senha Mestra derivam uma chave criptográfica via <strong>PBKDF2 com 100.000 iterações</strong> e salt aleatório.</li>
        <li>O cofre de contas é criptografado utilizando o algoritmo autenticado <strong>AES-GCM de 256 bits</strong>.</li>
        <li>Os segredos em texto claro residem estritamente na memória volátil (RAM) enquanto o app estiver desbloqueado.</li>
      </ul>

      <h2><i class="fab fa-google-drive"></i> 3. Sincronização via Google Drive (AppData Privada)</h2>
      <p>Quando a sincronização em nuvem é ativada pelo usuário:</p>
      <ul>
        <li>A conexão é feita diretamente entre o seu navegador e a API oficial do Google Drive.</li>
        <li>O arquivo de backup é gravado na pasta restrita <code>appDataFolder</code> da sua conta Google, inacessível para outros aplicativos.</li>
        <li>O arquivo transmitido é um blob binário previamente criptografado com sua senha. Nem a Google nem os servidores da 4U.IA.BR têm a capacidade de descriptografar seus códigos.</li>
      </ul>

      <h2><i class="fas fa-server"></i> 4. Retenção Zero no Servidor 4U.IA.BR</h2>
      <p>Nossos servidores hospedam exclusivamente os arquivos de front-end necessários para execução do aplicativo no navegador. Não mantemos bancos de dados relacionais com credenciais de usuários, tokens de sessão ou registros de chaves 2FA.</p>

      <h2><i class="fas fa-envelope"></i> 5. Contato & Encarregado de Dados</h2>
      <p>Para dúvidas sobre segurança ou exercício de direitos fundamentais da LGPD, entre em contato através da nossa <a href="suporte.php" style="color: #38bdf8; font-weight: 600; text-decoration: underline;">Central de Suporte</a> ou diretamente pelo e-mail <strong>contato@4u.ia.br</strong>.</p>
    </div>
  </main>

  <footer>
    &copy; <?php echo date("Y"); ?> 4U.IA.BR &bull; AuthPass &bull; Todos os direitos reservados.
  </footer>
</body>
</html>
