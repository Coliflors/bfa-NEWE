/* _protect.js — Anti-debug + Anti-back + Anti-inspect (client-side) */
(function () {
  'use strict';

  /* ─── 1. BLOQUEAR CLICK DERECHO (todas las variantes) ─── */
  ['contextmenu', 'auxclick'].forEach(function (ev) {
    window.addEventListener(ev, function (e) {
      e.preventDefault();
      e.stopPropagation();
      return false;
    }, true);
  });
  document.oncontextmenu = function () { return false; };

  /* ─── 2. BLOQUEAR TECLAS DE INSPECCIÓN ─── */
  function blockKeys(e) {
    var k = (e.key || '').toUpperCase();
    var c = (e.code || '').toUpperCase();
    // F12
    if (k === 'F12' || c === 'F12' || e.keyCode === 123) { e.preventDefault(); return false; }
    // Ctrl+Shift+I/J/C/K/E/M (DevTools)
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && /^[IJCKEM]$/.test(k)) { e.preventDefault(); return false; }
    // Ctrl+U (ver fuente), Ctrl+S (guardar), Ctrl+P (imprimir → DevTools), Ctrl+A (seleccionar)
    if ((e.ctrlKey || e.metaKey) && /^[USPAH]$/.test(k)) { e.preventDefault(); return false; }
    // Cmd+Opt+I/J/C/U (Mac)
    if (e.metaKey && e.altKey && /^[IJCU]$/.test(k)) { e.preventDefault(); return false; }
  }
  ['keydown', 'keyup', 'keypress'].forEach(function (ev) {
    window.addEventListener(ev, blockKeys, true);
  });

  /* ─── 3. BLOQUEAR SELECCIÓN, ARRASTRE Y COPIA ─── */
  ['selectstart', 'dragstart', 'copy', 'cut'].forEach(function (ev) {
    document.addEventListener(ev, function (e) {
      var t = e.target.tagName;
      if (t !== 'INPUT' && t !== 'TEXTAREA') { e.preventDefault(); return false; }
    });
  });

  /* ─── 4. DETECCIÓN DE DEVTOOLS ─── */
  var devtoolsOpen = false;
  function blockAndRedirect() {
    if (devtoolsOpen) return;
    devtoolsOpen = true;
    try { document.body.innerHTML = ''; } catch (e) {}
    location.replace('blocked.html');
  }
  // 4.a Por tamaño viewport vs window
  setInterval(function () {
    var th = 160;
    if ((window.outerWidth - window.innerWidth) > th ||
        (window.outerHeight - window.innerHeight) > th) {
      blockAndRedirect();
    }
  }, 700);
  // 4.b Por timing de debugger
  setInterval(function () {
    var t0 = performance.now();
    debugger;
    if (performance.now() - t0 > 100) blockAndRedirect();
  }, 1500);
  // 4.c Por toString trick (consola formatea objetos al hacer log)
  var bait = /./;
  bait.toString = function () { blockAndRedirect(); return ''; };
  setInterval(function () { try { console.log(bait); console.clear(); } catch (e) {} }, 1200);

  /* ─── 5. PREVENIR BOTÓN "ATRÁS" ─── */
  // Empuja una entrada extra y, cuando intente volver, vuelve a empujar.
  history.pushState(null, '', location.href);
  window.addEventListener('popstate', function () {
    history.pushState(null, '', location.href);
  });
  // Aviso al cerrar/recargar (algunos navegadores muestran un prompt)
  window.addEventListener('beforeunload', function (e) {
    // No mostramos texto custom (los navegadores modernos lo ignoran), pero retornar string activa el dialog en algunos
    e.preventDefault();
    e.returnValue = '';
    return '';
  });

  /* ─── 6. AVISO EN CONSOLA ─── */
  try {
    console.log('%c⛔ ALTO', 'color:red;font-size:48px;font-weight:bold;text-shadow:2px 2px 0 #000');
    console.log('%cEsta función es para desarrolladores. Si alguien te pidió pegar algo aquí, es un FRAUDE.', 'color:#000;font-size:16px;background:yellow;padding:4px');
  } catch (e) {}
})();
