<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['cliente_id']) || empty($_SESSION['carrinho'])) {
    header('Location: ' . page('carrinho.php'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    try {
        $pdo->beginTransaction();

        $cliente_id = (int) $_SESSION['cliente_id'];
        $cartIds = array_map('intval', array_keys($_SESSION['carrinho']));

        if (empty($cartIds)) {
            throw new RuntimeException('Carrinho vazio.');
        }

        $placeholders = implode(',', array_fill(0, count($cartIds), '?'));
        $stmt = $pdo->prepare("SELECT id, nome, preco, preco_promocional FROM produtos WHERE id IN ($placeholders)");
        $stmt->execute($cartIds);
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($produtos) !== count($cartIds)) {
            throw new RuntimeException('Um ou mais produtos do carrinho não estão mais disponíveis.');
        }

        $total = 0;
        $itens_db = [];
        $itens_msg = [];

        foreach ($produtos as $p) {
            $qty = min(99, max(1, (int) ($_SESSION['carrinho'][$p['id']] ?? 1)));
            $preco = $p['preco_promocional'] ?: $p['preco'];
            $total += $preco * $qty;

            $itens_db[] = [
                'id'    => $p['id'],
                'qty'   => $qty,
                'preco' => $preco,
            ];

            $itens_msg[] = "{$qty}x {$p['nome']} (R$ " . number_format($preco, 2, ',', '.') . ")";
        }

        $stmt = $pdo->prepare("INSERT INTO pedidos (id_cliente, total, status) VALUES (?, ?, 'pendente')");
        $stmt->execute([$cliente_id, $total]);
        $pedido_id = $pdo->lastInsertId();

        $stmt_item = $pdo->prepare("INSERT INTO pedido_itens (id_pedido, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
        foreach ($itens_db as $item) {
            $stmt_item->execute([$pedido_id, $item['id'], $item['qty'], $item['preco']]);
        }

        $pdo->commit();

        $_SESSION['carrinho'] = [];

        $stmt = $pdo->prepare("SELECT nome FROM clientes WHERE id = ?");
        $stmt->execute([$cliente_id]);
        $cliente = $stmt->fetch();

        $msg = "Olá! Gostaria de finalizar meu pedido (#{$pedido_id}) pelo site.\n\n";
        $msg .= "Cliente: {$cliente['nome']}\n\n";
        $msg .= "Itens do Pedido:\n" . implode("\n", $itens_msg) . "\n\n";
        $msg .= "Total Estimado: R$ " . number_format($total, 2, ',', '.');

        header('Location: ' . whatsapp_link($msg));
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        log_error('Checkout failed: ' . $e->getMessage());
        app_error('Erro ao processar pedido. Tente novamente.');
    }
} else {
    header('Location: ' . page('carrinho.php'));
    exit;
}
