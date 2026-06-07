<?php
require_once __DIR__ . '/includes/check_admin.php';

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';
$msg = '';
$msgError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $postAction = $_POST['action'] ?? $action;

    if ($postAction === 'add' || $postAction === 'edit') {
        $nome = trim($_POST['nome'] ?? '');
        $id_categoria = (int) ($_POST['id_categoria'] ?? 0);
        $preco = (float) ($_POST['preco'] ?? 0);
        $preco_promo = !empty($_POST['preco_promocional']) ? (float) $_POST['preco_promocional'] : null;
        $badge = trim($_POST['badge'] ?? '') ?: null;
        $descricao = trim($_POST['descricao'] ?? '');
        $img_url = validar_img_url($_POST['img_url'] ?? '');

        if ($img_url === null) {
            $msgError = 'URL da imagem inválida. Use apenas caminhos como img/produtos/nome.jpg';
            $action = $postAction;
        } elseif ($nome === '' || $descricao === '' || $id_categoria <= 0 || $preco <= 0) {
            $msgError = 'Preencha todos os campos obrigatórios corretamente.';
            $action = $postAction;
        } else {
            if ($preco_promo && !$badge) {
                $desconto = round((1 - $preco_promo / $preco) * 100);
                $badge = "-{$desconto}%";
            }

            if ($postAction === 'add') {
                $stmt = $pdo->prepare("INSERT INTO produtos (id_categoria, nome, descricao, preco, preco_promocional, badge, img_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$id_categoria, $nome, $descricao, $preco, $preco_promo, $badge, $img_url]);
                $msg = 'Produto adicionado com sucesso!';
                $action = 'list';
            } else {
                $id = (int) ($_POST['id'] ?? 0);
                $stmt = $pdo->prepare("UPDATE produtos SET id_categoria=?, nome=?, descricao=?, preco=?, preco_promocional=?, badge=?, img_url=? WHERE id=?");
                $stmt->execute([$id_categoria, $nome, $descricao, $preco, $preco_promo, $badge, $img_url, $id]);
                $msg = 'Produto atualizado com sucesso!';
                $action = 'list';
            }
        }
    } elseif ($postAction === 'remove_promo') {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = $pdo->prepare("UPDATE produtos SET preco_promocional = NULL, badge = NULL WHERE id = ?");
        $stmt->execute([$id]);
        $msg = 'Promoção removida do produto!';
        $action = 'list';
    } elseif ($postAction === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id=?");
        $stmt->execute([$id]);
        $msg = 'Produto excluído!';
        $action = 'list';
    }
}

$page_title = 'Produtos - Admin';
require_once __DIR__ . '/../includes/head.php';

$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome")->fetchAll();
?>

<div class="flex h-screen bg-surface-container-low">
  <!-- Sidebar -->
  <aside class="w-64 bg-primary-container text-on-primary-container flex flex-col">
    <div class="p-6">
      <h2 class="font-headline-md text-white font-bold">Admin Toca</h2>
    </div>
    <nav class="flex-1 px-4 space-y-2">
      <a href="index.php" class="block py-3 px-4 rounded-lg hover:bg-primary/10 transition-colors">Dashboard</a>
      <a href="produtos.php" class="block py-3 px-4 rounded-lg bg-primary/20 text-white font-bold">Produtos</a>
      <a href="pedidos.php" class="block py-3 px-4 rounded-lg hover:bg-primary/10 transition-colors">Pedidos</a>
      <a href="clientes.php" class="block py-3 px-4 rounded-lg hover:bg-primary/10 transition-colors">Clientes</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-8 overflow-y-auto">
    <header class="flex justify-between items-center mb-10">
      <h1 class="font-headline-xl text-primary">Gerenciar Produtos</h1>
      <?php if($action === 'list'): ?>
      <a href="?action=add" class="bg-primary text-on-primary px-6 py-2 rounded-full font-label-md">Novo Produto</a>
      <?php endif; ?>
    </header>

    <?php if($msg): ?>
    <div class="bg-emerald-100 text-emerald-800 p-4 rounded-lg mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">check_circle</span>
        <?= esc($msg) ?>
    </div>
    <?php endif; ?>
    <?php if($msgError): ?>
    <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">error</span>
        <?= esc($msgError) ?>
    </div>
    <?php endif; ?>

    <?php if($action === 'list'): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container-lowest border-b border-outline-variant/30">
                    <tr>
                        <th class="p-4 font-label-md text-secondary">ID</th>
                        <th class="p-4 font-label-md text-secondary">Nome</th>
                        <th class="p-4 font-label-md text-secondary">Categoria</th>
                        <th class="p-4 font-label-md text-secondary">Preço Normal</th>
                        <th class="p-4 font-label-md text-secondary">Preço Promocional</th>
                        <th class="p-4 font-label-md text-secondary">Status</th>
                        <th class="p-4 font-label-md text-secondary">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $produtos = $pdo->query("SELECT p.*, c.nome as cat FROM produtos p JOIN categorias c ON p.id_categoria = c.id ORDER BY p.id DESC")->fetchAll();
                    foreach($produtos as $p):
                        $em_promo = !empty($p['preco_promocional']);
                        $desconto_pct = $em_promo ? round((1 - $p['preco_promocional'] / $p['preco']) * 100) : 0;
                    ?>
                    <tr class="border-b border-outline-variant/10 hover:bg-surface-container-lowest/50">
                        <td class="p-4 text-sm"><?= $p['id'] ?></td>
                        <td class="p-4 text-sm font-bold text-primary">
                            <?= htmlspecialchars($p['nome']) ?>
                            <?php if($p['badge']): ?>
                                <span class="ml-2 inline-block bg-error/10 text-error text-[10px] font-bold px-2 py-0.5 rounded-full uppercase"><?= htmlspecialchars($p['badge']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-sm"><?= htmlspecialchars($p['cat']) ?></td>
                        <td class="p-4 text-sm <?= $em_promo ? 'line-through text-outline' : 'font-semibold' ?>">
                            R$ <?= number_format($p['preco'], 2, ',', '.') ?>
                        </td>
                        <td class="p-4 text-sm">
                            <?php if($em_promo): ?>
                                <span class="font-bold text-emerald-700">R$ <?= number_format($p['preco_promocional'], 2, ',', '.') ?></span>
                                <span class="ml-1 text-[10px] font-bold text-error bg-error/10 px-1.5 py-0.5 rounded">-<?= $desconto_pct ?>%</span>
                            <?php else: ?>
                                <span class="text-outline italic text-xs">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4">
                            <?php if($em_promo): ?>
                                <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 text-[11px] font-bold px-2.5 py-1 rounded-full">
                                    <span class="material-symbols-outlined text-[14px]">sell</span>
                                    Em Promoção
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 bg-surface-container text-secondary text-[11px] font-bold px-2.5 py-1 rounded-full">
                                    Preço Normal
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="?action=edit&id=<?= $p['id'] ?>" class="text-primary hover:underline" title="Editar">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <?php if($em_promo): ?>
                                <form method="POST" class="inline" onsubmit="return confirm('Remover a promoção deste produto?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="remove_promo">
                                    <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                                    <button type="submit" class="text-orange-600 hover:underline" title="Remover Promoção">
                                        <span class="material-symbols-outlined text-[18px]">money_off</span>
                                    </button>
                                </form>
                                <?php endif; ?>
                                <form method="POST" class="inline" onsubmit="return confirm('Excluir produto?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                                    <button type="submit" class="text-error hover:underline" title="Excluir">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php elseif($action === 'add' || $action === 'edit'): 
        $prod = null;
        if($action === 'edit') {
            $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
            $stmt->execute([(int) ($_GET['id'] ?? 0)]);
            $prod = $stmt->fetch();
        }
    ?>
        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 p-8 max-w-2xl">
            <form method="POST" action="?action=<?= esc($action) ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="<?= esc($action) ?>">
                <?php if($prod): ?>
                    <input type="hidden" name="id" value="<?= (int) $prod['id'] ?>">
                <?php endif; ?>
                
                <div class="space-y-6">
                    <div>
                        <label class="block font-label-md text-secondary mb-2">Nome</label>
                        <input type="text" name="nome" required value="<?= esc($prod['nome'] ?? '') ?>" class="w-full border rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block font-label-md text-secondary mb-2">Categoria</label>
                        <select name="id_categoria" required class="w-full border rounded-lg px-4 py-2">
                            <?php foreach($categorias as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= ($prod['id_categoria']??'') == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- ═══ Seção de Preços ═══ -->
                    <div class="border border-outline-variant/30 rounded-xl p-5 bg-surface-container-low/50">
                        <h3 class="font-label-md text-primary mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">payments</span>
                            Preços e Promoção
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-label-md text-secondary mb-2">Preço Normal (R$) *</label>
                                <input type="number" step="0.01" min="0.01" name="preco" required 
                                       value="<?= $prod['preco'] ?? '' ?>" 
                                       id="input-preco"
                                       class="w-full border rounded-lg px-4 py-2 font-semibold text-primary">
                            </div>
                            <div>
                                <label class="block font-label-md text-secondary mb-2 flex items-center gap-1">
                                    Preço Promocional (R$)
                                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-bold">OPCIONAL</span>
                                </label>
                                <input type="number" step="0.01" min="0" name="preco_promocional" 
                                       value="<?= $prod['preco_promocional'] ?? '' ?>" 
                                       id="input-preco-promo"
                                       placeholder="Deixe vazio se não há promoção"
                                       class="w-full border rounded-lg px-4 py-2 text-emerald-700 font-semibold">
                            </div>
                        </div>
                        <!-- Preview de desconto -->
                        <div id="promo-preview" class="mt-3 hidden">
                            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-2">
                                <span class="material-symbols-outlined text-emerald-600 text-[18px]">sell</span>
                                <span class="text-sm text-emerald-800">
                                    Desconto de <strong id="promo-pct">0</strong>% — 
                                    De <span class="line-through text-outline" id="promo-de">R$ 0,00</span> 
                                    por <strong class="text-emerald-700" id="promo-por">R$ 0,00</strong>
                                </span>
                            </div>
                        </div>
                        <p class="text-[11px] text-secondary mt-2 flex items-start gap-1">
                            <span class="material-symbols-outlined text-[14px] mt-px">info</span>
                            Se definir preço promocional sem badge, um badge com a % de desconto será criado automaticamente.
                        </p>
                    </div>

                    <div>
                        <label class="block font-label-md text-secondary mb-2">Badge (ex: OFERTA, NOVO, -20%)</label>
                        <input type="text" name="badge" value="<?= esc($prod['badge'] ?? '') ?>" class="w-full border rounded-lg px-4 py-2" placeholder="Opcional — automático se houver promoção">
                    </div>
                    <div>
                        <label class="block font-label-md text-secondary mb-2">URL da Imagem</label>
                        <input type="text" name="img_url" value="<?= esc($prod['img_url'] ?? 'img/produtos/placeholder.jpg') ?>" class="w-full border rounded-lg px-4 py-2">
                        <p class="text-xs text-secondary mt-1">Ex: img/produtos/prod_123.jpg</p>
                    </div>
                    <div>
                        <label class="block font-label-md text-secondary mb-2">Descrição</label>
                        <textarea name="descricao" rows="4" required class="w-full border rounded-lg px-4 py-2"><?= esc($prod['descricao'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="flex justify-end gap-4 pt-2">
                        <a href="produtos.php" class="px-6 py-2 rounded-full font-label-md text-secondary hover:bg-surface-container-low">Cancelar</a>
                        <button type="submit" class="bg-primary text-on-primary px-6 py-2 rounded-full font-label-md shadow-md hover:bg-primary/90">
                            Salvar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Script para preview de desconto em tempo real -->
        <script>
        (function() {
            const precoInput = document.getElementById('input-preco');
            const promoInput = document.getElementById('input-preco-promo');
            const preview = document.getElementById('promo-preview');
            const pctEl = document.getElementById('promo-pct');
            const deEl = document.getElementById('promo-de');
            const porEl = document.getElementById('promo-por');

            function formatBRL(v) {
                return 'R$ ' + parseFloat(v).toFixed(2).replace('.', ',');
            }

            function updatePreview() {
                const preco = parseFloat(precoInput.value);
                const promo = parseFloat(promoInput.value);

                if (preco > 0 && promo > 0 && promo < preco) {
                    const pct = Math.round((1 - promo / preco) * 100);
                    pctEl.textContent = pct;
                    deEl.textContent = formatBRL(preco);
                    porEl.textContent = formatBRL(promo);
                    preview.classList.remove('hidden');
                } else {
                    preview.classList.add('hidden');
                }
            }

            if (precoInput && promoInput) {
                precoInput.addEventListener('input', updatePreview);
                promoInput.addEventListener('input', updatePreview);
                // Executar no load para edição
                updatePreview();
            }
        })();
        </script>

    <?php endif; ?>

  </main>
</div>
</body>
</html>
