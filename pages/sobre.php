<?php
require_once __DIR__ . '/../config.php';
$page_title  = 'Nossa História';
$active_page = 'historia';
require_once __DIR__ . '/../includes/head.php';
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<main class="pt-16 pb-section-padding">

  <!-- Hero -->
  <section class="max-w-container-max mx-auto px-gutter mb-section-padding">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-stack-lg items-center">
      <div class="space-y-stack-md">
        <span class="font-label-md text-label-md text-tertiary-fixed-dim tracking-[0.2em] uppercase">Desde 1996 em Porto Velho</span>
        <h1 class="font-headline-xl text-headline-xl text-primary max-w-xl">
          Tradição que transforma casas em lares.
        </h1>
        <p class="font-body-lg text-body-lg text-secondary max-w-lg">
          Há quase três décadas, a Toca do Coelho é o destino de quem busca excelência em utilidades domésticas e o segredo dos melhores anfitriões de Rondônia.
        </p>
      </div>
      <div class="relative h-[500px] rounded-xl overflow-hidden shadow-xl shadow-primary/5">
        <img class="w-full h-full object-cover"
             src="https://lh3.googleusercontent.com/aida-public/AB6AXuBcedN5wJrfMMp9jksFTeKpM1NZahHfzVv50ijLij8Zm3gVPzPWQv-mApcolxNQ418S5uadfUhjkRGmvFi8vPH_Ep9m2AeFKJsPp3Z9O3kL_DVj8DBTD3nWhLgXd7iVDQKCtnTR6DasPzcrFZxPPXkMcTDyXCzONt1f-rqg76H4wnzzK9CLWJmwrUtveBUSg6RqOfgAOP4VNzd_sSj6wpny2EfM3FGCsY0Hcec9y5g-a04hGz-4SAhOx5xSJi5XJRLKVq8MA7XkKNW_"
             alt="Showroom Toca do Coelho">
      </div>
    </div>
  </section>

  <!-- Bento de números -->
  <section class="bg-surface-container-low py-section-padding">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white p-12 rounded-xl flex flex-col justify-center shadow-sm">
          <h2 class="font-headline-lg text-headline-lg text-primary mb-6">Nossa Origem</h2>
          <p class="font-body-md text-body-md text-secondary mb-4">
            Fundada em 1996, no coração de Porto Velho, a Toca do Coelho nasceu de um sonho simples: oferecer produtos que não apenas servissem à rotina, mas que celebrassem o prazer de estar em casa.
          </p>
          <p class="font-body-md text-body-md text-secondary">
            Ao longo dos anos, evoluímos de uma pequena loja local para uma referência regional em curadoria de utensílios, mantendo sempre o compromisso com a durabilidade e o design atemporal.
          </p>
        </div>
        <div class="bg-primary-container p-12 rounded-xl text-white flex flex-col justify-between">
          <span class="material-symbols-outlined text-4xl text-tertiary-fixed-dim">restaurant</span>
          <div>
            <h3 class="font-headline-md text-headline-md mb-2">Compromisso Regional</h3>
            <p class="font-body-md text-body-md opacity-80">Valorizamos a cultura de Rondônia e as necessidades de quem vive no Norte.</p>
          </div>
        </div>

        <?php
        $stats = [
          ['num'=>'28+',  'label'=>'Anos de História'],
          ['num'=>'15k+', 'label'=>'Itens Curados'],
          ['num'=>'100k+','label'=>'Clientes Atendidos'],
        ];
        foreach ($stats as $s): ?>
        <div class="bg-white p-8 rounded-xl border border-outline-variant/20 shadow-sm flex items-center justify-center text-center">
          <div>
            <h4 class="font-headline-xl text-headline-xl text-primary"><?= $s['num'] ?></h4>
            <p class="font-label-md text-label-md text-secondary uppercase tracking-widest"><?= $s['label'] ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Timeline -->
  <section class="max-w-container-max mx-auto px-gutter py-section-padding">
    <h2 class="font-headline-lg text-headline-lg text-primary text-center mb-16">Nossa Jornada</h2>
    <div class="relative">
      <div class="absolute left-1/2 -translate-x-1/2 h-full w-px bg-outline-variant"></div>
      <div class="space-y-24 relative">
        <?php
        $timeline = [
          ['ano'=>'1996','titulo'=>'O Primeiro Passo',   'desc'=>'Abertura da primeira unidade física em Porto Velho, focada em utilidades básicas de alta resistência.','img'=>'https://lh3.googleusercontent.com/aida-public/AB6AXuDb3sNxKFTPMjs4Yw2NBSHbu5wZ9KVTFDBzzVjPgVxH0QOjpRsL8kTgyWXj8nLvd1dV9DyKTpWNWJmnBTZ-pE6KmgBGBh2_pWkC9XyxRZDYSo0T7r4F0vc9pkUr1N6z0agOZ6H3mLFMZmRCFMTbibaY5Dv-1dy99Y_nrf3Gsx70RzfSJfgGVCISiPI0R0xJhdz_2zyO8EK8eQL7yYmImNMEvrRPwVEGaKlG-Yi867kslS2C7EVw4RqcweAncjX65uQmO_-tWcGWT09v','bg'=>'bg-primary','text'=>'text-white'],
          ['ano'=>'2010','titulo'=>'Expansão e Curadoria','desc'=>'Introdução de marcas internacionais de luxo e utensílios profissionais de gastronomia ao mercado regional.','img'=>'https://lh3.googleusercontent.com/aida-public/AB6AXuCU0s4EHgoUrA0p3kF8Lk7ER23-V7dqjHf-76dnEBrFc-7bYhD5mtuniZxdb61g4lsp7itFqc52c1Ah_Ih0V4jPetWEv7nNYsDra-oCo2D4EiVFe92SkQw0RHsg1nBNS_y3ZMYSKO4XyV7IcXjaDUmYF9DqMwH6ntScrOm32tEMhIn9eBvaN3yxLJrrM-C-mUhaTgWtuYsiqTpTvwY8Y6Fdx2PuXZpu3UsYq8jIpu4A3V2L9k1syJi3_AAPwdW-urkImiMXYMVPWecR','bg'=>'bg-tertiary-fixed-dim','text'=>'text-on-tertiary-fixed'],
          ['ano'=>'Hoje','titulo'=>'Digital e Design',    'desc'=>'Lançamento da plataforma digital Concierge, unindo o atendimento personalizado tradicional à conveniência moderna.','img'=>'https://lh3.googleusercontent.com/aida-public/AB6AXuCcrawuVUT_K-1bbSGeZkj1L4IFZ0Dh7bEjXuPkWfgnrD8vcLFqGpdZ-XFwxuiGPJ8VbmXj_ktX5ZyFm0E1_NyojnJqUjU1D_GFJDuW2hdlY7qa59QQGvDbo_wXqqa5Rlr74igKaE948eF0PMZofRlIki_qmK3u06EVtSlcR8-oSv2dhB2pW3QPtKnXe-T-YeyS4CdQp7J95EjbwjoRdfMGJsbi6y373fJNqElHmIVBLI-UiXQ-lmP6yVRHKoweM2SEAUThvlNvOAzY','bg'=>'bg-primary','text'=>'text-white'],
        ];
        foreach ($timeline as $i => $ev):
          $reverse = $i % 2 !== 0;
        ?>
        <div class="flex flex-col <?= $reverse ? 'md:flex-row-reverse' : 'md:flex-row' ?> items-center justify-between w-full">
          <div class="w-full md:w-[45%] <?= $reverse ? 'text-left' : 'text-right' ?> hidden md:block">
            <h3 class="font-headline-md text-headline-md text-primary"><?= $ev['titulo'] ?></h3>
            <p class="font-body-md text-body-md text-secondary"><?= $ev['desc'] ?></p>
          </div>
          <div class="w-10 h-10 rounded-full <?= $ev['bg'] ?> flex items-center justify-center z-10 border-4 border-background">
            <span class="<?= $ev['text'] ?> font-bold text-xs"><?= $ev['ano'] ?></span>
          </div>
          <div class="w-full md:w-[45%] h-64 rounded-xl overflow-hidden shadow-md">
            <img class="w-full h-full object-cover" src="<?= $ev['img'] ?>" alt="<?= $ev['titulo'] ?>">
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Pilares -->
  <section class="bg-primary-container text-white py-section-padding">
    <div class="max-w-container-max mx-auto px-gutter text-center">
      <h2 class="font-headline-lg text-headline-lg mb-16">Nossos Pilares</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-stack-lg">
        <?php
        $pilares = [
          ['icon'=>'star',        'titulo'=>'Qualidade',   'desc'=>'Selecionamos produtos que resistem ao tempo e ao uso intenso da cozinha brasileira.'],
          ['icon'=>'handshake',   'titulo'=>'Confiança',   'desc'=>'Construímos relações duradouras baseadas na transparência e no respeito ao cliente.'],
          ['icon'=>'diversity_3', 'titulo'=>'Comunidade',  'desc'=>'Temos orgulho de ser parte da história de milhares de famílias em Porto Velho.'],
        ];
        foreach ($pilares as $p): ?>
        <div class="space-y-4 p-8 border border-outline-variant/10 rounded-xl bg-white/5 backdrop-blur-sm">
          <span class="material-symbols-outlined text-4xl text-tertiary-fixed-dim"><?= $p['icon'] ?></span>
          <h3 class="font-headline-md text-headline-md"><?= $p['titulo'] ?></h3>
          <p class="font-body-md text-body-md opacity-70"><?= $p['desc'] ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
