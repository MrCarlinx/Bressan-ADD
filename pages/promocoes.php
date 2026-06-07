<?php
require_once __DIR__ . '/../config.php';
$page_title  = 'Promoções';
$active_page = 'promocoes';
require_once __DIR__ . '/../includes/db.php';

$stmt = $pdo->prepare("SELECT p.*, c.nome as cat FROM produtos p JOIN categorias c ON p.id_categoria = c.id WHERE p.preco_promocional IS NOT NULL ORDER BY p.nome");
$stmt->execute();
$produtos_promocao = $stmt->fetchAll();

require_once __DIR__ . '/../includes/head.php';
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<main class="pt-1">
  <section class="relative w-full overflow-hidden py-24 bg-primary-container text-on-primary-container">
    <div class="absolute inset-0 opacity-10">
      <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCYDJLNlW6Css-y_3spG5MSei-0wCKrGiyrOJmxk-gdvL61Myz6jQ2i3IC6JtOcDm-UksWkfofd9VXPugFvGiBJUDSt0b438uJa8nyIhsFczSJnekqFk4bt7bQkR08Uc5fao2_RXoQabNWRKb2s5Rns8QLROOnjIjFs4WzfBUqia4Wgs866iJa9QxOGPOMSB4uEHuWHI9GQfmv59jDFVFLcoQgFI1R58IcH3gjoFQ3T5C8Ji6m0MtUQTI45mi9Qb4GCHCFpbKgsIsKS" alt="Cozinha profissional em promoção">
    </div>
    <div class="relative z-10 max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop flex flex-col md:flex-row justify-between items-center gap-12">
      <div class="max-w-2xl">
        <span class="inline-block bg-tertiary text-on-tertiary px-4 py-1 rounded-full font-label-caps text-label-caps mb-6">OFERTAS ESPECIAIS</span>
        <h1 class="font-headline-lg text-headline-lg mb-6 leading-tight text-white">Promoções exclusivas para equipar sua cozinha profissional</h1>
        <p class="font-body-lg text-body-lg text-white/90 mb-8">Aproveite descontos em equipamentos, utensílios e soluções completas com condições especiais e atendimento rápido via WhatsApp.</p>
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
          <a class="bg-tertiary text-on-tertiary px-8 py-4 rounded-lg font-headline-sm text-headline-sm transition-all hover:scale-105 active:scale-95 shadow-lg" href="<?= page('catalogo.php') ?>" role="button" style="text-decoration: none">Ver Catálogo</a>
          <a class="border-2 border-white text-white px-8 py-4 rounded-lg font-headline-sm text-headline-sm transition-all hover:bg-white/10 active:scale-95" href="<?= page('contato.php') ?>" role="button" style="text-decoration: none">Fale Conosco</a>
        </div>
      </div>
      <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-8 rounded-xl text-center min-w-[300px]">
        <p class="font-label-caps text-label-caps mb-4 uppercase tracking-widest text-tertiary-fixed">Termina em:</p>
        <div class="flex justify-center gap-4 mb-4 text-white">
          <div class="flex flex-col">
            <span class="font-headline-md text-headline-md font-bold">48</span>
            <span class="text-[10px] uppercase opacity-70">Horas</span>
          </div>
          <span class="font-headline-md text-headline-md">:</span>
          <div class="flex flex-col">
            <span class="font-headline-md text-headline-md font-bold">15</span>
            <span class="text-[10px] uppercase opacity-70">Min</span>
          </div>
          <span class="font-headline-md text-headline-md">:</span>
          <div class="flex flex-col">
            <span class="font-headline-md text-headline-md font-bold">32</span>
            <span class="text-[10px] uppercase opacity-70">Seg</span>
          </div>
        </div>
        <div class="w-full bg-white/20 h-1.5 rounded-full overflow-hidden">
          <div class="bg-tertiary-fixed h-full w-2/3"></div>
        </div>
        <p class="text-[12px] mt-4 text-white/70 italic font-body-sm">Estoques limitados. Consulte valores e disponibilidade no WhatsApp.</p>
      </div>
    </div>
  </section>

  <section class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-24">
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-6 mb-12">
      <div>
        <h2 class="font-headline-md text-headline-md text-primary mb-2">Destaques em Promoção</h2>
        <p class="font-body-md text-body-md text-secondary">Seleção atualizada automaticamente com todos os produtos que possuem preço promocional.</p>
      </div>
      <a href="<?= page('catalogo.php?oferta=1') ?>" class="text-primary font-bold border-b-2 border-tertiary-fixed-dim hover:opacity-70 transition-opacity">Ver apenas promoções</a>
    </div>

    <?php if (count($produtos_promocao) === 0): ?>
      <div class="bg-surface-container-low rounded-xl p-12 text-center border border-outline-variant/20">
        <h3 class="font-headline-sm text-headline-sm text-primary mb-4">Nenhuma promoção disponível no momento</h3>
        <p class="font-body-md text-body-md text-secondary">Volte em breve para conferir novas ofertas ou entre em contato para receber sugestões personalizadas.</p>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-gutter">
        <?php foreach ($produtos_promocao as $p): ?>
        <div class="bg-surface-container-lowest rounded-xl overflow-hidden border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow group">
          <div class="relative h-72 overflow-hidden">
            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="<?= esc(asset($p['img_url'])) ?>" alt="<?= esc($p['nome']) ?>">
            <?php if ($p['preco_promocional']): ?>
            <span class="absolute top-4 left-4 bg-error text-on-error px-3 py-1 rounded-full font-label-caps text-label-caps">Promoção</span>
            <?php endif; ?>
          </div>
          <div class="p-6">
            <p class="font-label-caps text-label-caps text-outline mb-2 uppercase"><?= htmlspecialchars($p['cat']) ?></p>
            <h4 class="font-headline-sm text-headline-sm text-on-surface mb-4 leading-snug"><?= htmlspecialchars($p['nome']) ?></h4>
            <div class="flex items-baseline gap-2 mb-6">
              <?php if ($p['preco_promocional']): ?>
                <span class="font-body-sm text-body-sm text-outline line-through">R$ <?= number_format($p['preco'], 2, ',', '.') ?></span>
                <span class="font-headline-sm text-headline-sm text-primary">R$ <?= number_format($p['preco_promocional'], 2, ',', '.') ?></span>
              <?php else: ?>
                <span class="font-headline-sm text-headline-sm text-primary">R$ <?= number_format($p['preco'], 2, ',', '.') ?></span>
              <?php endif; ?>
            </div>
            <div class="flex flex-col gap-3">
              <a href="<?= page('produto.php?id=' . $p['id']) ?>" class="w-full bg-primary text-on-primary py-3 rounded-lg flex items-center justify-center gap-2 font-label-caps text-label-caps hover:brightness-110 transition-all">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1;">visibility</span>
                Ver Detalhes
              </a>
              <a href="<?= whatsapp_link('Olá! Gostaria de saber mais sobre o produto: ' . $p['nome']) ?>" target="_blank" class="w-full border-2 border-outline-variant/30 text-on-surface py-3 rounded-lg flex items-center justify-center gap-2 font-label-caps text-label-caps hover:bg-surface-container transition-all">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1;">chat</span>
                Consultar no WhatsApp
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <section class="bg-surface-container-low py-24">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
      <div class="relative">
        <div class="aspect-square rounded-2xl overflow-hidden shadow-xl border-8 border-surface-container-lowest">
          <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC2fcFdztEjOYk_feUqwaeuHKLxltEtAhzpATN3fYczxeZgGbRGgI1FUNobA5aLYFA-fVBo6cx7RITo4sfxabUj-OSSDRIwIY1X0x11cJ-5jo3blfEBbN531xq473a-DfVcnOwY9kv4IdNu8YnrJgrAGTQxKMnN_1_MJbfDkXV5RlU6qfDVc30xJpdhEPm2LhMIntovGZnJw_Pms3s3BmvuehYFK59lRSTUs7urjRZ5UQ0XGM_0tV1i3k6zDDXkryML0D1FpeiTg-wd" alt="Estoque de produtos promocionais">
        </div>
        <div class="absolute -bottom-6 -right-6 bg-secondary-container text-on-secondary-container p-8 rounded-lg shadow-lg max-w-[240px]">
          <span class="material-symbols-outlined text-4xl mb-4">info</span>
          <h5 class="font-headline-sm text-[20px] mb-2 leading-tight">Como funcionam as ofertas</h5>
        </div>
      </div>
      <div>
        <h2 class="font-headline-md text-headline-md text-on-surface mb-8">Compromisso com transparência e qualidade</h2>
        <div class="space-y-8">
          <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="shrink-0 w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold">1</div>
            <div>
              <h6 class="font-body-lg font-bold text-on-surface mb-2">Estoque limitado</h6>
              <p class="font-body-md text-on-surface-variant">Nossas promoções valem enquanto houver estoque. A reserva só é confirmada após o contato via WhatsApp.</p>
            </div>
          </div>
          <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="shrink-0 w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold">2</div>
            <div>
              <h6 class="font-body-lg font-bold text-on-surface mb-2">Preços atualizados</h6>
              <p class="font-body-md text-on-surface-variant">Os valores exibidos são para pagamento à vista e podem mudar sem aviso prévio. Consulte condições de parcelamento.</p>
            </div>
          </div>
          <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="shrink-0 w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold">3</div>
            <div>
              <h6 class="font-body-lg font-bold text-on-surface mb-2">Garantia Toca do Coelho</h6>
              <p class="font-body-md text-on-surface-variant">Mesmo em oferta, todos os produtos mantêm a garantia do fabricante e o suporte técnico da nossa equipe.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
