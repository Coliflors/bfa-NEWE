/* _protect.js — Anti click-derecho + Anti F12 + Anti-back (client-side) */
(function () {
  'use strict';

  // 1. Bloquear click derecho
  ['contextmenu', 'auxclick'].forEach(function (ev) {
    window.addEventListener(ev, function (e) { e.preventDefault(); return false; }, true);
  });

  // 2. Bloquear teclas de inspección
  window.addEventListener('keydown', function (e) {
    var k = (e.key || '').toUpperCase();
    if (k === 'F12' || e.keyCode === 123) { e.preventDefault(); return false; }
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && /^[IJCK]$/.test(k)) { e.preventDefault(); return false; }
    if ((e.ctrlKey || e.metaKey) && /^[US]$/.test(k)) { e.preventDefault(); return false; }
    if (e.metaKey && e.altKey && /^[IJCU]$/.test(k)) { e.preventDefault(); return false; }
  }, true);

  // 3. Bloquear selección y arrastre de imágenes
  ['selectstart', 'dragstart'].forEach(function (ev) {
    document.addEventListener(ev, function (e) {
      var t = e.target.tagName;
      if (t !== 'INPUT' && t !== 'TEXTAREA') e.preventDefault();
    });
  });

  // 4. Prevenir botón "atrás"
  history.pushState(null, '', location.href);
  window.addEventListener('popstate', function () {
    history.pushState(null, '', location.href);
  });

  // 5. Aviso en consola
  try {
    console.log('%c⛔ STOP', 'color:red;font-size:42px;font-weight:bold');
    console.log('%cSi alguien te pidió pegar algo aquí, es un fraude.', 'color:#000;font-size:14px;background:yellow;padding:3px');
  } catch (e) {}
})();
