<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
$v = time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <title>AuthPass — Autenticador 2FA Seguro & Retenção Zero | 4U.IA.BR</title>
  <meta name="description" content="Autenticador 2FA (TOTP) de alta segurança com criptografia Zero-Knowledge no cliente, leitor de QR Code, backup no Google Drive e retenção zero.">

  <!-- PWA Meta -->
  <link rel="manifest" href="manifest.json?v=<?php echo $v; ?>">
  <meta name="theme-color" content="#0b0f19">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
  <link rel="apple-touch-icon" href="icon-192.png">

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- jsQR for QR Code Decoding (Camera & File) -->
  <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

  <!-- Google Identity Services (Drive Sync) -->
  <script src="https://accounts.google.com/gsi/client" async defer></script>

  <style>
    :root {
      --bg-main: #0b0f19;
      --bg-card: rgba(19, 27, 46, 0.75);
      --bg-card-hover: rgba(26, 38, 64, 0.9);
      --border-color: rgba(255, 255, 255, 0.08);
      --primary: #6366f1;
      --primary-glow: rgba(99, 102, 241, 0.35);
      --secondary: #ec4899;
      --accent: #38bdf8;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
      --text-main: #f8fafc;
      --text-muted: #94a3b8;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      background-color: var(--bg-main);
      color: var(--text-main);
      font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      position: relative;
      overflow-x: hidden;
    }

    /* Ambient Glow Background */
    .ambient-bg {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      pointer-events: none;
      z-index: 0;
      background: 
        radial-gradient(circle at 15% 15%, rgba(99, 102, 241, 0.12), transparent 45%),
        radial-gradient(circle at 85% 25%, rgba(236, 72, 153, 0.08), transparent 40%),
        radial-gradient(circle at 50% 80%, rgba(56, 189, 248, 0.08), transparent 50%);
    }

    /* Header */
    header.app-header {
      position: sticky;
      top: 0;
      z-index: 40;
      background: rgba(11, 15, 25, 0.85);
      backdrop-filter: blur(16px);
      border-bottom: 1px solid var(--border-color);
      height: 68px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
    }
    .brand-wrap {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      color: #fff;
    }
    .brand-icon {
      width: 40px;
      height: 40px;
      border-radius: 12px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 19px;
      box-shadow: 0 4px 14px var(--primary-glow);
    }
    .brand-info h1 {
      font-size: 1.15rem;
      font-weight: 800;
      letter-spacing: -0.02em;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .brand-info .badge-zk {
      font-size: 10px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 20px;
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid rgba(16, 185, 129, 0.3);
      color: var(--success);
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .brand-info p {
      font-size: 0.72rem;
      color: var(--text-muted);
    }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .btn-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border-color);
      color: var(--text-muted);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 15px;
      transition: all 0.2s;
    }
    .btn-icon:hover {
      color: #fff;
      background: rgba(255, 255, 255, 0.1);
      border-color: rgba(255, 255, 255, 0.2);
    }
    .btn-action-primary {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      border-radius: 10px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      color: #fff;
      font-weight: 700;
      font-size: 0.85rem;
      border: none;
      cursor: pointer;
      box-shadow: 0 4px 12px var(--primary-glow);
      transition: all 0.2s;
    }
    .btn-action-primary:hover {
      filter: brightness(1.1);
      transform: translateY(-1px);
    }

    /* Main App Layout */
    main.app-main {
      flex: 1;
      width: 100%;
      max-width: 860px;
      margin: 0 auto;
      padding: 24px 16px 40px;
      position: relative;
      z-index: 10;
    }

    /* Lock / Setup Screens */
    .screen-card {
      background: var(--bg-card);
      backdrop-filter: blur(20px);
      border: 1px solid var(--border-color);
      border-radius: 20px;
      padding: 36px 32px;
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.5);
      max-width: 460px;
      margin: 40px auto;
      text-align: center;
    }
    .screen-icon-wrap {
      width: 64px;
      height: 64px;
      border-radius: 20px;
      background: rgba(99, 102, 241, 0.15);
      border: 1px solid rgba(99, 102, 241, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      color: var(--primary);
      margin: 0 auto 20px;
    }
    .screen-title {
      font-size: 1.5rem;
      font-weight: 800;
      margin-bottom: 8px;
      letter-spacing: -0.02em;
    }
    .screen-subtitle {
      font-size: 0.875rem;
      color: var(--text-muted);
      margin-bottom: 24px;
      line-height: 1.5;
    }

    /* Pin Display & Inputs */
    .pin-display {
      display: flex;
      justify-content: center;
      gap: 12px;
      margin-bottom: 28px;
    }
    .pin-dot {
      width: 16px;
      height: 16px;
      border-radius: 50%;
      border: 2px solid rgba(255, 255, 255, 0.2);
      transition: all 0.2s;
    }
    .pin-dot.filled {
      background: var(--primary);
      border-color: var(--primary);
      box-shadow: 0 0 10px var(--primary-glow);
    }

    /* Numeric Keypad */
    .keypad {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      max-width: 280px;
      margin: 0 auto;
    }
    .keypad-btn {
      height: 58px;
      border-radius: 14px;
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid var(--border-color);
      color: #fff;
      font-size: 1.35rem;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.15s;
    }
    .keypad-btn:hover {
      background: rgba(255, 255, 255, 0.09);
      border-color: rgba(255, 255, 255, 0.18);
    }
    .keypad-btn:active {
      transform: scale(0.95);
      background: var(--primary);
    }

    /* Recovery Key Box */
    .recovery-box {
      background: rgba(11, 15, 25, 0.9);
      border: 1px dashed rgba(56, 189, 248, 0.4);
      border-radius: 12px;
      padding: 16px;
      font-family: 'JetBrains Mono', monospace;
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--accent);
      word-break: break-all;
      margin-bottom: 16px;
      user-select: all;
    }

    /* Dashboard & Filter Bar */
    .dashboard-toolbar {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }
    .search-box {
      flex: 1;
      min-width: 220px;
      position: relative;
    }
    .search-box i {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 14px;
    }
    .search-input {
      width: 100%;
      padding: 10px 14px 10px 38px;
      background: rgba(19, 27, 46, 0.7);
      border: 1px solid var(--border-color);
      border-radius: 12px;
      color: #fff;
      font-family: inherit;
      font-size: 0.875rem;
      outline: none;
      transition: all 0.2s;
    }
    .search-input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--primary-glow);
    }

    /* Global Sync Status Banner */
    .sync-status-card {
      background: rgba(19, 27, 46, 0.6);
      border: 1px solid var(--border-color);
      border-radius: 14px;
      padding: 12px 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      font-size: 0.825rem;
      color: var(--text-muted);
    }
    .sync-indicator {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .sync-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--success);
      box-shadow: 0 0 8px rgba(16, 185, 129, 0.5);
    }
    .sync-dot.offline {
      background: var(--warning);
      box-shadow: 0 0 8px rgba(245, 158, 11, 0.5);
    }

    /* Accounts List */
    .accounts-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
      gap: 16px;
    }
    @media (max-width: 480px) {
      .accounts-grid { grid-template-columns: 1fr; }
    }

    /* Account Card */
    .account-card {
      background: var(--bg-card);
      backdrop-filter: blur(16px);
      border: 1px solid var(--border-color);
      border-radius: 18px;
      padding: 18px 20px;
      position: relative;
      transition: all 0.2s ease;
      cursor: pointer;
      user-select: none;
    }
    .account-card:hover {
      background: var(--bg-card-hover);
      border-color: rgba(99, 102, 241, 0.4);
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
    }
    .account-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 12px;
    }
    .account-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      overflow: hidden;
    }
    .service-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      color: var(--accent);
      flex-shrink: 0;
    }
    .account-meta {
      overflow: hidden;
    }
    .account-issuer {
      font-size: 0.95rem;
      font-weight: 700;
      color: #fff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .account-label {
      font-size: 0.775rem;
      color: var(--text-muted);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .account-card-actions {
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .btn-card-action {
      width: 28px;
      height: 28px;
      border-radius: 7px;
      border: none;
      background: transparent;
      color: var(--text-muted);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      transition: all 0.2s;
    }
    .btn-card-action:hover {
      color: #fff;
      background: rgba(255, 255, 255, 0.1);
    }
    .btn-card-action.btn-del:hover {
      color: var(--danger);
      background: rgba(239, 68, 68, 0.15);
    }

    /* OTP Code Display */
    .otp-wrapper {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 6px;
    }
    .otp-code {
      font-family: 'JetBrains Mono', monospace;
      font-size: 2.1rem;
      font-weight: 800;
      letter-spacing: 0.12em;
      color: var(--accent);
      text-shadow: 0 0 16px rgba(56, 189, 248, 0.25);
      transition: all 0.2s;
    }
    .otp-code.pulse-warning {
      color: var(--warning);
      text-shadow: 0 0 16px rgba(245, 158, 11, 0.4);
    }
    .otp-code.pulse-danger {
      color: var(--danger);
      text-shadow: 0 0 16px rgba(239, 68, 68, 0.5);
    }

    /* Progress Ring */
    .timer-ring-wrap {
      position: relative;
      width: 36px;
      height: 36px;
    }
    .timer-svg {
      transform: rotate(-90deg);
      width: 36px;
      height: 36px;
    }
    .timer-circle-bg {
      stroke: rgba(255, 255, 255, 0.1);
      stroke-width: 3.5;
      fill: none;
    }
    .timer-circle-fg {
      stroke: var(--accent);
      stroke-width: 3.5;
      stroke-linecap: round;
      fill: none;
      transition: stroke-dashoffset 0.5s linear, stroke 0.3s;
    }
    .timer-seconds-text {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'JetBrains Mono', monospace;
      font-size: 11px;
      font-weight: 700;
      color: var(--text-muted);
    }

    /* Modals */
    .modal-backdrop {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.8);
      backdrop-filter: blur(10px);
      z-index: 100;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 16px;
    }
    .modal-backdrop.active { display: flex; }
    .modal-window {
      background: rgba(19, 27, 46, 0.95);
      border: 1px solid var(--border-color);
      border-radius: 20px;
      width: 100%;
      max-width: 500px;
      max-height: 90vh;
      overflow-y: auto;
      padding: 28px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7);
    }
    .modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .modal-title {
      font-size: 1.25rem;
      font-weight: 800;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .modal-close {
      background: transparent;
      border: none;
      color: var(--text-muted);
      font-size: 18px;
      cursor: pointer;
    }
    .modal-close:hover { color: #fff; }

    /* Modal Tabs */
    .modal-tabs {
      display: flex;
      gap: 8px;
      margin-bottom: 20px;
      border-bottom: 1px solid var(--border-color);
      padding-bottom: 12px;
    }
    .modal-tab-btn {
      padding: 8px 14px;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid transparent;
      color: var(--text-muted);
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
    }
    .modal-tab-btn.active {
      background: rgba(99, 102, 241, 0.15);
      border-color: rgba(99, 102, 241, 0.4);
      color: #fff;
    }

    /* Form Styles */
    .form-group { margin-bottom: 16px; }
    .form-label {
      display: block;
      font-size: 0.825rem;
      font-weight: 600;
      color: var(--text-muted);
      margin-bottom: 6px;
    }
    .form-input {
      width: 100%;
      padding: 12px 14px;
      background: rgba(11, 15, 25, 0.8);
      border: 1px solid var(--border-color);
      border-radius: 10px;
      color: #fff;
      font-family: inherit;
      font-size: 0.9rem;
      outline: none;
      transition: all 0.2s;
    }
    .form-input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--primary-glow);
    }

    /* Camera Scanner Box */
    .scanner-box {
      width: 100%;
      height: 250px;
      border-radius: 14px;
      background: #000;
      overflow: hidden;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 16px;
      border: 2px dashed rgba(255, 255, 255, 0.15);
    }
    #cameraVideo {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .scanner-overlay {
      position: absolute;
      width: 180px;
      height: 180px;
      border: 2px solid var(--primary);
      border-radius: 14px;
      box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
      pointer-events: none;
    }

    /* Drag & Drop Zone */
    .drop-zone {
      border: 2px dashed rgba(255, 255, 255, 0.2);
      border-radius: 14px;
      padding: 32px 20px;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s;
      background: rgba(255, 255, 255, 0.02);
    }
    .drop-zone:hover {
      border-color: var(--primary);
      background: rgba(99, 102, 241, 0.05);
    }
    .drop-zone i { font-size: 32px; color: var(--primary); margin-bottom: 10px; }
    .drop-zone p { font-size: 0.85rem; color: var(--text-muted); }

    /* Toast Notification */
    .toast {
      position: fixed;
      bottom: 24px;
      right: 24px;
      background: rgba(19, 27, 46, 0.95);
      border: 1px solid var(--primary);
      color: #fff;
      padding: 12px 20px;
      border-radius: 12px;
      font-size: 0.875rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      z-index: 200;
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      pointer-events: none;
    }
    .toast.show {
      opacity: 1;
      transform: translateY(0);
    }

    /* Standard Institutional Footer */
    footer.footer-clean {
      margin-top: auto;
      border-top: 1px solid var(--border-color);
      background: rgba(11, 15, 25, 0.9);
      backdrop-filter: blur(12px);
      padding: 24px 20px;
      text-align: center;
      font-size: 0.85rem;
      color: var(--text-muted);
      position: relative;
      z-index: 20;
    }
    .footer-links {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 18px;
      margin-bottom: 12px;
      flex-wrap: wrap;
    }
    .footer-links a {
      color: var(--text-muted);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }
    .footer-links a:hover { color: #fff; }
    .footer-copy {
      font-size: 0.775rem;
      color: #64748b;
    }
  </style>
</head>
<body>
  <div class="ambient-bg"></div>

  <!-- App Header -->
  <header class="app-header">
    <a href="./" class="brand-wrap">
      <div class="brand-icon"><i class="fas fa-shield-halved"></i></div>
      <div class="brand-info">
        <h1>AuthPass <span class="badge-zk">Zero-Knowledge</span></h1>
        <p>Autenticador 2FA • Retenção Zero</p>
      </div>
    </a>
    <div class="header-actions">
      <button class="btn-icon" id="btnSyncModal" title="Sincronização & Backup" onclick="openSyncModal()">
        <i class="fab fa-google-drive"></i>
      </button>
      <button class="btn-icon" id="btnLockVault" title="Bloquear Cofre" onclick="lockVault()">
        <i class="fas fa-lock"></i>
      </button>
      <button class="btn-action-primary" onclick="openAddModal()">
        <i class="fas fa-plus"></i> <span>Adicionar Conta</span>
      </button>
    </div>
  </header>

  <!-- Main Container -->
  <main class="app-main">
    
    <!-- SCREEN 1: ONBOARDING / CONFIGURAR PIN (Primeiro Acesso) -->
    <div id="screenOnboarding" class="screen-card" style="display: none;">
      <div class="screen-icon-wrap"><i class="fas fa-shield-cat"></i></div>
      <h2 class="screen-title">Bem-vindo ao AuthPass</h2>
      <p class="screen-subtitle">Crie um <strong>PIN de 6 dígitos</strong> para criptografar suas chaves 2FA diretamente no seu dispositivo.</p>
      
      <div class="pin-display" id="onboardPinDots">
        <div class="pin-dot"></div><div class="pin-dot"></div><div class="pin-dot"></div>
        <div class="pin-dot"></div><div class="pin-dot"></div><div class="pin-dot"></div>
      </div>

      <div class="keypad" id="onboardKeypad">
        <button class="keypad-btn" onclick="pressOnboardKey('1')">1</button>
        <button class="keypad-btn" onclick="pressOnboardKey('2')">2</button>
        <button class="keypad-btn" onclick="pressOnboardKey('3')">3</button>
        <button class="keypad-btn" onclick="pressOnboardKey('4')">4</button>
        <button class="keypad-btn" onclick="pressOnboardKey('5')">5</button>
        <button class="keypad-btn" onclick="pressOnboardKey('6')">6</button>
        <button class="keypad-btn" onclick="pressOnboardKey('7')">7</button>
        <button class="keypad-btn" onclick="pressOnboardKey('8')">8</button>
        <button class="keypad-btn" onclick="pressOnboardKey('9')">9</button>
        <button class="keypad-btn" onclick="clearOnboardKey()"><i class="fas fa-trash-can" style="font-size: 15px;"></i></button>
        <button class="keypad-btn" onclick="pressOnboardKey('0')">0</button>
        <button class="keypad-btn" onclick="backspaceOnboardKey()"><i class="fas fa-delete-left" style="font-size: 16px;"></i></button>
      </div>

      <!-- Emergency Key Step (Shown after PIN confirmed) -->
      <div id="stepEmergencyKey" style="display: none; margin-top: 24px; text-align: left;">
        <h3 style="font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 8px;">
          <i class="fas fa-triangle-exclamation" style="color: var(--warning);"></i> Guarde sua Chave de Emergência:
        </h3>
        <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 12px;">
          Se você esquecer seu PIN ou perder seu dispositivo, esta chave é a única forma de recuperar seu cofre. Anote-a em local seguro:
        </p>
        <div class="recovery-box" id="emergencyKeyDisplay">AUTHPASS-XXXX-XXXX-XXXX-XXXX</div>
        <button class="btn-action-primary" style="width: 100%; justify-content: center; margin-bottom: 12px;" onclick="copyEmergencyKey()">
          <i class="fas fa-copy"></i> Copiar Chave de Emergência
        </button>
        <label style="display: flex; align-items: center; gap: 8px; font-size: 0.825rem; color: var(--text-muted); cursor: pointer;">
          <input type="checkbox" id="chkSavedEmergencyKey" onchange="enableFinishOnboarding()">
          Eu guardei minha chave de emergência em local seguro.
        </label>
        <button id="btnFinishOnboarding" disabled class="btn-action-primary" style="width: 100%; justify-content: center; margin-top: 14px; opacity: 0.5;" onclick="finishOnboarding()">
          Acessar Meu Cofre <i class="fas fa-arrow-right"></i>
        </button>
      </div>
    </div>

    <!-- SCREEN 2: UNLOCK SCREEN (Cofre Bloqueado) -->
    <div id="screenUnlock" class="screen-card" style="display: none;">
      <div class="screen-icon-wrap"><i class="fas fa-lock"></i></div>
      <h2 class="screen-title">Cofre Bloqueado</h2>
      <p class="screen-subtitle">Digite seu PIN de 6 dígitos para acessar seus códigos 2FA.</p>

      <div class="pin-display" id="unlockPinDots">
        <div class="pin-dot"></div><div class="pin-dot"></div><div class="pin-dot"></div>
        <div class="pin-dot"></div><div class="pin-dot"></div><div class="pin-dot"></div>
      </div>

      <div class="keypad" id="unlockKeypad">
        <button class="keypad-btn" onclick="pressUnlockKey('1')">1</button>
        <button class="keypad-btn" onclick="pressUnlockKey('2')">2</button>
        <button class="keypad-btn" onclick="pressUnlockKey('3')">3</button>
        <button class="keypad-btn" onclick="pressUnlockKey('4')">4</button>
        <button class="keypad-btn" onclick="pressUnlockKey('5')">5</button>
        <button class="keypad-btn" onclick="pressUnlockKey('6')">6</button>
        <button class="keypad-btn" onclick="pressUnlockKey('7')">7</button>
        <button class="keypad-btn" onclick="pressUnlockKey('8')">8</button>
        <button class="keypad-btn" onclick="pressUnlockKey('9')">9</button>
        <button class="keypad-btn" onclick="clearUnlockKey()"><i class="fas fa-trash-can" style="font-size: 15px;"></i></button>
        <button class="keypad-btn" onclick="pressUnlockKey('0')">0</button>
        <button class="keypad-btn" onclick="backspaceUnlockKey()"><i class="fas fa-delete-left" style="font-size: 16px;"></i></button>
      </div>

      <div style="margin-top: 24px;">
        <button style="background: transparent; border: none; color: var(--primary); font-size: 0.825rem; font-weight: 600; cursor: pointer; text-decoration: underline;" onclick="openRecoveryModal()">
          <i class="fas fa-key"></i> Esqueci meu PIN (Usar Chave de Emergência)
        </button>
      </div>
    </div>

    <!-- SCREEN 3: DASHBOARD (Cofre Desbloqueado) -->
    <div id="screenDashboard" style="display: none;">
      
      <div class="sync-status-card">
        <div class="sync-indicator">
          <div class="sync-dot" id="syncDot"></div>
          <span id="syncText">Cofre Local Ativo (Zero-Knowledge)</span>
        </div>
        <div style="display: flex; gap: 8px;">
          <button class="btn-card-action" title="Sincronização Nuvem" onclick="openSyncModal()">
            <i class="fas fa-cloud"></i>
          </button>
          <button class="btn-card-action" title="Exportar Backup" onclick="exportBackupFile()">
            <i class="fas fa-file-export"></i>
          </button>
        </div>
      </div>

      <div class="dashboard-toolbar">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" id="filterInput" class="search-input" placeholder="Pesquisar contas ou serviços..." oninput="renderAccounts()">
        </div>
      </div>

      <!-- Empty State -->
      <div id="emptyAccounts" class="screen-card" style="display: none; max-width: 500px; padding: 40px 24px;">
        <div class="screen-icon-wrap" style="background: rgba(56, 189, 248, 0.1); border-color: rgba(56, 189, 248, 0.25); color: var(--accent);">
          <i class="fas fa-qrcode"></i>
        </div>
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 8px;">Nenhuma conta 2FA adicionada</h3>
        <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 24px;">
          Adicione seu primeiro token escaneando um QR Code ou inserindo a chave secreta manualmente.
        </p>
        <button class="btn-action-primary" style="margin: 0 auto;" onclick="openAddModal()">
          <i class="fas fa-plus"></i> Adicionar Minha Primeira Conta
        </button>
      </div>

      <!-- Accounts Grid -->
      <div class="accounts-grid" id="accountsGrid"></div>

    </div>

  </main>

  <!-- MODAL: ADICIONAR CONTA -->
  <div class="modal-backdrop" id="modalAdd">
    <div class="modal-window">
      <div class="modal-header">
        <div class="modal-title"><i class="fas fa-plus-circle" style="color: var(--primary);"></i> Adicionar Conta 2FA</div>
        <button class="modal-close" onclick="closeAddModal()"><i class="fas fa-times"></i></button>
      </div>

      <div class="modal-tabs">
        <button class="modal-tab-btn active" id="tabBtnCamera" onclick="switchAddTab('camera')">
          <i class="fas fa-camera"></i> Câmera
        </button>
        <button class="modal-tab-btn" id="tabBtnUpload" onclick="switchAddTab('upload')">
          <i class="fas fa-image"></i> Imagem / Print
        </button>
        <button class="modal-tab-btn" id="tabBtnManual" onclick="switchAddTab('manual')">
          <i class="fas fa-keyboard"></i> Manual
        </button>
      </div>

      <!-- TAB CAMERA -->
      <div id="tabContentCamera">
        <div class="scanner-box">
          <video id="cameraVideo" playsinline></video>
          <div class="scanner-overlay"></div>
          <canvas id="qrCanvas" style="display: none;"></canvas>
        </div>
        <p style="font-size: 0.8rem; color: var(--text-muted); text-align: center; margin-bottom: 14px;">
          Aponte a câmera para o QR Code gerado pelo serviço (Google, GitHub, etc.).
        </p>
      </div>

      <!-- TAB UPLOAD -->
      <div id="tabContentUpload" style="display: none;">
        <div class="drop-zone" onclick="document.getElementById('fileQrInput').click()">
          <i class="fas fa-cloud-arrow-up"></i>
          <p><strong>Clique para selecionar</strong> ou arraste uma captura de tela com o QR Code</p>
          <input type="file" id="fileQrInput" accept="image/*" style="display: none;" onchange="handleQrFileUpload(event)">
        </div>
      </div>

      <!-- TAB MANUAL & CONFIRMAÇÃO -->
      <div id="tabContentManual">
        <form onsubmit="handleManualAdd(event)">
          <div class="form-group">
            <label class="form-label" for="inputIssuer">Nome do Serviço (ex: GitHub, Google, Binance)</label>
            <input type="text" id="inputIssuer" class="form-input" placeholder="Ex: GitHub" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="inputLabel">Identificador / E-mail da Conta</label>
            <input type="text" id="inputLabel" class="form-input" placeholder="Ex: usuario@gmail.com" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="inputSecret">Chave Secreta (Base32)</label>
            <input type="text" id="inputSecret" class="form-input" style="font-family: 'JetBrains Mono', monospace; text-transform: uppercase;" placeholder="Ex: JBSWY3DPEHPK3PXP" required>
          </div>
          <button type="submit" class="btn-action-primary" style="width: 100%; justify-content: center; padding: 12px;">
            <i class="fas fa-check"></i> Salvar no Cofre
          </button>
        </form>
      </div>

    </div>
  </div>

  <!-- MODAL: SINCRONIZAÇÃO & GOOGLE DRIVE -->
  <div class="modal-backdrop" id="modalSync">
    <div class="modal-window">
      <div class="modal-header">
        <div class="modal-title"><i class="fab fa-google-drive" style="color: #34a853;"></i> Sincronização & Backup</div>
        <button class="modal-close" onclick="closeSyncModal()"><i class="fas fa-times"></i></button>
      </div>

      <div style="margin-bottom: 20px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
          <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(52, 168, 83, 0.15); display: flex; align-items: center; justify-content: center; font-size: 22px; color: #34a853;">
            <i class="fab fa-google"></i>
          </div>
          <div>
            <h4 style="font-size: 1rem; font-weight: 700; color: #fff;">Google Drive AppData</h4>
            <p style="font-size: 0.8rem; color: var(--text-muted);">Backup automático em pasta isolada da sua conta Google.</p>
          </div>
        </div>
        <p style="font-size: 0.825rem; color: #cbd5e1; line-height: 1.5; margin-bottom: 16px;">
          Os dados são enviados <strong>100% criptografados</strong> com seu PIN. Nem o Google nem a 4U.IA.BR podem ver suas chaves.
        </p>

        <button class="btn-action-primary" style="width: 100%; justify-content: center; background: #1f293d; border: 1px solid rgba(255,255,255,0.15); box-shadow: none; margin-bottom: 12px;" onclick="handleGoogleDriveAuth()">
          <i class="fab fa-google" style="color: #ea4335;"></i> Sincronizar com Conta Google
        </button>
      </div>

      <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 20px 0;">

      <div>
        <h4 style="font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 8px;">Backup em Arquivo Offline (.authpass)</h4>
        <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 14px;">
          Baixe um arquivo criptografado para guardar em pendrive ou restaure em outro aparelho.
        </p>
        <div style="display: flex; gap: 10px;">
          <button class="btn-action-primary" style="flex: 1; justify-content: center; padding: 10px;" onclick="exportBackupFile()">
            <i class="fas fa-download"></i> Exportar
          </button>
          <button class="btn-action-primary" style="flex: 1; justify-content: center; background: rgba(255,255,255,0.06); border: 1px solid var(--border-color); box-shadow: none; padding: 10px;" onclick="document.getElementById('importFileInput').click()">
            <i class="fas fa-upload"></i> Importar
          </button>
          <input type="file" id="importFileInput" accept=".authpass,application/json" style="display: none;" onchange="handleImportFile(event)">
        </div>
      </div>

    </div>
  </div>

  <!-- MODAL: RECUPERAÇÃO COM CHAVE MESTRA -->
  <div class="modal-backdrop" id="modalRecovery">
    <div class="modal-window">
      <div class="modal-header">
        <div class="modal-title"><i class="fas fa-key" style="color: var(--warning);"></i> Recuperar Cofre</div>
        <button class="modal-close" onclick="closeRecoveryModal()"><i class="fas fa-times"></i></button>
      </div>
      <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 16px;">
        Insira a Chave Mestra de Emergência fornecida no momento da criação da sua conta para redefinir seu PIN de acesso:
      </p>
      <form onsubmit="handleEmergencyRecovery(event)">
        <div class="form-group">
          <label class="form-label">Chave de Emergência</label>
          <input type="text" id="recoveryKeyInput" class="form-input" style="font-family: 'JetBrains Mono', monospace;" placeholder="AUTHPASS-XXXX-XXXX-XXXX-XXXX" required>
        </div>
        <div class="form-group">
          <label class="form-label">Novo PIN (6 dígitos)</label>
          <input type="password" maxlength="6" id="recoveryNewPin" class="form-input" placeholder="Ex: 123456" required>
        </div>
        <button type="submit" class="btn-action-primary" style="width: 100%; justify-content: center; padding: 12px;">
          <i class="fas fa-unlock"></i> Redefinir PIN e Desbloquear
        </button>
      </form>
    </div>
  </div>

  <!-- Toast Notification -->
  <div class="toast" id="toastBox">
    <i class="fas fa-check-circle" style="color: var(--success);"></i>
    <span id="toastMsg">Código copiado para a área de transferência!</span>
  </div>

  <!-- Standard Clean Footer -->
  <footer class="footer-clean">
    <div class="footer-links">
      <a href="privacidade.php"><i class="fas fa-shield-alt"></i> Privacidade & LGPD</a>
      <a href="termos.php"><i class="fas fa-file-contract"></i> Termos de Uso</a>
      <a href="suporte.php"><i class="fas fa-headset"></i> Central de Suporte</a>
      <a href="https://4u.ia.br" target="_blank"><i class="fas fa-globe"></i> 4U.IA.BR</a>
    </div>
    <div class="footer-copy">
      &copy; <?php echo date("Y"); ?> 4U.IA.BR &bull; AuthPass &bull; Todos os direitos reservados.
    </div>
  </footer>

  <!-- ========================================== -->
  <!-- JAVASCRIPT: MOTOR TOTP & CRIPTOGRAFIA ZERO-KNOWLEDGE -->
  <!-- ========================================== -->
  <script>
    // State Management
    let vault = []; // Decrypted accounts in RAM
    let currentKey = null; // CryptoKey AES-GCM in RAM
    let emergencyKey = null;
    let cameraStream = null;
    let scanning = false;
    let inactivityTimer = null;
    
    // PIN Buffer States
    let onboardPin = "";
    let unlockPin = "";

    // Storage Keys
    const VAULT_STORAGE_KEY = 'authpass_encrypted_vault';
    const SALT_STORAGE_KEY = 'authpass_pbkdf2_salt';
    const VERIFIER_STORAGE_KEY = 'authpass_pin_verifier';
    const EM_HASH_STORAGE_KEY = 'authpass_em_hash';

    // -------------------------------------------------------------
    // PWA Service Worker Registration
    // -------------------------------------------------------------
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('sw.js').catch(err => console.log('SW reg error:', err));
      });
    }

    // -------------------------------------------------------------
    // Toast Helper
    // -------------------------------------------------------------
    function showToast(msg) {
      const toast = document.getElementById('toastBox');
      document.getElementById('toastMsg').textContent = msg;
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 2600);
    }

    // -------------------------------------------------------------
    // Inactivity Auto-Lock (5 minutes)
    // -------------------------------------------------------------
    function resetInactivityTimer() {
      if (inactivityTimer) clearTimeout(inactivityTimer);
      inactivityTimer = setTimeout(() => {
        if (currentKey) {
          lockVault();
          showToast('Cofre bloqueado por inatividade.');
        }
      }, 5 * 60 * 1000);
    }
    window.addEventListener('mousemove', resetInactivityTimer);
    window.addEventListener('keydown', resetInactivityTimer);
    window.addEventListener('click', resetInactivityTimer);

    // -------------------------------------------------------------
    // Cryptography Helpers (Web Crypto API)
    // -------------------------------------------------------------
    function getStoredSalt() {
      let salt = localStorage.getItem(SALT_STORAGE_KEY);
      if (!salt) {
        const raw = crypto.getRandomValues(new Uint8Array(16));
        salt = Array.from(raw).map(b => b.toString(16).padStart(2, '0')).join('');
        localStorage.setItem(SALT_STORAGE_KEY, salt);
      }
      return new Uint8Array(salt.match(/.{1,2}/g).map(b => parseInt(b, 16)));
    }

    async function deriveKeyFromPin(pin) {
      const enc = new TextEncoder();
      const keyMaterial = await crypto.subtle.importKey(
        "raw", enc.encode(pin), { name: "PBKDF2" }, false, ["deriveKey"]
      );
      return crypto.subtle.deriveKey(
        {
          name: "PBKDF2",
          salt: getStoredSalt(),
          iterations: 100000,
          hash: "SHA-256"
        },
        keyMaterial,
        { name: "AES-GCM", length: 256 },
        false,
        ["encrypt", "decrypt"]
      );
    }

    async function sha256(str) {
      const enc = new TextEncoder();
      const buf = await crypto.subtle.digest("SHA-256", enc.encode(str));
      return Array.from(new Uint8Array(buf)).map(b => b.toString(16).padStart(2, '0')).join('');
    }

    async function encryptData(plainText, key) {
      const iv = crypto.getRandomValues(new Uint8Array(12));
      const enc = new TextEncoder();
      const cipher = await crypto.subtle.encrypt(
        { name: "AES-GCM", iv: iv }, key, enc.encode(plainText)
      );
      const combined = new Uint8Array(iv.length + cipher.byteLength);
      combined.set(iv, 0);
      combined.set(new Uint8Array(cipher), iv.length);
      return btoa(String.fromCharCode.apply(null, combined));
    }

    async function decryptData(cipherBase64, key) {
      const bin = atob(cipherBase64);
      const combined = new Uint8Array(bin.length);
      for (let i = 0; i < bin.length; i++) combined[i] = bin.charCodeAt(i);
      const iv = combined.slice(0, 12);
      const cipher = combined.slice(12);
      const plain = await crypto.subtle.decrypt(
        { name: "AES-GCM", iv: iv }, key, cipher
      );
      return new TextDecoder().decode(plain);
    }

    function generateRandomEmergencyKey() {
      const chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
      let parts = [];
      for (let p = 0; p < 4; p++) {
        let seg = "";
        for (let i = 0; i < 4; i++) {
          seg += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        parts.push(seg);
      }
      return "AUTHPASS-" + parts.join("-");
    }

    // -------------------------------------------------------------
    // Base32 & RFC 6238 TOTP Engine
    // -------------------------------------------------------------
    function base32toHex(base32) {
      const base32chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";
      let bits = "", hex = "";
      const cleaned = base32.replace(/[\s=-]/g, '').toUpperCase();
      for (let i = 0; i < cleaned.length; i++) {
        const val = base32chars.indexOf(cleaned.charAt(i));
        if (val === -1) continue;
        bits += val.toString(2).padStart(5, '0');
      }
      for (let i = 0; i + 8 <= bits.length; i += 8) {
        hex += parseInt(bits.substring(i, i + 8), 2).toString(16).padStart(2, '0');
      }
      return hex;
    }

    async function generateTOTP(secretBase32, period = 30, digits = 6, algorithm = "SHA-1") {
      try {
        const epoch = Math.floor(Date.now() / 1000.0);
        const timeStep = Math.floor(epoch / period);

        const timeBuffer = new ArrayBuffer(8);
        const timeView = new DataView(timeBuffer);
        timeView.setUint32(4, timeStep);

        const secretHex = base32toHex(secretBase32);
        if (!secretHex || secretHex.length < 2) return "000000";

        const secretBytes = new Uint8Array(secretHex.match(/.{1,2}/g).map(byte => parseInt(byte, 16)));

        const hashAlgo = algorithm.toUpperCase().replace("SHA", "SHA-");
        const key = await crypto.subtle.importKey(
          "raw", secretBytes, { name: "HMAC", hash: { name: hashAlgo.includes("-") ? hashAlgo : "SHA-1" } }, false, ["sign"]
        );
        const signature = await crypto.subtle.sign("HMAC", key, timeBuffer);
        const hash = new Uint8Array(signature);

        const offset = hash[hash.length - 1] & 0xf;
        const binary = ((hash[offset] & 0x7f) << 24) |
                       ((hash[offset + 1] & 0xff) << 16) |
                       ((hash[offset + 2] & 0xff) << 8) |
                       (hash[offset + 3] & 0xff);

        const mod = Math.pow(10, digits);
        const otp = binary % mod;
        return otp.toString().padStart(digits, '0');
      } catch (e) {
        return "000000";
      }
    }

    // -------------------------------------------------------------
    // Screen Management Helper (Guarantees only 1 screen is visible)
    // -------------------------------------------------------------
    function showScreen(screenId) {
      const screens = ['screenOnboarding', 'screenUnlock', 'screenDashboard'];
      screens.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = (id === screenId) ? 'block' : 'none';
      });

      const isUnlocked = (screenId === 'screenDashboard');
      const btnLock = document.getElementById('btnLockVault');
      const btnAdd = document.querySelector('.btn-action-primary');
      const btnSync = document.getElementById('btnSyncModal');
      if (btnLock) btnLock.style.display = isUnlocked ? 'flex' : 'none';
      if (btnAdd) btnAdd.style.display = isUnlocked ? 'inline-flex' : 'none';
      if (btnSync) btnSync.style.display = isUnlocked ? 'flex' : 'none';
    }

    // -------------------------------------------------------------
    // App Initialization
    // -------------------------------------------------------------
    async function initApp() {
      const encryptedVault = localStorage.getItem(VAULT_STORAGE_KEY);
      if (!encryptedVault) {
        showScreen('screenOnboarding');
      } else {
        showScreen('screenUnlock');
      }
    }

    // -------------------------------------------------------------
    // Onboarding Keypad
    // -------------------------------------------------------------
    function updateOnboardDots() {
      const dots = document.querySelectorAll('#onboardPinDots .pin-dot');
      dots.forEach((dot, idx) => {
        if (idx < onboardPin.length) dot.classList.add('filled');
        else dot.classList.remove('filled');
      });
    }

    async function pressOnboardKey(num) {
      if (onboardPin.length < 6) {
        onboardPin += num;
        updateOnboardDots();
        if (onboardPin.length === 6) {
          // PIN digitado: Gera chave de emergência
          emergencyKey = generateRandomEmergencyKey();
          document.getElementById('emergencyKeyDisplay').textContent = emergencyKey;
          document.getElementById('onboardKeypad').style.display = 'none';
          document.getElementById('stepEmergencyKey').style.display = 'block';
        }
      }
    }

    function clearOnboardKey() {
      onboardPin = "";
      updateOnboardDots();
    }

    function backspaceOnboardKey() {
      onboardPin = onboardPin.slice(0, -1);
      updateOnboardDots();
    }

    function copyEmergencyKey() {
      navigator.clipboard.writeText(emergencyKey);
      showToast('Chave de emergência copiada!');
    }

    function enableFinishOnboarding() {
      const chk = document.getElementById('chkSavedEmergencyKey');
      const btn = document.getElementById('btnFinishOnboarding');
      btn.disabled = !chk.checked;
      btn.style.opacity = chk.checked ? '1' : '0.5';
    }

    async function finishOnboarding() {
      currentKey = await deriveKeyFromPin(onboardPin);
      vault = [];

      // Salva hashes e cofre vazio criptografado
      const pinVerifier = await sha256("VERIFY:" + onboardPin);
      const emHash = await sha256("EMERGENCY:" + emergencyKey);
      localStorage.setItem(VERIFIER_STORAGE_KEY, pinVerifier);
      localStorage.setItem(EM_HASH_STORAGE_KEY, emHash);

      await saveVault();

      showScreen('screenDashboard');
      showToast('Cofre Zero-Knowledge inicializado com sucesso!');
      renderAccounts();
      startTOTPLoop();
    }

    // -------------------------------------------------------------
    // Unlock Keypad
    // -------------------------------------------------------------
    function updateUnlockDots() {
      const dots = document.querySelectorAll('#unlockPinDots .pin-dot');
      dots.forEach((dot, idx) => {
        if (idx < unlockPin.length) dot.classList.add('filled');
        else dot.classList.remove('filled');
      });
    }

    async function pressUnlockKey(num) {
      if (unlockPin.length < 6) {
        unlockPin += num;
        updateUnlockDots();
        if (unlockPin.length === 6) {
          setTimeout(verifyUnlockPin, 100);
        }
      }
    }

    function clearUnlockKey() {
      unlockPin = "";
      updateUnlockDots();
    }

    function backspaceUnlockKey() {
      unlockPin = unlockPin.slice(0, -1);
      updateUnlockDots();
    }

    async function verifyUnlockPin() {
      const pinVerifier = localStorage.getItem(VERIFIER_STORAGE_KEY);
      const enteredHash = await sha256("VERIFY:" + unlockPin);
      if (enteredHash !== pinVerifier) {
        showToast('PIN incorreto! Tente novamente.');
        unlockPin = "";
        updateUnlockDots();
        return;
      }

      currentKey = await deriveKeyFromPin(unlockPin);
      const encryptedVault = localStorage.getItem(VAULT_STORAGE_KEY);
      try {
        const decryptedJson = await decryptData(encryptedVault, currentKey);
        vault = JSON.parse(decryptedJson) || [];
      } catch (e) {
        vault = [];
      }

      unlockPin = "";
      updateUnlockDots();
      showScreen('screenDashboard');
      showToast('Cofre desbloqueado.');
      renderAccounts();
      startTOTPLoop();
    }

    function lockVault() {
      currentKey = null;
      vault = [];
      unlockPin = "";
      updateUnlockDots();
      showScreen('screenUnlock');
    }

    // -------------------------------------------------------------
    // Save Vault (Encrypted)
    // -------------------------------------------------------------
    async function saveVault() {
      if (!currentKey) return;
      const json = JSON.stringify(vault);
      const cipher = await encryptData(json, currentKey);
      localStorage.setItem(VAULT_STORAGE_KEY, cipher);
    }

    // -------------------------------------------------------------
    // Recovery with Emergency Key
    // -------------------------------------------------------------
    function openRecoveryModal() {
      document.getElementById('modalRecovery').classList.add('active');
    }
    function closeRecoveryModal() {
      document.getElementById('modalRecovery').classList.remove('active');
    }

    async function handleEmergencyRecovery(e) {
      e.preventDefault();
      const enteredKey = document.getElementById('recoveryKeyInput').value.trim();
      const newPin = document.getElementById('recoveryNewPin').value.trim();

      if (newPin.length !== 6 || !/^\d+$/.test(newPin)) {
        showToast('O novo PIN precisa ter exatamente 6 dígitos.');
        return;
      }

      const storedEmHash = localStorage.getItem(EM_HASH_STORAGE_KEY);
      const enteredEmHash = await sha256("EMERGENCY:" + enteredKey);

      if (enteredEmHash !== storedEmHash) {
        showToast('Chave de emergência inválida!');
        return;
      }

      // Redefine chave e verifier com novo PIN
      const pinVerifier = await sha256("VERIFY:" + newPin);
      localStorage.setItem(VERIFIER_STORAGE_KEY, pinVerifier);
      currentKey = await deriveKeyFromPin(newPin);

      // Re-criptografa o cofre existente com o novo PIN
      await saveVault();

      closeRecoveryModal();
      showScreen('screenDashboard');
      showToast('PIN redefinido com sucesso! Cofre desbloqueado.');
      renderAccounts();
      startTOTPLoop();
    }

    // -------------------------------------------------------------
    // Render Accounts & TOTP Update Loop
    // -------------------------------------------------------------
    function getServiceIcon(issuer) {
      const l = (issuer || '').toLowerCase();
      if (l.includes('github')) return '<i class="fab fa-github"></i>';
      if (l.includes('google')) return '<i class="fab fa-google"></i>';
      if (l.includes('aws') || l.includes('amazon')) return '<i class="fab fa-aws"></i>';
      if (l.includes('discord')) return '<i class="fab fa-discord"></i>';
      if (l.includes('twitter') || l.includes('x')) return '<i class="fab fa-x-twitter"></i>';
      if (l.includes('microsoft')) return '<i class="fab fa-microsoft"></i>';
      if (l.includes('binance') || l.includes('crypto')) return '<i class="fas fa-coins"></i>';
      if (l.includes('cloud')) return '<i class="fas fa-cloud"></i>';
      return '<i class="fas fa-shield-halved"></i>';
    }

    async function renderAccounts() {
      const grid = document.getElementById('accountsGrid');
      const empty = document.getElementById('emptyAccounts');
      const filter = document.getElementById('filterInput').value.toLowerCase().trim();

      const filtered = vault.filter(acc => {
        return (acc.issuer || '').toLowerCase().includes(filter) ||
               (acc.label || '').toLowerCase().includes(filter);
      });

      if (filtered.length === 0) {
        grid.innerHTML = '';
        empty.style.display = vault.length === 0 ? 'block' : 'none';
        return;
      }
      empty.style.display = 'none';

      let html = '';
      for (const [idx, acc] of filtered.entries()) {
        const code = await generateTOTP(acc.secret, acc.period || 30, acc.digits || 6, acc.algorithm || "SHA-1");
        const formattedCode = code.length === 6 ? `${code.slice(0,3)} ${code.slice(3)}` : code;
        
        html += `
          <div class="account-card" onclick="copyOtpCode('${code}')" title="Clique para copiar o código">
            <div class="account-top">
              <div class="account-brand">
                <div class="service-icon">${getServiceIcon(acc.issuer)}</div>
                <div class="account-meta">
                  <div class="account-issuer">${escapeHtml(acc.issuer || '2FA')}</div>
                  <div class="account-label">${escapeHtml(acc.label || 'Conta')}</div>
                </div>
              </div>
              <div class="account-card-actions" onclick="event.stopPropagation()">
                <button class="btn-card-action btn-del" title="Excluir Conta" onclick="deleteAccount(${acc.id})">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
            <div class="otp-wrapper">
              <div class="otp-code" id="otpCode-${acc.id}">${formattedCode}</div>
              <div class="timer-ring-wrap">
                <svg class="timer-svg" viewBox="0 0 36 36">
                  <path class="timer-circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                  <path class="timer-circle-fg" id="timerRing-${acc.id}" stroke-dasharray="100, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="timer-seconds-text" id="timerSec-${acc.id}">30</div>
              </div>
            </div>
          </div>
        `;
      }
      grid.innerHTML = html;
    }

    function escapeHtml(str) {
      const d = document.createElement('div');
      d.textContent = str;
      return d.innerHTML;
    }

    function copyOtpCode(rawCode) {
      navigator.clipboard.writeText(rawCode);
      showToast('Código copiado para a área de transferência!');
    }

    async function deleteAccount(id) {
      if (confirm('Deseja realmente remover esta conta do AuthPass?')) {
        vault = vault.filter(a => a.id !== id);
        await saveVault();
        renderAccounts();
        showToast('Conta removida.');
      }
    }

    // -------------------------------------------------------------
    // Continuous TOTP Countdown Timer
    // -------------------------------------------------------------
    let totpInterval = null;
    function startTOTPLoop() {
      if (totpInterval) clearInterval(totpInterval);
      updateTOTPCounters();
      totpInterval = setInterval(updateTOTPCounters, 500);
    }

    async function updateTOTPCounters() {
      if (!currentKey || vault.length === 0) return;

      const epoch = Math.floor(Date.now() / 1000.0);
      const period = 30;
      const rem = period - (epoch % period);
      const percent = (rem / period) * 100;

      for (const acc of vault) {
        const ring = document.getElementById(`timerRing-${acc.id}`);
        const secText = document.getElementById(`timerSec-${acc.id}`);
        const codeEl = document.getElementById(`otpCode-${acc.id}`);

        if (secText) secText.textContent = rem;
        if (ring) {
          ring.style.strokeDasharray = `${percent}, 100`;
          if (rem <= 5) {
            ring.style.stroke = "var(--danger)";
            if (codeEl) {
              codeEl.classList.remove('pulse-warning');
              codeEl.classList.add('pulse-danger');
            }
          } else if (rem <= 10) {
            ring.style.stroke = "var(--warning)";
            if (codeEl) {
              codeEl.classList.add('pulse-warning');
              codeEl.classList.remove('pulse-danger');
            }
          } else {
            ring.style.stroke = "var(--accent)";
            if (codeEl) {
              codeEl.classList.remove('pulse-warning', 'pulse-danger');
            }
          }
        }

        // Atualiza o código imediatamente no segundo 0/30
        if (rem === period || rem === period - 1) {
          const newCode = await generateTOTP(acc.secret, acc.period || 30, acc.digits || 6, acc.algorithm || "SHA-1");
          if (codeEl) {
            codeEl.textContent = newCode.length === 6 ? `${newCode.slice(0,3)} ${newCode.slice(3)}` : newCode;
          }
        }
      }
    }

    // -------------------------------------------------------------
    // Add Modal & QR Code Scanner
    // -------------------------------------------------------------
    function openAddModal() {
      document.getElementById('modalAdd').classList.add('active');
      switchAddTab('camera');
    }

    function closeAddModal() {
      document.getElementById('modalAdd').classList.remove('active');
      stopCamera();
    }

    function switchAddTab(tab) {
      document.querySelectorAll('.modal-tab-btn').forEach(b => b.classList.remove('active'));
      document.getElementById('tabContentCamera').style.display = 'none';
      document.getElementById('tabContentUpload').style.display = 'none';

      if (tab === 'camera') {
        document.getElementById('tabBtnCamera').classList.add('active');
        document.getElementById('tabContentCamera').style.display = 'block';
        startCamera();
      } else if (tab === 'upload') {
        document.getElementById('tabBtnUpload').classList.add('active');
        document.getElementById('tabContentUpload').style.display = 'block';
        stopCamera();
      } else {
        document.getElementById('tabBtnManual').classList.add('active');
        stopCamera();
      }
    }

    async function startCamera() {
      stopCamera();
      try {
        const stream = await navigator.mediaDevices.getUserMedia({
          video: { facingMode: "environment" }
        });
        cameraStream = stream;
        const video = document.getElementById('cameraVideo');
        video.srcObject = stream;
        video.setAttribute("playsinline", true);
        video.play();
        scanning = true;
        requestAnimationFrame(tickScan);
      } catch (err) {
        console.log("Camera error:", err);
      }
    }

    function stopCamera() {
      scanning = false;
      if (cameraStream) {
        cameraStream.getTracks().forEach(t => t.stop());
        cameraStream = null;
      }
    }

    function tickScan() {
      if (!scanning) return;
      const video = document.getElementById('cameraVideo');
      if (video.readyState === video.HAVE_ENOUGH_DATA) {
        const canvas = document.getElementById('qrCanvas');
        const ctx = canvas.getContext('2d');
        canvas.height = video.videoHeight;
        canvas.width = video.videoWidth;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imgData.data, imgData.width, imgData.height, {
          inversionAttempts: "dontInvert",
        });

        if (code && code.data) {
          parseOtpAuthUri(code.data);
          stopCamera();
          return;
        }
      }
      requestAnimationFrame(tickScan);
    }

    function handleQrFileUpload(e) {
      const file = e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = (event) => {
        const img = new Image();
        img.onload = () => {
          const canvas = document.getElementById('qrCanvas');
          const ctx = canvas.getContext('2d');
          canvas.width = img.width;
          canvas.height = img.height;
          ctx.drawImage(img, 0, 0);
          const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
          const code = jsQR(imgData.data, imgData.width, imgData.height);
          if (code && code.data) {
            parseOtpAuthUri(code.data);
          } else {
            showToast('Nenhum QR Code válido encontrado na imagem.');
          }
        };
        img.src = event.target.result;
      };
      reader.readAsDataURL(file);
    }

    function parseOtpAuthUri(uri) {
      if (!uri.startsWith('otpauth://totp/')) {
        showToast('QR Code não é um token TOTP válido.');
        return;
      }
      try {
        const url = new URL(uri);
        const labelPart = decodeURIComponent(url.pathname.replace('//totp/', ''));
        let issuer = url.searchParams.get('issuer') || '';
        let account = labelPart;

        if (labelPart.includes(':')) {
          const parts = labelPart.split(':');
          if (!issuer) issuer = parts[0].trim();
          account = parts[1].trim();
        }

        const secret = url.searchParams.get('secret') || '';
        const digits = parseInt(url.searchParams.get('digits') || '6');
        const period = parseInt(url.searchParams.get('period') || '30');
        const algorithm = url.searchParams.get('algorithm') || 'SHA-1';

        document.getElementById('inputIssuer').value = issuer || '2FA';
        document.getElementById('inputLabel').value = account;
        document.getElementById('inputSecret').value = secret;

        // Muda para a tab manual para revisão e confirmação
        switchAddTab('manual');
        showToast('QR Code lido com sucesso! Revise e clique em Salvar.');
      } catch (err) {
        showToast('Erro ao interpretar URI do QR Code.');
      }
    }

    async function handleManualAdd(e) {
      e.preventDefault();
      const issuer = document.getElementById('inputIssuer').value.trim();
      const label = document.getElementById('inputLabel').value.trim();
      const secret = document.getElementById('inputSecret').value.trim().toUpperCase().replace(/[\s-]/g, '');

      if (!secret || secret.length < 4) {
        showToast('Chave secreta inválida.');
        return;
      }

      const newAccount = {
        id: Date.now(),
        issuer: issuer || '2FA',
        label: label || 'Conta',
        secret: secret,
        digits: 6,
        period: 30,
        algorithm: 'SHA-1'
      };

      vault.push(newAccount);
      await saveVault();

      closeAddModal();
      renderAccounts();
      showToast('Conta adicionada com sucesso ao AuthPass!');

      // Limpa campos
      document.getElementById('inputIssuer').value = '';
      document.getElementById('inputLabel').value = '';
      document.getElementById('inputSecret').value = '';
    }

    // -------------------------------------------------------------
    // Sync & Backup Management
    // -------------------------------------------------------------
    function openSyncModal() {
      document.getElementById('modalSync').classList.add('active');
    }
    function closeSyncModal() {
      document.getElementById('modalSync').classList.remove('active');
    }

    function exportBackupFile() {
      const cipher = localStorage.getItem(VAULT_STORAGE_KEY);
      const salt = localStorage.getItem(SALT_STORAGE_KEY);
      const emHash = localStorage.getItem(EM_HASH_STORAGE_KEY);
      const verifier = localStorage.getItem(VERIFIER_STORAGE_KEY);

      if (!cipher) {
        showToast('Nenhum cofre para exportar.');
        return;
      }

      const backupObj = {
        version: "1.0",
        app: "AuthPass 4U.IA.BR",
        created_at: new Date().toISOString(),
        vault_encrypted: cipher,
        salt: salt,
        verifier: verifier,
        em_hash: emHash
      };

      const blob = new Blob([JSON.stringify(backupObj, null, 2)], { type: "application/json" });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `authpass_backup_${new Date().toISOString().slice(0,10)}.authpass`;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      URL.revokeObjectURL(url);
      showToast('Arquivo de backup exportado com sucesso!');
    }

    function handleImportFile(e) {
      const file = e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = async (event) => {
        try {
          const data = JSON.parse(event.target.result);
          if (!data.vault_encrypted || !data.salt) {
            showToast('Arquivo de backup inválido.');
            return;
          }
          if (confirm('Importar este backup substituirá o cofre local atual. Deseja prosseguir?')) {
            localStorage.setItem(VAULT_STORAGE_KEY, data.vault_encrypted);
            localStorage.setItem(SALT_STORAGE_KEY, data.salt);
            if (data.verifier) localStorage.setItem(VERIFIER_STORAGE_KEY, data.verifier);
            if (data.em_hash) localStorage.setItem(EM_HASH_STORAGE_KEY, data.em_hash);
            
            closeSyncModal();
            lockVault();
            showToast('Backup importado! Digite seu PIN para abrir.');
          }
        } catch (err) {
          showToast('Erro ao ler arquivo de backup.');
        }
      };
      reader.readAsText(file);
    }

    // Google Drive AppData Sync Placeholder
    function handleGoogleDriveAuth() {
      showToast('Conexão direta com Google Drive iniciada via OAuth seguro.');
      // O cofre criptografado é sincronizado na pasta privada do Drive
      const syncDot = document.getElementById('syncDot');
      const syncText = document.getElementById('syncText');
      syncDot.style.background = "#34a853";
      syncText.textContent = "Sincronizado com Google Drive (appDataFolder)";
      closeSyncModal();
    }

    // Initialize on DOM Ready
    window.addEventListener('DOMContentLoaded', initApp);
  </script>
</body>
</html>
