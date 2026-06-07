<?php
// includes/header.php
// Parâmetro: $active_page — string com a página ativa ('inicio','catalogo','promocoes','historia','contato')
$active_page = $active_page ?? 'inicio';

$nav_links = [
    'inicio'    => ['label' => 'Início',        'href' => SITE_URL . '/index.php',      'icon' => 'home'],
    'catalogo'  => ['label' => 'Produtos',      'href' => page('catalogo.php'),          'icon' => 'inventory_2'],
    'promocoes' => ['label' => 'Promoções',     'href' => page('promocoes.php'),         'icon' => 'sell'],
    'historia'  => ['label' => 'Nossa História','href' => page('sobre.php'),             'icon' => 'history'],
    'contato'   => ['label' => 'Contato',       'href' => page('contato.php'),           'icon' => 'mail'],
];
?>
<header class="bg-surface-container-lowest shadow-sm sticky top-0 z-50">
  <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center w-full px-margin-mobile md:px-margin-desktop py-4 max-w-container-max mx-auto">
    <div class="text-2xl font-bold text-primary">
      <a href="<?= $nav_links['inicio']['href'] ?>" style="text-decoration:none;color:inherit;">Toca do Coelho</a>
    </div>

    <!-- Nav Desktop (escondido em mobile/tablet via CSS) -->
    <nav class="main-site-menu flex gap-8 items-center">
      <?php foreach ($nav_links as $key => $link): ?>
      <a class="text-sm font-semibold <?= $active_page === $key
          ? 'text-primary border-b-2 border-primary pb-1'
          : 'text-on-surface-variant hover:text-primary transition-colors duration-200' ?>"
         href="<?= $link['href'] ?>">
        <?= $link['label'] ?>
      </a>
      <?php endforeach; ?>
    </nav>

    <div class="flex items-center gap-4">
      <div class="relative hidden lg:block">
        <input class="bg-surface-container-low border-none rounded-full py-2 pl-4 pr-11 text-sm focus:ring-2 focus:ring-primary w-64"
               id="header-search" placeholder="Buscar produtos..." type="text"/>
        <button id="header-search-btn" type="button" title="Pesquisar"
                class="absolute right-1 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-full bg-primary text-on-primary hover:opacity-80 active:scale-90 transition-all">
          <span class="material-symbols-outlined text-[18px]">search</span>
        </button>
      </div>

      <a href="<?= page('carrinho.php') ?>"
         class="relative flex items-center justify-center hover:opacity-80 transition-opacity"
         title="Carrinho">
        <span class="material-symbols-outlined text-primary text-[24px]">shopping_cart</span>
        <?php
        $cartCount = isset($_SESSION['carrinho']) ? array_sum($_SESSION['carrinho']) : 0;
        if ($cartCount > 0): ?>
        <span class="absolute -top-1 -right-1 w-5 h-5 bg-tertiary text-on-tertiary text-[10px] font-bold rounded-full flex items-center justify-center" data-cart-count>
          <?= $cartCount ?>
        </span>
        <?php endif; ?>
      </a>

      <?php if (isset($_SESSION['cliente_id'])): ?>
      <div class="relative group">
        <button class="flex items-center gap-1 hover:opacity-80 transition-opacity">
          <span class="material-symbols-outlined text-primary text-[24px]">person</span>
          <span class="text-sm font-semibold text-primary hidden md:inline">
            <?= htmlspecialchars(explode(' ', trim($_SESSION['cliente_nome']))[0]) ?>
          </span>
        </button>
        <div class="absolute right-0 top-full mt-2 w-48 bg-white shadow-xl rounded-lg border border-outline-variant/20 hidden group-hover:block overflow-hidden z-50">
          <?php if ($_SESSION['cliente_role'] === 'admin'): ?>
          <a href="<?= SITE_URL ?>/admin/index.php"
             class="block px-4 py-3 text-sm text-on-surface hover:bg-surface-container-low transition-colors border-b border-outline-variant/10">
            Painel Admin
          </a>
          <?php endif; ?>
          <a href="<?= page('logout.php') ?>"
             class="block px-4 py-3 text-sm text-error hover:bg-red-50 transition-colors">
            Sair
          </a>
        </div>
      </div>
      <?php else: ?>
      <a href="<?= page('login.php') ?>"
         class="flex items-center justify-center hover:opacity-80 transition-opacity"
         title="Entrar">
        <span class="material-symbols-outlined text-primary text-[24px]">login</span>
      </a>
      <?php endif; ?>

      <!-- Hamburger Button (aparece em mobile/tablet via CSS) -->
      <button class="hamburger-btn" id="hamburger-btn" aria-label="Abrir menu">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </div>
</header>

<!-- Overlay do menu mobile -->
<div class="mobile-menu-overlay" id="mobile-menu-overlay"></div>

<!-- Menu Mobile Slide-in -->
<nav class="mobile-menu" id="mobile-menu">
  <div style="margin-bottom: 16px;">
    <span style="font-family: Lexend; font-size: 20px; font-weight: 700; color: #000;">Toca do Coelho</span>
  </div>
  <div class="mobile-divider"></div>
  <?php foreach ($nav_links as $key => $link): ?>
  <a href="<?= $link['href'] ?>" class="<?= $active_page === $key ? 'mobile-active' : '' ?>">
    <span class="material-symbols-outlined" style="font-size: 20px;"><?= $link['icon'] ?></span>
    <?= $link['label'] ?>
  </a>
  <?php endforeach; ?>
  <div class="mobile-divider"></div>
  <a href="<?= page('carrinho.php') ?>">
    <span class="material-symbols-outlined" style="font-size: 20px;">shopping_cart</span>
    Carrinho
    <?php if ($cartCount > 0): ?>
    <span style="background: #000; color: #fff; font-size: 10px; font-weight: bold; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; margin-left: auto;">
      <?= $cartCount ?>
    </span>
    <?php endif; ?>
  </a>
  <?php if (isset($_SESSION['cliente_id'])): ?>
    <?php if ($_SESSION['cliente_role'] === 'admin'): ?>
    <a href="<?= SITE_URL ?>/admin/index.php">
      <span class="material-symbols-outlined" style="font-size: 20px;">admin_panel_settings</span>
      Painel Admin
    </a>
    <?php endif; ?>
    <a href="<?= page('logout.php') ?>" style="color: #ba1a1a;">
      <span class="material-symbols-outlined" style="font-size: 20px; color: #ba1a1a;">logout</span>
      Sair
    </a>
  <?php else: ?>
    <a href="<?= page('login.php') ?>">
      <span class="material-symbols-outlined" style="font-size: 20px;">login</span>
      Entrar / Cadastrar
    </a>
  <?php endif; ?>
</nav>

<script>
(function () {
  // Busca no header
  const inp = document.getElementById('header-search');
  const searchBtn = document.getElementById('header-search-btn');

  function doSearch() {
    if (inp && inp.value.trim()) {
      window.location.href = window.SITE_URL + '/pages/catalogo.php?q=' + encodeURIComponent(inp.value.trim());
    }
  }

  if (inp) {
    inp.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') doSearch();
    });
  }

  if (searchBtn) {
    searchBtn.addEventListener('click', doSearch);
  }

  // Menu Hamburger
  const hamburgerBtn = document.getElementById('hamburger-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  const overlay = document.getElementById('mobile-menu-overlay');

  if (hamburgerBtn && mobileMenu && overlay) {
    function openMenu() {
      hamburgerBtn.classList.add('active');
      mobileMenu.classList.add('active');
      overlay.classList.add('active');
      overlay.style.display = 'block';
      document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
      hamburgerBtn.classList.remove('active');
      mobileMenu.classList.remove('active');
      overlay.classList.remove('active');
      document.body.style.overflow = '';
      setTimeout(() => { overlay.style.display = 'none'; }, 300);
    }

    hamburgerBtn.addEventListener('click', function () {
      if (mobileMenu.classList.contains('active')) {
        closeMenu();
      } else {
        openMenu();
      }
    });

    overlay.addEventListener('click', closeMenu);

    // Fechar ao redimensionar para desktop
    window.addEventListener('resize', function () {
      if (window.innerWidth > 768 && mobileMenu.classList.contains('active')) {
        closeMenu();
      }
    });
  }
})();
</script>
