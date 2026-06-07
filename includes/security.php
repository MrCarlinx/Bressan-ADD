<?php
// includes/security.php — Helpers de segurança compartilhados

/**
 * Carrega variáveis do arquivo .env (formato KEY=VALUE).
 */
function load_env(string $path): void
{
    if (!is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        if ($key !== '' && getenv($key) === false) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

function env(string $key, ?string $default = null): ?string
{
    $value = $_ENV[$key] ?? getenv($key);
    return ($value !== false && $value !== '') ? (string) $value : $default;
}

function is_production(): bool
{
    return env('APP_ENV', 'local') === 'production';
}

function init_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $secure = is_production() || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function verify_csrf(?string $token): bool
{
    return isset($_SESSION['csrf_token'])
        && is_string($token)
        && hash_equals($_SESSION['csrf_token'], $token);
}

function require_csrf(): void
{
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
    if (!verify_csrf($token)) {
        http_response_code(403);
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Token de segurança inválido.']);
            exit;
        }
        die('Ação bloqueada: token de segurança inválido. Recarregue a página e tente novamente.');
    }
}

function regenerate_session(): void
{
    session_regenerate_id(true);
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function log_error(string $message): void
{
    $log_dir = dirname(__DIR__) . '/storage/logs';
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    @file_put_contents($log_dir . '/app.log', $line, FILE_APPEND | LOCK_EX);
}

function app_error(string $userMessage, ?string $internalMessage = null): void
{
    if ($internalMessage !== null) {
        log_error($internalMessage);
    }
    die(htmlspecialchars($userMessage, ENT_QUOTES, 'UTF-8'));
}

/** Rate limiting simples por chave (ex.: login por IP). */
function rate_limit_check(string $key, int $maxAttempts = 5, int $windowSeconds = 900): bool
{
    $now = time();
    $storageKey = 'rate_limit_' . md5($key);

    if (!isset($_SESSION[$storageKey])) {
        $_SESSION[$storageKey] = ['count' => 0, 'first' => $now];
    }

    $data = &$_SESSION[$storageKey];

    if ($now - $data['first'] > $windowSeconds) {
        $data = ['count' => 0, 'first' => $now];
    }

    return $data['count'] < $maxAttempts;
}

function rate_limit_hit(string $key): void
{
    $storageKey = 'rate_limit_' . md5($key);
    if (!isset($_SESSION[$storageKey])) {
        $_SESSION[$storageKey] = ['count' => 0, 'first' => time()];
    }
    $_SESSION[$storageKey]['count']++;
}

function rate_limit_reset(string $key): void
{
    unset($_SESSION['rate_limit_' . md5($key)]);
}

const PEDIDO_STATUS_VALIDOS = ['pendente', 'em_processamento', 'enviado', 'entregue', 'cancelado'];

function validar_status_pedido(string $status): bool
{
    return in_array($status, PEDIDO_STATUS_VALIDOS, true);
}

/**
 * Valida caminho de imagem de produto (apenas arquivos locais em img/produtos/).
 */
function validar_img_url(string $url): ?string
{
    $url = trim($url);
    if ($url === '') {
        return 'img/produtos/placeholder.jpg';
    }

    if (preg_match('#^(https?://|//|javascript:|data:)#i', $url)) {
        return null;
    }

    if (str_contains($url, '..') || str_contains($url, '\\')) {
        return null;
    }

    if (!preg_match('#^img/produtos/[a-zA-Z0-9._\-/]+$#', $url)) {
        return null;
    }

    return $url;
}

function validar_senha(string $senha): ?string
{
    if (strlen($senha) < 8) {
        return 'A senha deve ter no mínimo 8 caracteres.';
    }
    if (!preg_match('/[A-Za-z]/', $senha) || !preg_match('/[0-9]/', $senha)) {
        return 'A senha deve conter letras e números.';
    }
    return null;
}

function validar_email(string $email): bool
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

function esc(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Revalida no banco se o usuário logado ainda é admin.
 */
function revalidar_admin(PDO $pdo): bool
{
    if (empty($_SESSION['cliente_id'])) {
        return false;
    }

    $stmt = $pdo->prepare("SELECT role FROM clientes WHERE id = ?");
    $stmt->execute([(int) $_SESSION['cliente_id']]);
    $row = $stmt->fetch();

    if (!$row || $row['role'] !== 'admin') {
        return false;
    }

    $_SESSION['cliente_role'] = 'admin';
    return true;
}
