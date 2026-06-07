<?php
require_once __DIR__ . '/../config.php';
$page_title  = 'Catálogo';
$active_page = 'catalogo';
require_once __DIR__ . '/../includes/head.php';

require_once __DIR__ . '/../includes/db.php';

// Buscar categorias
$stmt = $pdo->query("SELECT * FROM categorias ORDER BY nome");
$categorias_db = $stmt->fetchAll();
$categorias = array_column($categorias_db, 'nome');

// Construir query de produtos
$query = "SELECT p.*, c.nome as cat FROM produtos p JOIN categorias c ON p.id_categoria = c.id WHERE 1=1";
$params = [];

if (!empty($_GET['cat'])) {
    $query .= " AND c.nome = ?";
    $params[] = $_GET['cat'];
}

if (!empty($_GET['oferta'])) {
    $query .= " AND p.preco_promocional IS NOT NULL";
}

$query .= " ORDER BY p.nome";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$produtos = $stmt->fetchAll();
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<main class="pt-16 pb-section-padding px-gutter max-w-container-max mx-auto">
  <div class="flex flex-col md:flex-row gap-stack-lg">

    <!-- ── Sidebar filtros ───────────────────────────────────────────── -->
    <aside class="w-full md:w-64 flex-shrink-0">
      <div class="sticky top-24 space-y-stack-lg">
        <div>
          <h3 class="font-headline-md text-headline-md text-primary mb-stack-md">Categorias</h3>
          <ul class="space-y-3">
            <?php foreach ($categorias as $cat): ?>
            <li>
              <a href="?cat=<?= urlencode($cat) ?>"
                 class="font-body-md text-body-md text-secondary hover:text-primary flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-tertiary-fixed-dim"></span>
                <?= htmlspecialchars($cat) ?>
              </a>
            </li>
            <?php endforeach; ?>
            <li>
              <a href="?oferta=1"
                 class="font-label-md text-label-md text-error font-bold flex items-center gap-2 uppercase tracking-wider">
                <span class="material-symbols-outlined text-error text-[18px]">sell</span> Promoções
              </a>
            </li>
          </ul>
        </div>

        <div>
          <h3 class="font-headline-md text-headline-md text-primary mb-stack-md">Buscar</h3>
          <input id="search-input" type="text" placeholder="Nome do produto..."
                 class="w-full border-b border-outline py-2 bg-transparent focus:outline-none text-body-md">
        </div>
      </div>
    </aside>

    <!-- ── Grid de produtos ──────────────────────────────────────────── -->
    <div class="flex-grow">
      <div class="flex justify-between items-end mb-stack-lg border-b border-outline-variant/20 pb-4">
        <div>
          <h2 class="font-headline-xl text-headline-xl text-primary">Catálogo Digital</h2>
          <p class="font-body-md text-body-md text-secondary mt-2">Explore nossa seleção curada de utilidades premium.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($produtos as $p): ?>
        <div class="group card-hover bg-surface-container-lowest rounded-xl overflow-hidden custom-shadow"
             data-product-card="<?= htmlspecialchars(strtolower($p['nome'] . ' ' . $p['cat'])) ?>">
          <div class="aspect-square bg-surface-container-low overflow-hidden relative">
            <?php if ($p['badge']): ?>
            <span class="absolute top-4 left-4 z-10 bg-error text-white font-label-md text-label-md px-3 py-1 rounded-full">
              <?= esc($p['badge']) ?>
            </span>
            <?php endif; ?>
            <img alt="<?= htmlspecialchars($p['nome']) ?>"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                 src="<?= esc(asset($p['img_url'])) ?>">
          </div>
          <div class="p-6">
            <span class="font-label-sm text-label-sm text-tertiary-fixed-dim bg-tertiary-fixed-dim/10 px-2 py-1 rounded mb-2 inline-block uppercase tracking-wider">
              <?= htmlspecialchars($p['cat']) ?>
            </span>
            <h4 class="font-headline-md text-headline-md text-primary mb-1"><?= htmlspecialchars($p['nome']) ?></h4>
            <p class="font-body-md text-body-md text-on-surface-variant font-bold mb-4">
                <?php if($p['preco_promocional']): ?>
                    <span class="line-through text-xs text-outline">R$ <?= number_format($p['preco'], 2, ',', '.') ?></span>
                    <span class="text-error ml-1">R$ <?= number_format($p['preco_promocional'], 2, ',', '.') ?></span>
                <?php else: ?>
                    R$ <?= number_format($p['preco'], 2, ',', '.') ?>
                <?php endif; ?>
            </p>
            <a href="<?= page('produto.php?id=' . $p['id']) ?>"
               class="w-full bg-primary hover:opacity-90 text-on-primary font-label-md text-sm py-2.5 rounded-lg flex items-center justify-center gap-2 transition-all active:scale-95 shadow-sm mb-2">
              <span class="material-symbols-outlined text-[20px]">visibility</span>
              Ver Detalhes
            </a>
            <button onclick="addCart(<?= $p['id'] ?>)"
               class="w-full border-2 border-outline-variant/30 hover:bg-surface-dim text-on-surface font-label-md text-sm py-2 rounded-lg flex items-center justify-center gap-2 transition-all active:scale-95 shadow-sm mb-2">
              <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
              Pôr no Carrinho
            </button>
            <a href="<?= whatsapp_link('Olá! Gostaria de comprar o produto: ' . $p['nome']) ?>" target="_blank"
               class="w-full bg-emerald-cta hover:brightness-110 text-white font-label-md text-sm py-2.5 rounded-lg flex items-center justify-center gap-2 transition-all active:scale-95 shadow-sm shadow-emerald-500/20">
              <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1;">chat</span>
              Pedir pelo Whats
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= asset('js/main.js') ?>"></script>
<script>
function addCart(id) {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const body = new URLSearchParams({ action: 'add', id, qty: 1, csrf_token: csrf });
    fetch('<?= SITE_URL ?>/ajax/cart_actions.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: body.toString()
    }).then(res => res.json()).then(data => {
        if(data.success) {
            alert('Produto adicionado ao carrinho!');
            // Atualiza badge do carrinho se existir
            document.querySelectorAll('[data-cart-count]').forEach(el => {
                el.textContent = data.count;
                el.style.display = data.count > 0 ? 'flex' : 'none';
            });
        } else {
            alert('Erro: ' + data.error);
        }
    }).catch(err => {
        console.error('Erro ao adicionar ao carrinho:', err);
        alert('Erro de conexão. Tente novamente.');
    });
}
</script>
</body>
</html>
