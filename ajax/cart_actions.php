<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido.']);
    exit;
}

require_csrf();

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'get_cart':
            echo json_encode(['success' => true, 'cart' => $_SESSION['carrinho']]);
            exit;

        case 'add':
            $id = (int) ($_POST['id'] ?? 0);
            $qty = min(99, max(1, (int) ($_POST['qty'] ?? 1)));

            if ($id > 0) {
                $stmt = $pdo->prepare("SELECT id, preco, preco_promocional FROM produtos WHERE id = ?");
                $stmt->execute([$id]);
                $prod = $stmt->fetch();

                if ($prod) {
                    if (isset($_SESSION['carrinho'][$id])) {
                        $_SESSION['carrinho'][$id] = min(99, $_SESSION['carrinho'][$id] + $qty);
                    } else {
                        $_SESSION['carrinho'][$id] = $qty;
                    }
                    echo json_encode(['success' => true, 'count' => array_sum($_SESSION['carrinho'])]);
                    exit;
                }
            }
            throw new Exception('Produto inválido');

        case 'update':
            $id = (int) ($_POST['id'] ?? 0);
            $qty = min(99, max(1, (int) ($_POST['qty'] ?? 1)));
            if ($qty > 0 && isset($_SESSION['carrinho'][$id])) {
                $_SESSION['carrinho'][$id] = $qty;
            }
            echo json_encode(['success' => true]);
            exit;

        case 'remove':
            $id = (int) ($_POST['id'] ?? 0);
            if (isset($_SESSION['carrinho'][$id])) {
                unset($_SESSION['carrinho'][$id]);
            }
            echo json_encode(['success' => true]);
            exit;

        case 'clear':
            $_SESSION['carrinho'] = [];
            echo json_encode(['success' => true]);
            exit;

        default:
            throw new Exception('Ação inválida');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
