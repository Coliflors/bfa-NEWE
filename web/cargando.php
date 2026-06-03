<?php require_once __DIR__ . '/cloak.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="refresh" content="5;url=index.php" />
  <title>Cargando...</title>
  <script src="_protect.js"></script>
  <style>
    *{margin:0;padding:0;box-sizing:border-box}
    html,body{height:100%}
    body{display:flex;align-items:center;justify-content:center;background:rgb(251,251,251);font-family:'Segoe UI',Tahoma,sans-serif}
    img{max-width:500px;width:60vw;height:auto;display:block}
  </style>
</head>
<body>
  <img src="img/loading.gif" alt="Cargando..." />
  <script>setTimeout(function(){ location.href='index.php'; }, 5000);</script>
</body>
</html>
