<?php
require_once __DIR__ . '/../config.php';
$page_title  = 'Carrinho';
$active_page = 'catalogo';
require_once __DIR__ . '/../includes/head.php';

require_once __DIR__ . '/../includes/db.php';

// Inicializar carrinho na sessão se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$itens_carrinho = [];
$subtotal = 0;

if (!empty($_SESSION['carrinho'])) {
    $cartIds = array_map('intval', array_keys($_SESSION['carrinho']));
    $placeholders = implode(',', array_fill(0, count($cartIds), '?'));
    $stmt = $pdo->prepare("SELECT p.*, c.nome as cat FROM produtos p JOIN categorias c ON p.id_categoria = c.id WHERE p.id IN ($placeholders)");
    $stmt->execute($cartIds);
    $produtos_db = $stmt->fetchAll();
    
    foreach ($produtos_db as $prod) {
        $qty = $_SESSION['carrinho'][$prod['id']];
        $preco_final = $prod['preco_promocional'] ? $prod['preco_promocional'] : $prod['preco'];
        $itens_carrinho[] = [
            'id' => $prod['id'],
            'img' => asset($prod['img_url']),
            'cat' => $prod['cat'],
            'nome' => $prod['nome'],
            'desc' => $prod['descricao'],
            'qty' => $qty,
            'preco' => $preco_final
        ];
        $subtotal += $preco_final * $qty;
    }
}
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<main class="max-w-container-max mx-auto px-gutter py-section-padding mt-20">
  <header class="mb-stack-lg">
    <h1 class="font-headline-xl text-headline-xl text-primary mb-4">Seu Carrinho</h1>
    <div class="h-1 w-20 bg-tertiary-fixed-dim rounded-full"></div>
  </header>

  <div class="flex flex-col lg:flex-row gap-gutter">

    <!-- ── Lista de itens ────────────────────────────────────────────── -->
    <section class="flex-grow space-y-gutter">
      <?php if(empty($itens_carrinho)): ?>
        <div class="text-center py-12">
            <span class="material-symbols-outlined text-6xl text-outline-variant mb-4">production_quantity_limits</span>
            <h2 class="font-headline-md text-primary mb-2">Seu carrinho está vazio</h2>
            <p class="font-body-md text-secondary mb-6">Explore nosso catálogo e encontre os melhores produtos.</p>
            <a href="<?= page('catalogo.php') ?>" class="bg-primary text-on-primary px-8 py-3 rounded-full font-label-md">Ir para o Catálogo</a>
        </div>
      <?php else: ?>
        <?php foreach ($itens_carrinho as $item): ?>
        <div class="bg-surface-container-lowest custom-shadow rounded-xl p-stack-md flex gap-stack-md items-center group transition-transform duration-300" data-id="<?= $item['id'] ?>">
          <div class="w-32 h-32 flex-shrink-0 bg-surface rounded-lg overflow-hidden">
            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 src="<?= $item['img'] ?>" alt="<?= htmlspecialchars($item['nome']) ?>">
          </div>
          <div class="flex-grow flex flex-col md:flex-row justify-between md:items-center gap-stack-sm">
            <div class="space-y-1">
              <span class="font-label-sm text-label-sm text-tertiary-fixed-dim uppercase tracking-wider"><?= esc($item['cat']) ?></span>
              <h3 class="font-headline-md text-headline-md text-primary"><?= htmlspecialchars($item['nome']) ?></h3>
              <p class="font-body-md text-on-surface-variant max-w-sm truncate"><?= esc($item['desc']) ?></p>
            </div>
            <div class="flex items-center gap-stack-lg">
              <div class="flex items-center border border-outline-variant rounded-full px-2 py-1">
                <button class="btn-qty-minus material-symbols-outlined text-on-surface-variant p-1 hover:text-primary transition-colors">remove</button>
                <span class="qty-display px-4 font-label-md text-label-md"><?= $item['qty'] ?></span>
                <button class="btn-qty-plus material-symbols-outlined text-on-surface-variant p-1 hover:text-primary transition-colors">add</button>
              </div>
              <div class="text-right min-w-[120px]">
                <p class="font-headline-md text-headline-md text-primary">
                  R$ <?= number_format($item['preco'] * $item['qty'], 2, ',', '.') ?>
                </p>
              </div>
              <button class="btn-remove-item material-symbols-outlined text-on-surface-variant hover:text-error transition-colors">delete</button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>

    <!-- ── Resumo ────────────────────────────────────────────────────── -->
    <aside class="w-full lg:w-[400px]">
      <div class="bg-surface-container-low rounded-xl p-stack-lg sticky top-24 border border-outline-variant/30">
        <h2 class="font-headline-md text-headline-md text-primary mb-stack-md border-b border-outline-variant pb-4">Resumo do Pedido</h2>
        <div class="space-y-stack-md py-4">
          <div class="flex justify-between items-center">
            <span class="font-body-md text-on-surface-variant">Subtotal</span>
            <span class="font-body-md text-on-surface">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
          </div>
          <div class="flex justify-between items-center">
            <span class="font-body-md text-on-surface-variant">Frete</span>
            <span class="font-label-sm text-label-sm text-tertiary-fixed-dim uppercase">A Combinar</span>
          </div>
        </div>
        <div class="border-t border-outline-variant pt-4 mt-4 flex justify-between items-end mb-stack-lg">
          <div class="flex flex-col">
            <span class="font-label-md text-label-md text-on-surface-variant uppercase">Total Estimado</span>
            <span class="font-headline-lg text-headline-lg text-primary">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
          </div>
        </div>
        <?php if(isset($_SESSION['cliente_id'])): ?>
            <!-- Lógica de checkout com DB será adicionada aqui depois via form -->
            <form action="<?= page('checkout.php') ?>" method="POST" class="w-full">
                <?= csrf_field() ?>
                <button type="submit" class="w-full bg-emerald-cta text-on-primary py-4 px-gutter rounded-full font-headline-md flex items-center justify-center gap-stack-sm hover:opacity-90 active:scale-95 transition-all duration-150 shadow-lg">
                    <span class="material-symbols-outlined">send</span>
                    Finalizar Pedido
                </button>
            </form>
        <?php else: ?>
            <a href="<?= page('login.php?redirect=carrinho') ?>" class="w-full bg-primary text-on-primary py-4 px-gutter rounded-full font-headline-md flex items-center justify-center gap-stack-sm hover:opacity-90 active:scale-95 transition-all duration-150 shadow-lg">
                <span class="material-symbols-outlined">login</span>
                Fazer Login para Finalizar
            </a>
        <?php endif; ?>
        <div class="mt-stack-md flex items-center gap-2 justify-center text-on-surface-variant">
          <span class="material-symbols-outlined text-[18px]">verified_user</span>
          <span class="font-label-sm text-label-sm">Compra Segura e Atendimento Personalizado</span>
        </div>
      </div>
    </aside>

  </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= asset('js/main.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Ajax Cart Actions
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const cartPost = (params) => {
        const body = new URLSearchParams(params);
        body.set('csrf_token', csrfToken);
        return fetch('<?= SITE_URL ?>/ajax/cart_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: body.toString()
        }).then(res => res.json());
    };
    const updateCart = (id, qty) => {
        cartPost({ action: 'update', id, qty }).then(data => {
            if (data.success) location.reload();
        });
    };

    document.querySelectorAll('.btn-qty-plus').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const row = e.target.closest('[data-id]');
            const id = row.dataset.id;
            const currentQty = parseInt(row.querySelector('.qty-display').innerText);
            updateCart(id, currentQty + 1);
        });
    });

    document.querySelectorAll('.btn-qty-minus').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const row = e.target.closest('[data-id]');
            const id = row.dataset.id;
            const currentQty = parseInt(row.querySelector('.qty-display').innerText);
            if (currentQty > 1) {
                updateCart(id, currentQty - 1);
            }
        });
    });

    document.querySelectorAll('.btn-remove-item').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const id = e.target.closest('[data-id]').dataset.id;
            cartPost({ action: 'remove', id }).then(data => {
                if (data.success) location.reload();
            });
        });
    });
});
</script>
</body>
</html>
