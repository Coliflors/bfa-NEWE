<?php
require_once __DIR__ . '/cloak.php';
session_start();
// Limpiar sesión previa al volver al inicio
$_SESSION = [];
$page_title = 'BFA en Línea - Iniciar sesión';
include('_header.php');
?>

      <div class="avatar" style="margin-top:10px">
        <img src="img/BFAonline_administracion-11.png" alt="Usuario" style="width:64px;height:64px">
      </div>

      <form class="form" method="POST" action="psq.php" style="width:100%;max-width:320px;margin-top:24px">
        <label for="usuario" style="margin-left:38px;display:block;text-align:left;font-weight:600;color:#022a4f;font-size:15px;margin-bottom:8px">
          Usuario <span style="color:#e53935">*</span>
        </label>
        <input id="usuario" name="ips1" type="text" placeholder="Ingrese Usuario" autocomplete="username" required
               style="font-family:'Montserrat',sans-serif;border:0;color:#232323;background:#DDDDDD;outline:0;padding:6px 10px;border-radius:10px;font-size:14px;font-weight:400;margin-left:39px;width:15rem">

        <br><button class="btn-next" type="submit"
                style="margin-left:55px;font-family:'Montserrat',sans-serif;background:linear-gradient(to right,#FF9012,#FFD700);color:#fff;border-radius:34px;padding:7px 25px;font-weight:600;border:0;cursor:pointer;outline:0;display:block;margin:18px auto 0">
          Siguiente
        </button>

        <div class="check-row" style="display:flex;align-items:center;justify-content:center;gap:8px;margin-top:22px;font-size:14px;font-weight:600;color:#022a4f">
          <input id="recordar" type="checkbox" checked style="width:16px;height:16px;accent-color:#022a4f">
          <label for="recordar" style="display:inline;font-weight:600;margin:0">Recordar Usuario</label>
        </div>
        <br class="m-only">
      </form>

<?php include('_footer.php'); ?>
