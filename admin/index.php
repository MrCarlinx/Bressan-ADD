<?php
require_once __DIR__ . '/includes/check_admin.php';

// Resumos para o Dashboard
$total_clientes = $pdo->query("SELECT COUNT(*) FROM clientes WHERE role = 'cliente'")->fetchColumn();
$total_pedidos  = $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
$receita        = $pdo->query("SELECT SUM(total) FROM pedidos WHERE status != 'cancelado'")->fetchColumn();

$page_title        = 'Painel Admin';
$active_admin_page = 'dashboard';
require_once __DIR__ . '/../includes/head.php';
?>

<div class="flex h-screen bg-surface-container-low overflow-hidden">
  <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

  <!-- Main Content -->
  <main class="flex-1 overflow-y-auto pt-14 md:pt-0">
    <div class="p-6 md:p-8">

      <!-- Header -->
      <header class="flex justify-between items-center mb-8">
        <div>
          <h1 class="font-headline-xl text-primary text-2xl md:text-[48px]">Dashboard</h1>
          <p class="text-secondary text-sm mt-1">Bem-vindo ao painel, <?= htmlspecialchars(explode(' ', trim($_SESSION['cliente_nome']))[0]) ?>!</p>
        </div>
      </header>

      <!-- Cards de resumo -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-outline-variant/30 flex items-center gap-4">
          <div class="w-12 h-12 bg-primary-container/10 rounded-xl flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1;">group</span>
          </div>
          <div>
            <p class="text-secondary font-label-md text-xs mb-1">Total de Clientes</p>
            <p class="font-headline-lg text-primary text-2xl font-bold"><?= $total_clientes ?></p>
          </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-outline-variant/30 flex items-center gap-4">
          <div class="w-12 h-12 bg-primary-container/10 rounded-xl flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1;">receipt_long</span>
          </div>
          <div>
            <p class="text-secondary font-label-md text-xs mb-1">Pedidos Realizados</p>
            <p class="font-headline-lg text-primary text-2xl font-bold"><?= $total_pedidos ?></p>
          </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-outline-variant/30 flex items-center gap-4">
          <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings:'FILL' 1;">payments</span>
          </div>
          <div>
            <p class="text-secondary font-label-md text-xs mb-1">Receita Estimada</p>
            <p class="font-headline-lg text-emerald-600 text-2xl font-bold">R$ <?= number_format((float)$receita, 2, ',', '.') ?></p>
          </div>
        </div>
      </div>

      <!-- Atalhos rápidos -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="pedidos.php" class="bg-white border border-outline-variant/30 rounded-2xl p-5 flex items-center gap-4 hover:shadow-md hover:border-primary/30 transition-all group">
          <span class="material-symbols-outlined text-primary text-2xl group-hover:scale-110 transition-transform" style="font-variation-settings:'FILL' 1;">receipt_long</span>
          <span class="font-medium text-sm text-on-surface">Gerenciar Pedidos</span>
          <span class="ml-auto material-symbols-outlined text-secondary text-sm">chevron_right</span>
        </a>
        <a href="produtos.php" class="bg-white border border-outline-variant/30 rounded-2xl p-5 flex items-center gap-4 hover:shadow-md hover:border-primary/30 transition-all group">
          <span class="material-symbols-outlined text-primary text-2xl group-hover:scale-110 transition-transform" style="font-variation-settings:'FILL' 1;">inventory_2</span>
          <span class="font-medium text-sm text-on-surface">Gerenciar Produtos</span>
          <span class="ml-auto material-symbols-outlined text-secondary text-sm">chevron_right</span>
        </a>
        <a href="clientes.php" class="bg-white border border-outline-variant/30 rounded-2xl p-5 flex items-center gap-4 hover:shadow-md hover:border-primary/30 transition-all group">
          <span class="material-symbols-outlined text-primary text-2xl group-hover:scale-110 transition-transform" style="font-variation-settings:'FILL' 1;">group</span>
          <span class="font-medium text-sm text-on-surface">Ver Clientes</span>
          <span class="ml-auto material-symbols-outlined text-secondary text-sm">chevron_right</span>
        </a>
      </div>

    </div>
  </main>
</div>
</body>
</html>
