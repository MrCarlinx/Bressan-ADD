<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT p.*, c.nome as cat FROM produtos p JOIN categorias c ON p.id_categoria = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch();

if (!$produto) {
    header('Location: ' . page('catalogo.php'));
    exit;
}

$page_title  = $produto['nome'];
$active_page = 'catalogo';
require_once __DIR__ . '/../includes/head.php';

// Produtos Relacionados
$stmt = $pdo->prepare("SELECT p.*, c.nome as cat FROM produtos p JOIN categorias c ON p.id_categoria = c.id WHERE p.id_categoria = ? AND p.id != ? LIMIT 4");
$stmt->execute([$produto['id_categoria'], $id]);
$relacionados = $stmt->fetchAll();
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<main class="max-w-container-max mx-auto px-gutter pt-16 pb-section-padding">
  
  <div class="flex flex-col md:flex-row gap-16 mb-section-padding">
    <!-- Imagem -->
    <div class="w-full md:w-1/2">
      <div class="bg-surface-container-lowest rounded-2xl overflow-hidden aspect-square border border-outline-variant/30 flex items-center justify-center relative p-8">
        <?php if ($produto['badge']): ?>
        <span class="absolute top-6 left-6 z-10 bg-error text-white font-label-md text-label-md px-4 py-1.5 rounded-full">
          <?= htmlspecialchars($produto['badge']) ?>
        </span>
        <?php endif; ?>
        <img src="<?= esc(asset($produto['img_url'])) ?>" alt="<?= esc($produto['nome']) ?>" class="w-full h-full object-contain mix-blend-multiply">
      </div>
    </div>
    
    <!-- Detalhes -->
    <div class="w-full md:w-1/2 flex flex-col justify-center">
      <span class="font-label-md text-label-md text-tertiary-fixed-dim bg-tertiary-fixed-dim/10 px-3 py-1.5 rounded-lg mb-4 self-start uppercase tracking-wider">
        <?= htmlspecialchars($produto['cat']) ?>
      </span>
      <h1 class="font-headline-xl text-headline-xl text-primary mb-4 leading-tight">
        <?= htmlspecialchars($produto['nome']) ?>
      </h1>
      
      <div class="mb-8">
        <?php if($produto['preco_promocional']): ?>
          <div class="flex items-end gap-3">
            <span class="font-headline-lg text-headline-lg text-error">R$ <?= number_format($produto['preco_promocional'], 2, ',', '.') ?></span>
            <span class="font-body-lg text-outline line-through mb-1">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
          </div>
        <?php else: ?>
          <span class="font-headline-lg text-headline-lg text-primary">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
        <?php endif; ?>
      </div>
      
      <p class="font-body-lg text-body-lg text-secondary mb-10 leading-relaxed border-t border-outline-variant/30 pt-8">
        <?= nl2br(htmlspecialchars($produto['descricao'])) ?>
      </p>
      
      <div class="flex flex-col sm:flex-row gap-4">
        <button onclick="addCart(<?= $produto['id'] ?>)" class="flex-1 bg-primary hover:opacity-90 text-on-primary py-4 rounded-full font-label-md text-label-md flex items-center justify-center gap-3 transition-transform active:scale-95 shadow-xl">
          <span class="material-symbols-outlined text-[24px]">add_shopping_cart</span>
          Pôr no Carrinho
        </button>
        <a href="<?= whatsapp_link('Olá! Gostaria de comprar o produto: ' . $produto['nome']) ?>" target="_blank" class="flex-1 bg-emerald-cta hover:brightness-110 text-white py-4 rounded-full font-label-md text-label-md flex items-center justify-center gap-3 transition-transform active:scale-95 shadow-xl shadow-emerald-500/20">
          <span class="material-symbols-outlined text-[24px]" style="font-variation-settings:'FILL' 1;">chat</span>
          Pedir pelo Whats
        </a>
      </div>
    </div>
  </div>

  <!-- Produtos Relacionados -->
  <?php if (count($relacionados) > 0): ?>
  <div class="border-t border-outline-variant/30 pt-section-padding">
    <h2 class="font-headline-lg text-headline-lg text-primary mb-10 text-center">Produtos Relacionados</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
      <?php foreach ($relacionados as $p): ?>
      <div class="group card-hover bg-surface-container-lowest rounded-xl overflow-hidden custom-shadow">
        <div class="aspect-square bg-surface-container-low overflow-hidden relative p-4">
          <img alt="<?= htmlspecialchars($p['nome']) ?>"
               class="w-full h-full object-contain mix-blend-multiply transition-transform duration-500 group-hover:scale-105"
               src="<?= esc(asset($p['img_url'])) ?>">
        </div>
        <div class="p-6">
          <h4 class="font-headline-md text-body-lg text-primary mb-1 truncate"><?= htmlspecialchars($p['nome']) ?></h4>
          <p class="font-body-md text-label-md text-on-surface-variant font-bold mb-4">
            R$ <?= number_format($p['preco_promocional'] ?: $p['preco'], 2, ',', '.') ?>
          </p>
          <a href="<?= page('produto.php?id=' . $p['id']) ?>"
             class="w-full bg-surface-container-highest hover:bg-surface-dim text-on-surface font-label-sm text-label-sm py-2.5 rounded-lg flex items-center justify-center gap-2 transition-all active:scale-95">
            Ver Detalhes
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= asset('js/main.js') ?>"></script>
<script>
function addCart(id) {
    fetch('<?= SITE_URL ?>/ajax/cart_actions.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=add&id=${id}&qty=1`
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
