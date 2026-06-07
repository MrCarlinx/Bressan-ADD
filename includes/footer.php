<?php
// includes/footer.php
?>
<footer class="w-full py-section-padding px-gutter flex flex-col md:flex-row justify-between items-center gap-base max-w-container-max mx-auto border-t border-outline-variant/20 bg-surface-container-low">
  <div class="flex flex-col items-center md:items-start gap-4">
    <span class="font-headline-md text-headline-md font-bold text-primary"><?= SITE_NAME ?></span>
    <p class="font-body-md text-label-sm text-secondary max-w-xs text-center md:text-left">
      © 1996-<?= date('Y') ?> <?= SITE_NAME ?>. Excelência em utilidades domésticas e soluções para o lar e gastronomia.
    </p>
  </div>

  <div class="flex flex-wrap justify-center gap-8 my-8 md:my-0">
    <a class="text-secondary hover:text-primary transition-colors font-label-sm text-label-sm" href="#">Termos de Uso</a>
    <a class="text-secondary hover:text-primary transition-colors font-label-sm text-label-sm" href="#">Política de Privacidade</a>
    <a class="text-secondary hover:text-primary transition-colors font-label-sm text-label-sm" href="#">Trocas e Devoluções</a>
    <a class="text-secondary hover:text-primary transition-colors font-label-sm text-label-sm"
       href="<?= whatsapp_link() ?>" target="_blank">WhatsApp Concierge</a>
  </div>

  <div class="flex items-center gap-4">
    <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-secondary hover:text-primary cursor-pointer transition-colors">
      <span class="material-symbols-outlined text-body-md">share</span>
    </div>
    <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-secondary hover:text-primary cursor-pointer transition-colors">
      <span class="material-symbols-outlined text-body-md">camera</span>
    </div>
    <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-secondary hover:text-primary cursor-pointer transition-colors">
      <span class="material-symbols-outlined text-body-md">location_on</span>
    </div>
  </div>
</footer>

<?php if (($active_page ?? '') !== 'contato'): ?>
<!-- WhatsApp Chatbot Widget -->
<div id="wa-widget" class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
  
  <!-- Janela de Chat (escondida por padrão) -->
  <div id="wa-chat-window" class="hidden w-80 bg-white rounded-2xl shadow-2xl border border-outline-variant/30 mb-4 flex flex-col transition-all origin-bottom-right transform scale-95 opacity-0 data-[state=open]:scale-100 data-[state=open]:opacity-100 duration-200">
    <!-- Header do Chat -->
    <div class="bg-emerald-cta text-white p-4 flex items-center justify-between rounded-t-2xl">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">support_agent</span>
        </div>
        <div>
          <h4 class="font-bold text-sm leading-tight">Toca do Coelho</h4>
          <p class="text-xs text-white/80 mt-0.5">Atendimento via WhatsApp</p>
        </div>
      </div>
      <button id="wa-close-btn" class="text-white hover:text-white/80 p-1 rounded-full hover:bg-white/10 transition-colors">
        <span class="material-symbols-outlined text-sm">close</span>
      </button>
    </div>

    <!-- Corpo do Chat -->
    <div class="p-4 bg-surface-container-low flex flex-col gap-3">
      <!-- Mensagem Bot -->
      <div class="flex items-start gap-2 max-w-[85%]">
        <div class="bg-white text-on-surface p-3 rounded-2xl rounded-tl-sm shadow-sm text-sm border border-outline-variant/10 leading-relaxed">
          Olá! Bem-vindo à Toca do Coelho. 🐰<br>Como podemos ajudar você hoje?
        </div>
      </div>

      <!-- Quick Replies -->
      <div class="flex flex-col gap-2 mt-1">
        <button class="wa-quick-reply text-left text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl px-3 py-2 hover:bg-emerald-100 transition-colors" data-msg="Olá! Gostaria de informações sobre os produtos.">
          🛍️ Dúvidas sobre produtos
        </button>
        <button class="wa-quick-reply text-left text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl px-3 py-2 hover:bg-emerald-100 transition-colors" data-msg="Olá! Gostaria de acompanhar meu pedido.">
          📦 Acompanhar meu pedido
        </button>
        <button class="wa-quick-reply text-left text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl px-3 py-2 hover:bg-emerald-100 transition-colors" data-msg="Olá! Gostaria de falar com um atendente.">
          👩‍💻 Falar com atendente
        </button>
      </div>
    </div>

    <!-- Indicador de redirecionamento + Input Area -->
    <div class="px-4 pb-1 bg-white">
      <p class="text-[10px] text-center text-gray-400 flex items-center justify-center gap-1">
        <span class="material-symbols-outlined" style="font-size:12px;">open_in_new</span>
        Será redirecionado para o WhatsApp
      </p>
    </div>
    <div class="p-3 bg-white border-t border-outline-variant/20 flex items-center gap-2 rounded-b-2xl">
      <input type="text" id="wa-chat-input" class="flex-1 bg-surface-container-low border-none rounded-full px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-cta outline-none transition-shadow" placeholder="Ou escreva sua mensagem...">
      <button id="wa-send-btn" class="w-10 h-10 bg-emerald-cta text-white rounded-full flex items-center justify-center hover:bg-emerald-600 transition-colors shrink-0 shadow-md">
        <span class="material-symbols-outlined text-sm ml-0.5" style="font-variation-settings:'FILL' 1;">send</span>
      </button>
    </div>
  </div>

  <!-- Botão Flutuante (FAB) -->
  <button id="wa-toggle-btn" class="bg-emerald-cta text-white w-16 h-16 rounded-full flex items-center justify-center shadow-[0_8px_30px_rgb(16,185,129,0.3)] hover:scale-105 active:scale-95 transition-all group relative">
    <span id="wa-fab-icon" class="material-symbols-outlined text-3xl" style="font-variation-settings:'FILL' 1;">chat</span>
    <!-- Badge de notificação -->
    <span id="wa-badge" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-md">1</span>
    <!-- Tooltip -->
    <span class="absolute right-full mr-4 bg-white text-emerald-700 font-bold py-2 px-4 rounded-xl shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none md:block hidden">
      Precisa de ajuda?
    </span>
  </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const chatWindow   = document.getElementById('wa-chat-window');
  const toggleBtn    = document.getElementById('wa-toggle-btn');
  const closeBtn     = document.getElementById('wa-close-btn');
  const sendBtn      = document.getElementById('wa-send-btn');
  const chatInput    = document.getElementById('wa-chat-input');
  const fabIcon      = document.getElementById('wa-fab-icon');
  const badge        = document.getElementById('wa-badge');
  const quickReplies = document.querySelectorAll('.wa-quick-reply');

  const WHATSAPP_NUMBER = '<?= WHATSAPP_NUMBER ?>';
  let isOpen = false;

  // ── Abrir ──────────────────────────────────────────────
  function openChat() {
    isOpen = true;
    chatWindow.classList.remove('hidden');
    // Duplo rAF: garante que display:flex está aplicado antes de animar
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        chatWindow.setAttribute('data-state', 'open');
        chatInput.focus();
      });
    });
    fabIcon.textContent = 'close';
    badge.classList.add('hidden');
  }

  // ── Fechar: usa transitionend para só esconder após a animação terminar ──
  function closeChat() {
    if (!isOpen) return;
    isOpen = false;
    chatWindow.removeAttribute('data-state');
    fabIcon.textContent = 'chat';

    function onTransitionEnd(e) {
      // Reage apenas ao opacity ou transform, não a outros filhos
      if (e.target !== chatWindow) return;
      chatWindow.classList.add('hidden');
      chatWindow.removeEventListener('transitionend', onTransitionEnd);
    }
    chatWindow.addEventListener('transitionend', onTransitionEnd);
  }

  function toggleChat() {
    isOpen ? closeChat() : openChat();
  }

  // ── Envio: abre WhatsApp com a mensagem ──────────────────
  function sendMessage(msgText) {
    const text = (msgText || chatInput.value).trim();
    if (!text) return;
    const url = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
    chatInput.value = '';
    closeChat();
  }

  // ── Eventos ───────────────────────────────────────────
  toggleBtn.addEventListener('click', toggleChat);
  closeBtn.addEventListener('click', closeChat);
  sendBtn.addEventListener('click', () => sendMessage());

  chatInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') sendMessage();
  });

  // Quick reply buttons
  quickReplies.forEach((btn) => {
    btn.addEventListener('click', () => sendMessage(btn.dataset.msg));
  });

  // Fechar ao clicar fora do widget
  document.addEventListener('click', (e) => {
    if (isOpen && !document.getElementById('wa-widget').contains(e.target)) {
      closeChat();
    }
  });
});
</script>
<?php endif; ?>