<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';

$error = '';
$redirect = $_GET['redirect'] ?? 'inicio';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $rateKey = 'login_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . '_' . strtolower($email);

    if (!rate_limit_check($rateKey)) {
        $error = 'Muitas tentativas. Aguarde 15 minutos e tente novamente.';
    } elseif (empty($email) || empty($senha)) {
        $error = 'Preencha todos os campos.';
    } elseif (!validar_email($email)) {
        $error = 'E-mail inválido.';
    } else {
        $stmt = $pdo->prepare("SELECT id, nome, senha_hash, role FROM clientes WHERE email = ?");
        $stmt->execute([$email]);
        $cliente = $stmt->fetch();
        
        if ($cliente && password_verify($senha, $cliente['senha_hash'])) {
            rate_limit_reset($rateKey);
            regenerate_session();

            $_SESSION['cliente_id'] = $cliente['id'];
            $_SESSION['cliente_nome'] = $cliente['nome'];
            $_SESSION['cliente_role'] = $cliente['role'];
            
            if ($cliente['role'] === 'admin') {
                header('Location: ' . SITE_URL . '/admin/index.php');
            } else {
                if ($redirect === 'carrinho') {
                    header('Location: ' . page('carrinho.php'));
                } else {
                    header('Location: ' . SITE_URL . '/index.php');
                }
            }
            exit;
        } else {
            rate_limit_hit($rateKey);
            $error = 'Email ou senha inválidos.';
        }
    }
}

$page_title  = 'Login';
$active_page = 'login';
require_once __DIR__ . '/../includes/head.php';
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<main class="min-h-screen flex items-center justify-center pt-16 pb-section-padding px-gutter bg-surface-container-low">
  <div class="bg-surface-container-lowest custom-shadow rounded-2xl w-full max-w-md p-10">
    <div class="text-center mb-8">
      <span class="material-symbols-outlined text-primary text-5xl mb-2">account_circle</span>
      <h1 class="font-headline-lg text-headline-lg text-primary">Acesse sua Conta</h1>
    </div>
    
    <?php if($error): ?>
    <div class="bg-error-container text-on-error-container p-4 rounded-lg mb-6 font-label-md text-sm">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" class="space-y-6">
      <?= csrf_field() ?>
      <div>
        <label class="block font-label-md text-on-surface-variant mb-2">E-mail</label>
        <input type="email" name="email" required class="w-full border border-outline-variant rounded-lg px-4 py-3 bg-transparent focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
      </div>
      <div>
        <label class="block font-label-md text-on-surface-variant mb-2">Senha</label>
        <input type="password" name="senha" required class="w-full border border-outline-variant rounded-lg px-4 py-3 bg-transparent focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
      </div>
      
      <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-on-primary py-4 rounded-full font-label-md text-label-md transition-transform active:scale-95 shadow-md">
        Entrar
      </button>
    </form>
    
    <div class="mt-8 text-center border-t border-outline-variant/30 pt-6">
      <p class="font-body-md text-secondary">Ainda não tem conta?</p>
      <a href="<?= page('cadastro.php?redirect=' . urlencode($redirect)) ?>" class="inline-block mt-2 font-label-md text-primary hover:underline">Criar nova conta</a>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
