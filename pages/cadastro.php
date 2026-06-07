<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';

$error = '';
$redirect = $_GET['redirect'] ?? 'inicio';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($nome) || empty($email) || empty($senha)) {
        $error = 'Preencha todos os campos.';
    } elseif (!validar_email($email)) {
        $error = 'E-mail inválido.';
    } elseif ($senhaError = validar_senha($senha)) {
        $error = $senhaError;
    } else {
        // Verificar se email já existe
        $stmt = $pdo->prepare("SELECT id FROM clientes WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Este e-mail já está em uso.';
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO clientes (nome, email, senha_hash, role) VALUES (?, ?, ?, 'cliente')");
            if ($stmt->execute([$nome, $email, $hash])) {
                regenerate_session();

                $_SESSION['cliente_id'] = $pdo->lastInsertId();
                $_SESSION['cliente_nome'] = $nome;
                $_SESSION['cliente_role'] = 'cliente';
                
                if ($redirect === 'carrinho') {
                    header('Location: ' . page('carrinho.php'));
                } else {
                    header('Location: ' . SITE_URL . '/index.php');
                }
                exit;
            } else {
                $error = 'Erro ao criar conta. Tente novamente.';
            }
        }
    }
}

$page_title  = 'Cadastro';
$active_page = 'login';
require_once __DIR__ . '/../includes/head.php';
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<main class="min-h-screen flex items-center justify-center pt-16 pb-section-padding px-gutter bg-surface-container-low">
  <div class="bg-surface-container-lowest custom-shadow rounded-2xl w-full max-w-md p-10">
    <div class="text-center mb-8">
      <span class="material-symbols-outlined text-primary text-5xl mb-2">person_add</span>
      <h1 class="font-headline-lg text-headline-lg text-primary">Criar Conta</h1>
    </div>
    
    <?php if($error): ?>
    <div class="bg-error-container text-on-error-container p-4 rounded-lg mb-6 font-label-md text-sm">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" class="space-y-6">
      <?= csrf_field() ?>
      <div>
        <label class="block font-label-md text-on-surface-variant mb-2">Nome Completo</label>
        <input type="text" name="nome" required class="w-full border border-outline-variant rounded-lg px-4 py-3 bg-transparent focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
      </div>
      <div>
        <label class="block font-label-md text-on-surface-variant mb-2">E-mail</label>
        <input type="email" name="email" required class="w-full border border-outline-variant rounded-lg px-4 py-3 bg-transparent focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
      </div>
      <div>
        <label class="block font-label-md text-on-surface-variant mb-2">Senha</label>
        <input type="password" name="senha" required minlength="8" class="w-full border border-outline-variant rounded-lg px-4 py-3 bg-transparent focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
        <p class="text-xs text-on-surface-variant mt-1">Mínimo 8 caracteres, com letras e números.</p>
      </div>
      
      <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-on-primary py-4 rounded-full font-label-md text-label-md transition-transform active:scale-95 shadow-md">
        Cadastrar
      </button>
    </form>
    
    <div class="mt-8 text-center border-t border-outline-variant/30 pt-6">
      <p class="font-body-md text-secondary">Já possui conta?</p>
      <a href="<?= page('login.php?redirect=' . urlencode($redirect)) ?>" class="inline-block mt-2 font-label-md text-primary hover:underline">Fazer Login</a>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
