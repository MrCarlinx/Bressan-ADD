<?php
require_once __DIR__ . '/../config.php';
$page_title  = 'Contato';
$active_page = 'contato';

// ── Processamento do formulário ────────────────────────────────────────
$msg_sucesso = '';
$msg_erro    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $nome    = trim(htmlspecialchars($_POST['nome']    ?? ''));
    $email   = trim(htmlspecialchars($_POST['email']   ?? ''));
    $assunto = trim(htmlspecialchars($_POST['assunto'] ?? ''));
    $mensagem= trim(htmlspecialchars($_POST['mensagem']?? ''));

    if ($nome && filter_var($email, FILTER_VALIDATE_EMAIL) && $assunto && $mensagem) {
        $headers  = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";
        $body     = "Nome: $nome\nE-mail: $email\nAssunto: $assunto\n\n$mensagem";
        // mail(CONTACT_EMAIL, "[$nome] $assunto", $body, $headers);
        // Comentado acima: ative quando tiver servidor com mail() configurado.
        $msg_sucesso = 'Mensagem enviada com sucesso! Entraremos em contato em breve.';
    } else {
        $msg_erro = 'Por favor, preencha todos os campos corretamente.';
    }
}

require_once __DIR__ . '/../includes/head.php';
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<main class="pt-16 pb-section-padding">
  <div class="max-w-container-max mx-auto px-gutter">

    <header class="mb-stack-lg">
      <h1 class="font-headline-xl text-headline-xl text-primary mb-4">Fale Conosco</h1>
      <p class="font-body-lg text-body-lg text-secondary max-w-2xl">
        Desde 1996, cuidamos de cada detalhe para sua casa. Entre em contato para dúvidas sobre produtos, encomendas especiais ou suporte.
      </p>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-stack-lg items-start">

      <!-- ── Informações ──────────────────────────────────────────────── -->
      <div class="lg:col-span-5 flex flex-col gap-stack-md">
        <div class="bg-white p-stack-lg rounded-xl shadow-sm">
          <h2 class="font-headline-md text-headline-md mb-stack-md">Canais de Atendimento</h2>
          <div class="flex flex-col gap-stack-md">
            <?php
            $canais = [
              ['icon'=>'call',       'label'=>'Telefone', 'valor'=> CONTACT_PHONE],
              ['icon'=>'mail',       'label'=>'E-mail',   'valor'=> CONTACT_EMAIL],
              ['icon'=>'location_on','label'=>'Endereço', 'valor'=> CONTACT_ADDRESS],
            ];
            foreach ($canais as $c): ?>
            <div class="flex items-start gap-stack-sm">
              <span class="material-symbols-outlined text-tertiary-fixed-dim"><?= $c['icon'] ?></span>
              <div>
                <p class="font-label-md text-label-md text-secondary"><?= $c['label'] ?></p>
                <p class="font-body-md text-body-md"><?= $c['valor'] ?></p>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="bg-surface-container-low p-stack-lg rounded-xl">
          <h3 class="font-label-md text-label-md text-primary mb-stack-md uppercase tracking-wider">Horário de Funcionamento</h3>
          <ul class="space-y-2 font-body-md text-body-md text-on-surface-variant">
            <li class="flex justify-between border-b border-outline-variant/30 pb-1"><span>Segunda à Sexta</span><span class="font-medium">09h às 18h</span></li>
            <li class="flex justify-between border-b border-outline-variant/30 pb-1"><span>Sábado</span><span class="font-medium">09h às 14h</span></li>
            <li class="flex justify-between text-secondary"><span>Domingos e Feriados</span><span class="font-medium italic">Fechado</span></li>
          </ul>
        </div>
      </div>

      <!-- ── Formulário ───────────────────────────────────────────────── -->
      <div class="lg:col-span-7 space-y-stack-lg">
        <?php if ($msg_sucesso): ?>
        <div class="bg-green-100 border border-green-300 text-green-800 px-6 py-4 rounded-xl font-body-md">
          <?= $msg_sucesso ?>
        </div>
        <?php endif; ?>
        <?php if ($msg_erro): ?>
        <div class="bg-red-100 border border-red-300 text-red-800 px-6 py-4 rounded-xl font-body-md">
          <?= $msg_erro ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="contato.php" class="bg-white p-stack-lg rounded-xl shadow-sm">
          <?= csrf_field() ?>
          <h2 class="font-headline-md text-headline-md mb-stack-lg">Envie uma mensagem</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-stack-lg">
            <div class="flex flex-col gap-2">
              <label class="font-label-md text-label-md text-primary" for="nome">Nome Completo</label>
              <input id="nome" name="nome" type="text" placeholder="Seu nome" required
                     class="minimal-input py-2 font-body-md text-body-md"
                     value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
            </div>
            <div class="flex flex-col gap-2">
              <label class="font-label-md text-label-md text-primary" for="email">E-mail</label>
              <input id="email" name="email" type="email" placeholder="seu@email.com" required
                     class="minimal-input py-2 font-body-md text-body-md"
                     value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="md:col-span-2 flex flex-col gap-2">
              <label class="font-label-md text-label-md text-primary" for="assunto">Assunto</label>
              <input id="assunto" name="assunto" type="text" placeholder="Como podemos ajudar?" required
                     class="minimal-input py-2 font-body-md text-body-md"
                     value="<?= htmlspecialchars($_POST['assunto'] ?? '') ?>">
            </div>
            <div class="md:col-span-2 flex flex-col gap-2">
              <label class="font-label-md text-label-md text-primary" for="mensagem">Mensagem</label>
              <textarea id="mensagem" name="mensagem" placeholder="Escreva sua mensagem..." required
                        class="minimal-input py-2 min-h-[120px] font-body-md text-body-md resize-none"><?= htmlspecialchars($_POST['mensagem'] ?? '') ?></textarea>
            </div>
          </div>
          <button type="submit"
                  class="mt-stack-lg w-full md:w-auto px-10 py-4 bg-primary text-on-primary font-label-md text-label-md rounded hover:opacity-90 active:scale-95 transition-all uppercase tracking-widest">
            Enviar Mensagem
          </button>
        </form>
      </div>

    </div>
  </div>
</main>

<!-- FAB WhatsApp (desktop visível aqui) -->
<div class="fixed bottom-10 right-10 z-[60]">
  <a class="flex items-center gap-3 bg-[#25D366] text-white px-6 py-3 rounded-full shadow-lg hover:scale-105 transition-transform"
     href="<?= whatsapp_link('Olá! Vim da página de contato e gostaria de atendimento.') ?>" target="_blank">
    <span class="material-symbols-outlined text-[24px]" style="font-variation-settings:'FILL' 1;">chat</span>
    <span class="font-label-md text-label-md">WhatsApp Concierge</span>
  </a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
