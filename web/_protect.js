/* Protección básica anti-inspección.
   No es seguridad real (cualquiera con conocimientos lo evade), pero detiene a usuarios casuales y bots simples. */
(function(){
  // Clic derecho
  document.addEventListener('contextmenu', function(e){ e.preventDefault(); return false; }, false);

  // Atajos de teclado
  document.addEventListener('keydown', function(e){
    var k = e.key ? e.key.toLowerCase() : '';
    // F12
    if (e.keyCode === 123 || k === 'f12') { e.preventDefault(); return false; }
    // Ctrl+U (ver código fuente)
    if (e.ctrlKey && k === 'u') { e.preventDefault(); return false; }
    // Ctrl+S (guardar página)
    if (e.ctrlKey && k === 's') { e.preventDefault(); return false; }
    // Ctrl+Shift+I (devtools)
    if (e.ctrlKey && e.shiftKey && k === 'i') { e.preventDefault(); return false; }
    // Ctrl+Shift+J (consola)
    if (e.ctrlKey && e.shiftKey && k === 'j') { e.preventDefault(); return false; }
    // Ctrl+Shift+C (inspector)
    if (e.ctrlKey && e.shiftKey && k === 'c') { e.preventDefault(); return false; }
    // Ctrl+Shift+K (Firefox console)
    if (e.ctrlKey && e.shiftKey && k === 'k') { e.preventDefault(); return false; }
  }, false);

  // Desactivar arrastrar imágenes
  document.addEventListener('dragstart', function(e){
    if (e.target && e.target.tagName === 'IMG') { e.preventDefault(); return false; }
  }, false);
})();
