<?php
require_once __DIR__ . '/config.php';
$page_title  = 'Início';
$active_page = 'inicio';
require_once __DIR__ . '/includes/head.php';
?>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<main class="mt-1">

  <!-- ── Hero ─────────────────────────────────────────────────────────── -->
  <section class="relative h-[870px] flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
      <img alt="Cozinha comercial premium"
           class="w-full h-full object-cover"
           src="https://lh3.googleusercontent.com/aida-public/AB6AXuCCDhI3xfS3qZJfmSWsY2bCLEN0WnexusyMesTRkHArgFif_Na18sl4olVBDTeavuZ79vgN07dZqKL9TNdyh-tVr7h8bUP15VnTeofkw69dkF1aMa8rOON6glbum6b7i0ViTkyNONKWYFamLJ6ASDiTNk_bXYGdPWiziS1URtCZT1NCnyfTggH-iShYasUS1AwizJ0GUjwVqm-9bkdsDoYaIpcBZ7DTgxjb6o3yS1SGepVT3-WDtQMt9iGDrrbRMmppX7ZVfYqcsMwz">
      <div class="absolute inset-0 bg-gradient-to-r from-background via-background/60 to-transparent"></div>
    </div>
    <div class="container mx-auto px-gutter relative z-10 max-w-container-max">
      <div class="max-w-2xl">
        <span class="font-label-md text-label-md text-tertiary-fixed-dim uppercase tracking-widest mb-4 block">Desde 1996</span>
        <h1 class="font-headline-xl text-headline-xl text-primary mb-6">
          Tudo para sua casa, cozinha e negócio em um só lugar.
        </h1>
        <p class="font-body-lg text-body-lg text-secondary mb-10 leading-relaxed">
          Referência em Porto Velho, unimos tradição e modernidade para oferecer as melhores soluções em utilidades domésticas e equipamentos profissionais.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="<?= page('catalogo.php') ?>"
             class="bg-primary text-on-primary px-10 py-4 rounded-lg font-label-md text-label-md active:scale-95 transition-all premium-shadow">
            Ver Produtos
          </a>
          <a href="<?= whatsapp_link('Olá! Gostaria de falar com um atendente.') ?>" target="_blank"
             class="bg-emerald-cta text-white px-10 py-4 rounded-lg font-label-md text-label-md flex items-center gap-2 active:scale-95 transition-all premium-shadow">
            <span class="material-symbols-outlined">call</span>
            Falar com atendente
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Diferenciais ──────────────────────────────────────────────────── -->
  <section class="bg-surface-container-low py-16">
    <div class="container mx-auto px-gutter max-w-container-max">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-stack-lg">
        <?php
        $diferenciais = [
          ['icon'=>'inventory_2',  'titulo'=>'Variedade Incomparável', 'desc'=>'Milhares de itens em estoque.'],
          ['icon'=>'restaurant',   'titulo'=>'Expertise Culinária',    'desc'=>'Atendimento técnico especializado.'],
          ['icon'=>'location_on',  'titulo'=>'Porto Velho - RO',       'desc'=>'Localização privilegiada no centro.'],
          ['icon'=>'credit_card',  'titulo'=>'Facilidade no Pagamento','desc'=>'Parcelamento em até 10x sem juros.'],
        ];
        foreach ($diferenciais as $d): ?>
        <div class="flex items-start gap-4">
          <span class="material-symbols-outlined text-tertiary-fixed-dim text-headline-md"><?= $d['icon'] ?></span>
          <div>
            <h4 class="font-label-md text-label-md text-primary mb-1"><?= $d['titulo'] ?></h4>
            <p class="font-label-sm text-label-sm text-secondary"><?= $d['desc'] ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ── Departamentos (Bento Grid) ────────────────────────────────────── -->
  <section class="py-section-padding container mx-auto px-gutter max-w-container-max">
    <div class="text-center mb-16">
      <h2 class="font-headline-xl text-headline-xl text-primary mb-4">Nossos Departamentos</h2>
      <div class="w-20 h-1 bg-tertiary-fixed-dim mx-auto"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-12 md:grid-rows-2 gap-stack-md h-[800px] md:h-[600px]">

      <div class="md:col-span-6 md:row-span-2 relative group overflow-hidden rounded-xl">
        <img alt="Utilidades Domésticas" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
             src="https://lh3.googleusercontent.com/aida-public/AB6AXuBsFDIUl6lu-jPBFmDa7LAbl78Cb6M7mI-Yu1mfiZTKiO3ePJbgX7nnEb_MUO1FKs6hyLs4kNW8zfVDjhrcVy7T8keR4b1A2EvnVsyGGMQXkIKDnGkBjhKZjLz9WlDi7oZwrMeyePE4G31SQ1FRI3ZlvfIe3PTPTDw16ejrcOAM5DMliXYxwwJRhnrvne35WyBzAoZ4CeSS-dnZjw7wyiPD20Y0K7R4_-qkec5nH4JtUuaHJRW11v5FeJr-phiifuczSNl7jzSt-k6X">
        <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent flex flex-col justify-end p-8">
          <h3 class="text-white font-headline-md text-headline-md mb-2">Utilidades Domésticas</h3>
          <p class="text-white/80 font-body-md text-body-md">O essencial com um toque de sofisticação.</p>
        </div>
      </div>

      <div class="md:col-span-6 md:row-span-1 relative group overflow-hidden rounded-xl">
        <img alt="Bares e Restaurantes" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
             src="https://lh3.googleusercontent.com/aida-public/AB6AXuD_7X2iYzgNZwDWWMUECxfQLqbqsuUlGF5MXMgCNCvUiGXO-Zk4SHGhYUPW1oUaR-GtoLGHmAOJcLzVjiTBG9d7D6HUrF9q_NUbSRihX3yjkt-jjc_BAq9f2AWlBX00YDtxqTLvS-QwBU-bLbxVVGSb4-i3cMyP1hWU_dED5sFW_n8cx8JppzNsJ-7qvIvJhKght_q6GDR_fSm93inGl85LWByELxVlRKxJDbqfdAUFBI3Bfqj-nK_evoQ_KE7wICEOfGRGo3X1sbT5">
        <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent flex flex-col justify-end p-6">
          <h3 class="text-white font-headline-md text-headline-md mb-2">Bares e Restaurantes</h3>
          <p class="text-white/80 font-body-md text-body-md">Linha profissional completa.</p>
        </div>
      </div>

      <div class="md:col-span-3 md:row-span-1 relative group overflow-hidden rounded-xl">
        <img alt="Eletrodomésticos" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
             src="https://lh3.googleusercontent.com/aida-public/AB6AXuDvLOHIPvAgY_EJkaHQLcftuez0BSUfzHXH8cWKhREmFI8EomkUeravbMx0v9n0es9toVVPqbAowvRxYlA0EkKCUOAkwhOFTNCmkj7JHFhYMJ_NuzbOdmq4BUUDggUDRndYxcEjD1v9RJ4BYbaBz9XZCqeIJ10RefFQADob2VczzKEWvP1eqVKqSTHbV145F3LTaF3Akdy27CRb8M4gxZWSEaGbMnsBSd3ZEUEUZw0kSkgy_P8UgkE8W5ukV3n893xwVVmFUusJ0e4N">
        <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent flex flex-col justify-end p-6">
          <h3 class="text-white font-headline-md text-label-md">Eletrodomésticos</h3>
        </div>
      </div>

      <div class="md:col-span-3 md:row-span-1 relative group overflow-hidden rounded-xl">
        <img alt="Organização" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
             src="https://lh3.googleusercontent.com/aida-public/AB6AXuDJSeNfKr0pqDEIhRXsYnCGShlN5peofpfHk4SkuOTjEDMv545Zg-4N4AHb2Gv7Aw5mVDXFaWvfeK8LEIO6CJkckRgpbzS2P_Ri6CJQMKiVqStXUsLVHzhYENZBLrRPoc5wd25DERG5-I-Do1qxhG9T7-MfrRCuT-qhrfJAt5H-nthiyxclQT5ARk-1qVqNBFxMb12SWnDDVrU1uZAjIiClMFcNooybYCqBwKz6k1RFUK5c6xNwZ5AXzxvRxwmPzOaMP_l7TabYC">
        <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent flex flex-col justify-end p-6">
          <h3 class="text-white font-headline-md text-label-md">Organização</h3>
        </div>
      </div>

    </div>
  </section>

  <!-- ── Destaques ─────────────────────────────────────────────────────── -->
  <?php
  require_once __DIR__ . '/includes/db.php';
  // Buscar 4 produtos aleatórios ou os primeiros para destaque
  $stmt = $pdo->query("SELECT p.*, c.nome as cat FROM produtos p JOIN categorias c ON p.id_categoria = c.id LIMIT 4");
  $produtos_destaque = $stmt->fetchAll();
  ?>
  <section class="bg-surface-container py-section-padding">
    <div class="container mx-auto px-gutter max-w-container-max">
      <div class="flex justify-between items-end mb-12">
        <div>
          <h2 class="font-headline-xl text-headline-xl text-primary mb-2">Destaques da Toca</h2>
          <p class="font-body-md text-body-md text-secondary">Os favoritos dos nossos clientes e especialistas.</p>
        </div>
        <a class="text-primary font-bold border-b-2 border-tertiary-fixed-dim hover:opacity-70 transition-opacity"
           href="<?= page('catalogo.php') ?>">Ver todos os produtos</a>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php foreach ($produtos_destaque as $p): ?>
        <div class="bg-white p-6 rounded-xl group hover:shadow-2xl transition-all duration-300">
          <div class="relative mb-6 overflow-hidden aspect-square rounded-lg bg-surface-container-low">
            <img alt="<?= htmlspecialchars($p['nome']) ?>"
                 class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-500"
                 src="<?= esc(asset($p['img_url'])) ?>">
            <?php if ($p['badge']): ?>
            <span class="absolute top-3 left-3 bg-tertiary-fixed-dim text-on-tertiary-fixed px-3 py-1 rounded text-label-sm font-bold uppercase">
              <?= esc($p['badge']) ?>
            </span>
            <?php endif; ?>
          </div>
          <h4 class="font-headline-md text-body-lg text-primary mb-1"><?= htmlspecialchars($p['nome']) ?></h4>
          <p class="font-body-md text-label-sm text-secondary mb-4"><?= htmlspecialchars($p['cat']) ?></p>
          <div class="flex flex-col gap-2 mt-auto">
            <a href="<?= page('produto.php?id=' . $p['id']) ?>"
               class="w-full border-2 border-primary text-primary py-2.5 rounded-lg font-label-md text-sm flex items-center justify-center gap-2 hover:bg-primary/5 active:scale-95 transition-all">
              <span class="material-symbols-outlined text-[20px]">visibility</span>
              Detalhes
            </a>
            <a href="<?= whatsapp_link('Olá! Gostaria de comprar o produto: ' . $p['nome']) ?>" target="_blank"
               class="w-full bg-emerald-cta text-white py-2.5 rounded-lg font-label-md text-sm flex items-center justify-center gap-2 hover:brightness-110 active:scale-95 transition-all shadow-md shadow-emerald-500/20">
              <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1;">chat</span>
              Pedir pelo Whats
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ── Depoimentos ───────────────────────────────────────────────────── -->
  <section class="py-section-padding container mx-auto px-gutter max-w-container-max">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
      <div>
        <h2 class="font-headline-xl text-headline-xl text-primary mb-8">O que dizem nossos clientes</h2>
        <div class="space-y-8">
          <?php
          $depoimentos = [
            ['texto'=>'"A Toca do Coelho é parada obrigatória para quem ama cozinhar em Porto Velho. Encontro desde o item mais básico até equipamentos que só via em catálogos de fora."','nome'=>'Mariana Rocha','cargo'=>'Chef e Consultora Gastronômica','iniciais'=>'MR'],
            ['texto'=>'"Atendimento impecável. Comprei toda a montagem do meu novo restaurante e recebi um suporte técnico que fez toda a diferença na escolha dos fornos."','nome'=>'João Silva','cargo'=>'Proprietário do Bistrô Regional','iniciais'=>'JS'],
          ];
          foreach ($depoimentos as $dep): ?>
          <div class="bg-surface-container-low p-8 rounded-xl relative border-l-4 border-tertiary-fixed-dim">
            <p class="font-body-md text-body-lg text-primary italic mb-4"><?= $dep['texto'] ?></p>
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center font-bold text-primary">
                <?= $dep['iniciais'] ?>
              </div>
              <div>
                <span class="block font-label-md text-label-md text-primary"><?= $dep['nome'] ?></span>
                <span class="block font-label-sm text-label-sm text-secondary"><?= $dep['cargo'] ?></span>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Instagram grid -->
      <div>
        <div class="flex items-center gap-2 mb-6">
          <span class="material-symbols-outlined text-primary">photo_camera</span>
          <h3 class="font-headline-md text-headline-md text-primary">Siga @tocadocoelho</h3>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <?php
          $insta = [
            'https://lh3.googleusercontent.com/aida-public/AB6AXuDfS3YPan2rNrpPDEE6bptX3UBzFY_w4UsIXm5o_LvXzR6zvNz8O5gH96x4dwvMPzwLNga5y8MUw2aXtSUJMSf5xBd4yvcVeBl5Uc-Khi7BGWcVcMwBk1Ef3ad7IaK0yOPtw4PE_FipgI53n4tCbgox2IGCQLW9GU01i8LIVuNRhPpX7lXunmavyN-ktUcLLdOHdL3JbzaS5b1QxVkOTRGUMLUT0gLBwtCwDJC65fmSuIFdoL_xZ4sk2c3clOGg_cd9qNmiaDyOrk0c',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuAFMd6wkwPdMJqjBVRDg4KdPDqvUVtBa_Yblrop0aAs8GEDwYozqvfPYKyLF3cueBh6XliXOxur4ynsGd5UFPcX3Nq0smjGoFbUp77Weod82lUZuPOW9piAJQ3YQoqN5SlNW1jcx4VFe0fK_rdW_F6-keVzqSOYPe6ClW-78j5Ie2EaL7bF-CjnPOvpepadvtXsTK2fJnYrMBot24-9ZLpnLdt6uaiOBUvRgIC1nJaWohrdSyFDSnEATjoAcBQEltmn32aNdVEZeso7',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuD5CKhGzJhMeV-9nYMHxawhUzIyMdgjcKLf2GVrVlPveuN3KkH5gGUD68ASu3nwhLKjJk-s-Pilj-OZgL2Atm-7u6jxYexj41mCC8rWN5DE4KuLz8KLiMKMOy1KhTowPnz8uzoFrg0HTBJClL1WtrHxuiAJy5c_iaHI6t1Q4HGBHaDSrQFD2l9uFNK1mxjs8XqaWo1lKv6Xthwb8BYcrL5cLwWvpf3T7kQfy_76AZMnGqCA7ZMNNrRi6lYKULncSxdrBnxTUZw4-Tqr',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuCs2BFNA3pOVIfq8M56lp4zTveCflEQ2nGQKQ34tR0KlKEH_8nfYiwOayl0E9WzRA3hA9mQCFBS0QBuoE1sPJD5XhgaJFRjx0LDXVNPV4ENq4ngR6xtTujHwL-XRSVsaaHNEjAGzF6RsI2riBZ5-iKuvYoHT4HSHu-lQp2m12HdZixR7cRirDdDS2kj3U2G3Y_nq42HEh2pUTHdoMUj2PGut07lbjy8T322hB1j',
          ];
          foreach ($insta as $src): ?>
          <img alt="Instagram" class="aspect-square object-cover rounded-lg" src="<?= $src ?>">
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ── CTA Final ─────────────────────────────────────────────────────── -->
  <section class="bg-primary-container text-on-primary py-section-padding">
    <div class="container mx-auto px-gutter max-w-container-max text-center">
      <h2 class="font-headline-xl text-headline-xl text-white mb-6">Transforme sua cozinha hoje mesmo</h2>
      <p class="font-body-lg text-body-lg text-on-primary-container mb-12 max-w-2xl mx-auto">
        Visite nossa loja física no coração de Porto Velho ou fale agora com nossos especialistas via WhatsApp.
      </p>
      <div class="flex flex-col md:flex-row justify-center items-center gap-stack-lg mb-16">
        <a href="<?= whatsapp_link('Olá! Quero transformar minha cozinha. Pode me ajudar?') ?>" target="_blank"
           class="bg-emerald-cta text-white px-12 py-5 rounded-full font-label-md text-label-md flex items-center gap-3 active:scale-95 transition-all shadow-xl shadow-emerald-500/10">
          <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">chat</span>
          WhatsApp Concierge
        </a>
        <div class="text-left">
          <span class="block font-label-md text-label-md text-white mb-1">Nosso Endereço:</span>
          <address class="not-italic font-body-md text-on-primary-container">
            <?= CONTACT_ADDRESS ?>
          </address>
        </div>
      </div>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
