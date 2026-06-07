<?php
require_once __DIR__ . '/includes/check_admin.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    require_csrf();

    $id = (int) ($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';

    if (!validar_status_pedido($status)) {
        $msg = 'Status inválido.';
    } else {
        $stmt = $pdo->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        $msg = 'Status do pedido atualizado!';
    }
}

$page_title = 'Pedidos - Admin';
require_once __DIR__ . '/../includes/head.php';

$pedidos = $pdo->query("SELECT p.*, c.nome as cliente_nome FROM pedidos p JOIN clientes c ON p.id_cliente = c.id ORDER BY p.data_pedido DESC")->fetchAll();
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
      <a href="pedidos.php" class="block py-3 px-4 rounded-lg bg-primary/20 text-white font-bold">Pedidos</a>
      <a href="clientes.php" class="block py-3 px-4 rounded-lg hover:bg-primary/10 transition-colors">Clientes</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-8 overflow-y-auto">
    <header class="mb-10">
      <h1 class="font-headline-xl text-primary">Gerenciar Pedidos</h1>
    </header>

    <?php if($msg): ?>
    <div class="bg-emerald-100 text-emerald-800 p-4 rounded-lg mb-6">
        <?= esc($msg) ?>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container-lowest border-b border-outline-variant/30">
                <tr>
                    <th class="p-4 font-label-md text-secondary">ID</th>
                    <th class="p-4 font-label-md text-secondary">Data</th>
                    <th class="p-4 font-label-md text-secondary">Cliente</th>
                    <th class="p-4 font-label-md text-secondary">Total</th>
                    <th class="p-4 font-label-md text-secondary">Status</th>
                    <th class="p-4 font-label-md text-secondary">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pedidos as $p): ?>
                <tr class="border-b border-outline-variant/10 hover:bg-surface-container-lowest/50">
                    <td class="p-4 text-sm font-bold">#<?= $p['id'] ?></td>
                    <td class="p-4 text-sm"><?= date('d/m/Y H:i', strtotime($p['data_pedido'])) ?></td>
                    <td class="p-4 text-sm"><?= htmlspecialchars($p['cliente_nome']) ?></td>
                    <td class="p-4 text-sm font-bold text-primary">R$ <?= number_format($p['total'], 2, ',', '.') ?></td>
                    <td class="p-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-bold uppercase
                            <?= $p['status'] == 'pendente' ? 'bg-amber-100 text-amber-800' : '' ?>
                            <?= $p['status'] == 'entregue' ? 'bg-emerald-100 text-emerald-800' : '' ?>
                            <?= $p['status'] == 'cancelado' ? 'bg-error-container text-on-error-container' : '' ?>
                            <?= in_array($p['status'], ['em_processamento', 'enviado']) ? 'bg-blue-100 text-blue-800' : '' ?>
                        ">
                            <?= str_replace('_', ' ', $p['status']) ?>
                        </span>
                    </td>
                    <td class="p-4 text-sm">
                        <form method="POST" class="flex items-center gap-2">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                            <select name="status" class="border rounded px-2 py-1 text-sm bg-white">
                                <option value="pendente" <?= $p['status'] == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                <option value="em_processamento" <?= $p['status'] == 'em_processamento' ? 'selected' : '' ?>>Em Processamento</option>
                                <option value="enviado" <?= $p['status'] == 'enviado' ? 'selected' : '' ?>>Enviado</option>
                                <option value="entregue" <?= $p['status'] == 'entregue' ? 'selected' : '' ?>>Entregue</option>
                                <option value="cancelado" <?= $p['status'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                            <button type="submit" class="bg-primary text-on-primary px-3 py-1 rounded text-xs hover:bg-primary/90">Atualizar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

  </main>
</div>
</body>
</html>
