<?php
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    // Bitrix24 виджет (чат с оператором)
    ?>
    <script data-skip-moving="true">
      (function(w,d,u){
        var s=d.createElement('script'); s.async=true; s.src=u+'?'+(Date.now()/60000|0);
        var h=d.getElementsByTagName('script')[0]; h.parentNode.insertBefore(s,h);
      })(window,document,'https://cdn-ru.bitrix24.ru/b21206704/crm/site_button/loader_1_z52dlt.js');
    </script>

<style>
  /* === Попап (namespaced) === */
  .lp-popup-overlay{
    position:fixed; inset:0; background:rgba(0,0,0,.6);
    display:none; align-items:center; justify-content:center;
    z-index:9999; pointer-events:all;
  }
  .lp-popup{
    background:#fff; padding:28px; border-radius:12px;
    max-width:460px; width:92%;
    box-shadow:0 10px 30px rgba(0,0,0,.25);
    position:relative; margin:0 auto; gap:16px; pointer-events:auto;
    z-index:10000;
  }
  .lp-popup h2{margin:0 0 12px;font-size:20px}
  .lp-popup p.sub{margin:0 0 14px;color:#6b7280;font-size:14px}
  .lp-popup form input,.lp-popup form button{
    width:100%; padding:12px 14px; margin:7px 0;
    border-radius:10px; border:1px solid #d1d5db; font-size:14px;
  }
  .lp-popup form button{background:#2977b5;color:#fff;border:none;cursor:pointer}
  .lp-popup form button:disabled{opacity:.6;cursor:default}

  .lp-popup-close{
    position:absolute; top:8px; right:8px;
    width:44px; height:44px;
    display:flex; align-items:center; justify-content:center;
    font-size:26px; line-height:1; font-weight:bold;
    color:#6b7280; cursor:pointer;
    background:transparent; border:0;
    z-index:10001; pointer-events:auto;
  }
  .lp-popup-close:hover{ color:#111827; }

  /* Возвращаем «правые» кнопки поверх футера */
  .b24-widget-button-wrapper,.b24-widget-button-position,.b24-widget-button-block{z-index:2147483000!important}
  /* На время открытого попапа опустим их ниже затемнения */
  body.popup-open .b24-widget-button-wrapper,
  body.popup-open .b24-widget-button-position,
  body.popup-open .b24-widget-button-block{z-index:9000!important}
</style>

<div class="lp-popup-overlay" id="leadPopupOverlay" aria-hidden="true">
  <div class="lp-popup" role="dialog" aria-modal="true" aria-labelledby="leadPopupTitle">
    <button type="button"
        class="lp-popup-close"
        id="leadPopupClose"
        aria-label="Закрыть"
        onclick="window.__lpClose && window.__lpClose(event)">×</button>
    <div>
      <h2 id="leadPopupTitle">Оставьте заявку</h2>
      <p class="sub">Мы свяжемся с вами в ближайшее время</p>
    </div>
    <form id="leadPopupForm" method="post" action="/ajax/cart/index.php" novalidate>
      <input type="hidden" name="EVENT" value="sendForm">
      <input type="hidden" name="FORM_NAME" value="POPUP_LEAD">
      <input type="hidden" name="EMAIL_EVENT_ID" value="WEBCOMP_NEW_ORDER">
      <input type="hidden" name="IBLOCK_ID" value="6">
      <input type="hidden" name="sessid" value="<?= bitrix_sessid(); ?>">
      <input type="text"  name="NAME"  placeholder="Ваше имя" required />
      <input type="tel"   name="PHONE" placeholder="Телефон" required />
      <button type="submit">Отправить</button>
      <div id="leadPopupMsg" style="margin-top:8px;font-size:13px;color:#6b7280"></div>
    </form>
  </div>
</div>

<script>
(function(){
  if (window._leadPopupInit) return;
  window._leadPopupInit = true;

  var overlay = document.getElementById('leadPopupOverlay');
  var popup   = overlay.querySelector('.lp-popup');
  var form    = document.getElementById('leadPopupForm');
  var msg     = document.getElementById('leadPopupMsg');

  // --- ключи localStorage ---
  var LS_NEXT_AT    = 'leadPopupNextAt';      // когда снова показывать (timestamp, ms)
  var LS_CLOSE_CNT  = 'leadPopupCloseCount';  // сколько раз закрывали
  var LS_SUBMITTED  = 'leadPopupSubmitted';   // форма успешно отправлена (перманентно скрывать)

  function getCloseCount(){ return parseInt(localStorage.getItem(LS_CLOSE_CNT) || '0', 10); }
  function setCloseCount(v){ localStorage.setItem(LS_CLOSE_CNT, String(v)); }

  function getNextAt(){ return parseInt(localStorage.getItem(LS_NEXT_AT) || '0', 10); }
  function setNextAt(ts){ localStorage.setItem(LS_NEXT_AT, String(ts)); }

  function isSubmitted(){ return localStorage.getItem(LS_SUBMITTED) === '1'; }
  function markSubmitted(){ localStorage.setItem(LS_SUBMITTED, '1'); }

  // Следующие интервалы после закрытия: +30 сек каждый раз, начиная с 1:00
  function calcNextDelayMs(closeCount){
    var minutes = 1 + 0.5 * closeCount; // 1.0, 1.5, 2.0, 2.5, ...
    return Math.round(minutes * 60 * 1000);
  }

  function openPopup(){
    if (isSubmitted()) return; // на всякий случай
    overlay.style.display = 'flex';
    overlay.setAttribute('aria-hidden','false');
    document.body.classList.add('popup-open');
  }

  function closePopup(){
    overlay.style.display = 'none';
    overlay.setAttribute('aria-hidden','true');
    document.body.classList.remove('popup-open');

    // если форма была успешно отправлена — больше не планируем показы
    if (isSubmitted()) return;

    // планируем следующий показ с увеличенным интервалом
    var cnt = getCloseCount() + 1;
    setCloseCount(cnt);
    var nextDelay = calcNextDelayMs(cnt);
    setNextAt(Date.now() + nextDelay);
  }

  // даём доступ inline-кнопке
  window.__lpClose = function(e){ if (e){ e.preventDefault(); e.stopPropagation(); } closePopup(); };

  // --- первое/следующее появление ---
  (function scheduleShow(){
    if (isSubmitted()) return; // уже отправляли — больше не показываем

    var now = Date.now();
    var nextAt = getNextAt();
    var initialDelay = 30000; // первый показ через 30 секунд

    var delay = nextAt > now ? (nextAt - now) : initialDelay;

    setTimeout(function(){
      if (isSubmitted()) return;
      if (getNextAt() <= Date.now()) openPopup();
      else scheduleShow(); // если «догнали» раньше срока, перепланируем
    }, delay);
  })();

  // --- UX-обработчики ---
  overlay.addEventListener('click', function(e){
    if (!e.target.closest('.lp-popup')) closePopup();
  });
  popup.addEventListener('click', function(e){ e.stopPropagation(); }, true);
  document.addEventListener('click', function(e){
    if (e.target && e.target.closest('#leadPopupClose')) {
      e.preventDefault(); e.stopPropagation(); closePopup();
    }
  }, true);
  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape' && overlay.style.display === 'flex') closePopup();
  });

  // --- отправка формы ---
  form.addEventListener('submit', function(e){
    e.preventDefault();
    msg.textContent = '';
    var btn = form.querySelector('button[type="submit"]'); if (btn) btn.disabled = true;
    var fd = new FormData(form);

    fetch('/ajax/cart/index.php', { method:'POST', body:fd, credentials:'same-origin' })
      .then(function(r){ return r.json(); })
      .then(function(d){
        if (d && d.status){
          // навсегда отключаем дальнейшие показы
          markSubmitted();
          localStorage.removeItem(LS_NEXT_AT);

          msg.style.color = '#10b981';
          msg.textContent = 'Спасибо! Ваша заявка принята.';
          setTimeout(closePopup, 1200);
          form.reset();
        } else {
          msg.style.color = '#ef4444';
          msg.textContent = 'Не удалось отправить' + (d && d.reason ? ' ('+d.reason+')' : '') + '. Попробуйте позже.';
        }
      })
      .catch(function(){
        msg.style.color = '#ef4444';
        msg.textContent = 'Ошибка соединения. Попробуйте позже.';
      })
      .finally(function(){ if (btn) btn.disabled = false; });
  });
})();
</script>

<script>
// --- 1) Полноценная заглушка JSSeo с ожидаемой структурой ---
window.JSSeo = window.JSSeo || {};
(function(S){
  var noop = function(){};
  S.init = S.init || noop;
  S.view = S.view || noop;
  S.event = S.event || noop;
  S.set = S.set || noop;
  S.push = S.push || noop;
  S.success = S.success || noop;

  S.Yandex = S.Yandex || {
    Enabled: false,
    Init: noop,
    ReachGoal: noop,
    goals: {}
  };
  S.Google = S.Google || {
    Enabled: false,
    Init: noop,
    Event: noop,
    goals: {}
  };
})(window.JSSeo);
</script>

<script>
// --- 2) Подстрахуем getDelivery: не трогать el.dataset, если элемента нет ---
(function waitPatch(){
  if (window.JSCartDelivery && window.JSCartDelivery.prototype
      && typeof window.JSCartDelivery.prototype.getDelivery === 'function') {

    var origGetDelivery = window.JSCartDelivery.prototype.getDelivery;

    window.JSCartDelivery.prototype.getDelivery = function(){
      var el = document.querySelector(
        '[data-delivery-active], [data-delivery]:not([hidden]), [data-delivery-id].is-active, #cart-delivery'
      );
      if (!el) {
        console.warn('[cart] delivery element not found, skip getDelivery');
        return;
      }
      try {
        return origGetDelivery.apply(this, arguments);
      } catch(e){
        console.error('[cart] getDelivery guarded error:', e);
      }
    };
  } else {
    setTimeout(waitPatch, 200);
  }
})();
</script>

<script>
// --- 3) Страховка: если где-то вызывают напрямую, не роняем страницу ---
window.addEventListener('error', function(e){
  var msg = String(e && e.message || '');
  if (msg.includes('dataset') && msg.includes('null')) {
    e.preventDefault();
    console.warn('[guard] suppressed dataset null error:', msg);
  }
}, true);
</script>
<script>
// Переписываем кнопки Bitrix24-виджета, чтобы Яндекс не видел <a без href>
(function fixB24Links(){
    const tryFix = () => {
        // Все виджет-кнопки от Bitrix24
        const btns = document.querySelectorAll(
            'a[data-b24-crm-button-widget], a[data-b24-crm-button-widget-blank]'
        );
        if (!btns.length) return false;

        btns.forEach(a => {
            // Если уже обработано — пропускаем
            if (a.__fixed) return;

            const div = document.createElement('div');
            // Делаем div "кнопкой"
            div.setAttribute('role', 'button');

            // Копируем все data-атрибуты (Bitrix24 читает их)
            for (const attr of a.attributes) {
                if (attr.name.startsWith('data-')) {
                    div.setAttribute(attr.name, attr.value);
                }
            }

            // Сохраняем стили
            div.style.cssText = a.style.cssText;
            div.className = a.className;

            // Переносим в DOM
            a.parentNode.replaceChild(div, a);

            div.__fixed = true;
        });

        return true;
    };

    // Пробуем несколько раз (виджет подгружается асинхронно)
    let attempts = 0;
    const interval = setInterval(() => {
        attempts++;
        if (tryFix() || attempts > 20) clearInterval(interval);
    }, 300);
})();
</script>
    <?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
?>