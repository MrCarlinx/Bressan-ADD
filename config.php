<?php
// ============================================================
// config.php — Configurações globais do site Toca do Coelho
// ============================================================

require_once __DIR__ . '/includes/security.php';

load_env(__DIR__ . '/.env');
init_secure_session();

// Auto-detect base path
$config_dir = str_replace('\\', '/', __DIR__);
$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
$base_path = str_replace($doc_root, '', $config_dir);

define('SITE_NAME', 'Toca do Coelho');
define('SITE_URL', $base_path);

// Banco de Dados (lidos do .env)
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_NAME', env('DB_NAME', 'toca_do_coelho'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));

define('WHATSAPP_NUMBER', '5569992829089');
define('WHATSAPP_MSG_DEFAULT', 'Olá, gostaria de mais informações!');

define('CONTACT_PHONE', '+55 (69) 99282-9089');
define('CONTACT_EMAIL', 'contato@tocadocoelho.com.br');
define('CONTACT_ADDRESS', 'Rua Sete de Setembro, 1234 - Centro, Porto Velho - RO');

function whatsapp_link(string $msg = WHATSAPP_MSG_DEFAULT): string
{
    return 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . urlencode($msg);
}

function asset(string $path): string
{
    return SITE_URL . '/assets/' . ltrim($path, '/');
}

function page(string $path): string
{
    return SITE_URL . '/pages/' . ltrim($path, '/');
}
