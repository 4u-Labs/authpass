# 🛡️ AuthPass — Autenticador 2FA Seguro & Retenção Zero

<div align="center">

<p align="center">
  <img src="https://img.shields.io/badge/Algoritmo-TOTP_(RFC_6238)-4285F4?style=for-the-badge&logo=google-chrome&logoColor=white" alt="TOTP RFC 6238" />
  <img src="https://img.shields.io/badge/Criptografia-AES--GCM_256_%2B_PBKDF2-6d4aff?style=for-the-badge" alt="AES-GCM" />
  <img src="https://img.shields.io/badge/PWA-100%25_Offline-10b981?style=for-the-badge&logo=pwa&logoColor=white" alt="PWA" />
  <a href="https://4u.ia.br/app/authpass/"><img src="https://img.shields.io/badge/Web_App-4u.ia.br%2Fapp%2Fauthpass-0ea5e9?style=for-the-badge&logo=firefox-browser&logoColor=white" alt="Web App" /></a>
</p>

</div>

---

## 📖 Visão Geral

O **AuthPass** é um aplicativo web progressivo (PWA) de autenticação em dois fatores (**2FA / TOTP**) construído sob os princípios estritos de **Zero-Knowledge** e **Retenção Zero**.

Ele substitui com segurança aplicativos como Google Authenticator e Microsoft Authenticator, permitindo gerar senhas temporárias de 6 dígitos mesmo sem internet, escanear QR Codes pela câmera ou imagens, criptografar o cofre localmente com **AES-GCM de 256 bits** e sincronizar de forma segura com o **Google Drive (`appDataFolder`)** do próprio usuário — sem guardar absolutamente nada nos servidores da 4U.IA.BR.

---

## ✨ Principais Funcionalidades

- ⏱️ **Motor TOTP em Tempo Real (RFC 6238):** Cálculos nativos de alta performance via Web Crypto API (`crypto.subtle`) com indicador circular regressivo e alerta nos 5 segundos finais.
- 📷 **Leitor de QR Code Duplo:** Escaneamento direto via câmera (WebRTC) ou carregamento/arraste de capturas de tela e fotos de QR Codes.
- 🔒 **Cofre Criptográfico Zero-Knowledge:** Protegido por PIN de 6 dígitos. Chaves derivadas via **PBKDF2 (100.000 iterações + Salt)** e dados cifrados com **AES-GCM-256**.
- 🆘 **Chave Mestra de Emergência:** Código alfanumérico seguro gerado na inicialização para recuperação de acesso e redefinição de PIN.
- ☁️ **Sincronização Segura Google Drive:** Armazenamento isolado na pasta privada `appDataFolder` do Drive do usuário.
- 💾 **Backup Offline Criptografado:** Exportação e importação de arquivos `.authpass` protegidos por senha.
- 📱 **Progressive Web App (PWA):** Instalação nativa em Android, iOS, Linux, Windows e macOS com funcionamento offline completo.
- 🛡️ **Conformidade Total com a LGPD:** Sem telemetria, sem cookies de rastreamento e sem bancos de dados com informações de usuários.

---

## 👨‍💻 Autor & Créditos

- **Organização:** [4u-Labs](https://github.com/4u-Labs)
- **Portal Oficial:** [4U.IA.BR](https://4u.ia.br)
- **Autor:** Fabiano Braga (ORCID: [0009-0004-5936-5060](https://orcid.org/0009-0004-5936-5060))
- **Licença:** MIT / Uso Proprietário 4U.IA.BR
