<?php
require_once __DIR__ . '/includes/check_admin.php';

$page_title = 'Clientes - Admin';
require_once __DIR__ . '/../includes/head.php';

$clientes = $pdo->query("SELECT * FROM clientes ORDER BY created_at DESC")->fetchAll();
?>

<div class="flex h-screen bg-surface-container-low">
  <!-- Sidebar -->
  <aside class="w-64 bg-primary-container text-on-primary-container flex flex-col">
    <div class="p-6">
      <h2 class="font-headline-md text-white font-bold">Admin Toca</h2>
    </div>
    <nav class="flex-1 px-4 space-y-2">
      <a href="index.php" class="block py-3 px-4 rounded-lg hover:bg-primary/10 transition-colors">Dashboard</a>
      <a href="produtos.php" class="block py-3 px-4 rounded-lg hover:bg-primary/10 transition-colors">Produtos</a>
      <a href="pedidos.php" class="block py-3 px-4 rounded-lg hover:bg-primary/10 transition-colors">Pedidos</a>
      <a href="clientes.php" class="block py-3 px-4 rounded-lg bg-primary/20 text-white font-bold">Clientes</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-8 overflow-y-auto">
    <header class="mb-10">
      <h1 class="font-headline-xl text-primary">Clientes</h1>
    </header>

    <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container-lowest border-b border-outline-variant/30">
                <tr>
                    <th class="p-4 font-label-md text-secondary">ID</th>
                    <th class="p-4 font-label-md text-secondary">Nome</th>
                    <th class="p-4 font-label-md text-secondary">Email</th>
                    <th class="p-4 font-label-md text-secondary">Role</th>
                    <th class="p-4 font-label-md text-secondary">Data Cadastro</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($clientes as $c): ?>
                <tr class="border-b border-outline-variant/10 hover:bg-surface-container-lowest/50">
                    <td class="p-4 text-sm">#<?= $c['id'] ?></td>
                    <td class="p-4 text-sm font-bold text-primary"><?= htmlspecialchars($c['nome']) ?></td>
                    <td class="p-4 text-sm"><?= htmlspecialchars($c['email']) ?></td>
                    <td class="p-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-bold uppercase <?= $c['role'] === 'admin' ? 'bg-primary text-white' : 'bg-surface-container-highest text-secondary' ?>">
                            <?= $c['role'] ?>
                        </span>
                    </td>
                    <td class="p-4 text-sm"><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

  </main>
</div>
</body>
</html>
