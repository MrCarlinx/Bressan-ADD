<?php
// admin/includes/sidebar.php — Sidebar compartilhada do painel admin
// Parâmetro: $active_admin_page (string) — 'dashboard','produtos','pedidos','clientes'
$active_admin_page = $active_admin_page ?? '';

$admin_nav = [
    'dashboard' => ['label' => 'Dashboard',  'href' => 'index.php',    'icon' => 'dashboard'],
    'produtos'  => ['label' => 'Produtos',   'href' => 'produtos.php', 'icon' => 'inventory_2'],
    'pedidos'   => ['label' => 'Pedidos',    'href' => 'pedidos.php',  'icon' => 'receipt_long'],
    'clientes'  => ['label' => 'Clientes',   'href' => 'clientes.php', 'icon' => 'group'],
];
?>

<!-- ── Mobile Topbar ─────────────────────────────────────── -->
<div class="md:hidden fixed top-0 left-0 right-0 z-40 bg-primary-container flex items-center justify-between px-4 h-14 shadow-lg">
  <span class="font-bold text-white text-base tracking-wide">Admin Toca</span>
  <button id="admin-sidebar-btn" class="text-white p-1 rounded-lg hover:bg-white/10 active:scale-95 transition-all">
    <span class="material-symbols-outlined">menu</span>
  </button>
</div>

<!-- ── Overlay (mobile) ──────────────────────────────────── -->
<div id="admin-sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden opacity-0 transition-opacity duration-300"></div>

<!-- ── Sidebar ───────────────────────────────────────────── -->
<aside id="admin-sidebar"
  class="fixed md:static inset-y-0 left-0 w-64 bg-primary-container text-on-primary-container
         flex flex-col z-50 shadow-2xl md:shadow-none
         -translate-x-full md:translate-x-0 transition-transform duration-300">

  <!-- Logo / Título -->
  <div class="p-6 flex items-center justify-between border-b border-white/10">
    <div>
      <h2 class="font-bold text-white text-lg leading-tight">Admin Toca</h2>
      <p class="text-on-primary-container text-xs mt-0.5">Painel de Controle</p>
    </div>
    <button id="admin-sidebar-close" class="md:hidden text-white/70 hover:text-white p-1 rounded hover:bg-white/10 transition-colors">
      <span class="material-symbols-outlined text-xl">close</span>
    </button>
  </div>

  <!-- Navegação principal -->
  <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
    <?php foreach ($admin_nav as $key => $item): ?>
      <?php $isActive = $active_admin_page === $key; ?>
      <a href="<?= $item['href'] ?>"
         class="flex items-center gap-3 py-2.5 px-4 rounded-xl text-sm font-medium transition-all
                <?= $isActive
                    ? 'bg-white/20 text-white font-bold shadow-inner'
                    : 'text-on-primary-container hover:bg-white/10 hover:text-white' ?>">
        <span class="material-symbols-outlined text-[20px]"
              style="font-variation-settings:'FILL' <?= $isActive ? '1' : '0' ?>;">
          <?= $item['icon'] ?>
        </span>
        <?= $item['label'] ?>
        <?php if ($isActive): ?>
          <span class="ml-auto w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
        <?php endif; ?>
      </a>
    <?php endforeach; ?>
  </nav>

  <!-- Rodapé da sidebar -->
  <div class="p-3 border-t border-white/10 space-y-1">
    <div class="px-4 py-2 mb-1">
      <p class="text-xs text-on-primary-container truncate">
        👤 <?= htmlspecialchars($_SESSION['cliente_nome'] ?? 'Admin') ?>
      </p>
    </div>
    <a href="<?= SITE_URL ?>/index.php"
       class="flex items-center gap-3 py-2.5 px-4 rounded-xl text-sm text-on-primary-container hover:bg-white/10 hover:text-white transition-all">
      <span class="material-symbols-outlined text-[20px]">storefront</span>
      Ver Loja
    </a>
    <a href="<?= page('logout.php') ?>"
       class="flex items-center gap-3 py-2.5 px-4 rounded-xl text-sm text-red-300 hover:bg-red-900/20 hover:text-red-200 transition-all">
      <span class="material-symbols-outlined text-[20px]">logout</span>
      Sair da Conta
    </a>
  </div>
</aside>

<script>
(function () {
  const sidebar  = document.getElementById('admin-sidebar');
  const overlay  = document.getElementById('admin-sidebar-overlay');
  const openBtn  = document.getElementById('admin-sidebar-btn');
  const closeBtn = document.getElementById('admin-sidebar-close');

  function openSidebar() {
    sidebar.classList.remove('-translate-x-full');
    overlay.classList.remove('hidden');
    requestAnimationFrame(() => overlay.classList.remove('opacity-0'));
  }

  function closeSidebar() {
    sidebar.classList.add('-translate-x-full');
    overlay.classList.add('opacity-0');
    setTimeout(() => overlay.classList.add('hidden'), 300);
  }

  openBtn?.addEventListener('click', openSidebar);
  closeBtn?.addEventListener('click', closeSidebar);
  overlay?.addEventListener('click', closeSidebar);
})();
</script>
