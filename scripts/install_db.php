<?php
// scripts/install_db.php — Execute via CLI: php scripts/install_db.php
require_once __DIR__ . '/../config.php';

if (php_sapi_name() !== 'cli') {
    $token = $_GET['token'] ?? $_POST['token'] ?? '';
    $expected = env('INSTALL_TOKEN', '');
    if ($expected === '' || !hash_equals($expected, (string) $token)) {
        http_response_code(403);
        die('Acesso negado.');
    }
}

echo "Instalando Banco de Dados...\n";

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';charset=utf8mb4', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents(__DIR__ . '/../database.sql');
    $pdo->exec($sql);
    echo "Banco de dados e tabelas criados com sucesso!\n";
} catch (PDOException $e) {
    log_error('install_db schema failed: ' . $e->getMessage());
    die("Erro ao criar banco de dados. Verifique storage/logs/app.log\n");
}

$pdo = new PDO(
    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
    DB_USER,
    DB_PASS,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$img_dir = __DIR__ . '/../assets/img/produtos';
if (!is_dir($img_dir)) {
    mkdir($img_dir, 0755, true);
}

// Criar admin inicial a partir do .env (somente se não existir)
$adminEmail = env('ADMIN_EMAIL', '');
$adminPass  = env('ADMIN_PASS', '');

if ($adminEmail !== '' && $adminPass !== '') {
    if (!validar_email($adminEmail)) {
        die("ADMIN_EMAIL inválido no .env\n");
    }
    if ($senhaError = validar_senha($adminPass)) {
        die("ADMIN_PASS inválido: $senhaError\n");
    }

    $stmt = $pdo->prepare('SELECT id FROM clientes WHERE email = ?');
    $stmt->execute([$adminEmail]);
    if (!$stmt->fetch()) {
        $hash = password_hash($adminPass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO clientes (nome, email, senha_hash, role) VALUES (?, ?, ?, 'admin')");
        $stmt->execute(['Administrador', $adminEmail, $hash]);
        echo "Admin criado: $adminEmail\n";
    } else {
        echo "Admin já existe para $adminEmail — ignorado.\n";
    }
} else {
    echo "AVISO: Defina ADMIN_EMAIL e ADMIN_PASS no .env para criar o usuário admin.\n";
}

$produtos = [
  ['cat'=>'Eletrodomésticos','nome'=>'Batedeira Profissional Inox',    'preco'=>1890.00, 'badge'=>null,    'img'=>'https://lh3.googleusercontent.com/aida-public/AB6AXuAzaDCiNDRz7ad4egiaZYCgbThoyEA2DXVFFssCFfngK91oKdqKr-6VAsr4bePRhPPF1YTu5qpGq-G2XbT3He5N_6AILg5YmS4GQpo914lj7iLw9_CiAbuZxc-P83diF5515Oag1DcCfiNOU5om42z5hVeW24YibOEbzzzBYOvoUWF38qwv8hb9G0RQg6N2lqPRlOn08rWlpRUxlJnXo_EpkFIqf8Vyi24tp7ukteQg5xCS28zwoWap-oySd3GDIBSAyZ6hYX8Z7_SP'],
  ['cat'=>'Panelas',          'nome'=>'Conjunto Ferro Fundido Navy',    'preco'=>850.00,  'badge'=>'OFERTA','img'=>'https://lh3.googleusercontent.com/aida-public/AB6AXuAL3mIizWnv1YjOO34cxfQ3jk_22uGw3fCf0kijNRXtzKeAXTK48WeCMFdOycj4EyiLKbZuE0ikYqdt6MqPg4PKKXMgtbaGDoyIwIp0DbQ9VYsnuR9pFSWRJP7pwlAJ-qFR7_4ZpNT5P7acL3GHrTy7DM9S0ea2nVgwhbaEa7dYsESzG6898nf0Ljguz9LPjI8bADEN5kT9OtE3_LVgr28LUeiSzM0Ub-Tw18y7qruSFQWjAU_5fw-4Ryjggl87ASo23WRXCkTLnRpN'],
  ['cat'=>'Organização',      'nome'=>'Potes Cerâmica Heritage',       'preco'=>120.00,   'badge'=>null,    'img'=>'https://lh3.googleusercontent.com/aida-public/AB6AXuAdTKs7I7GB6GkNHYIhuZD0T7uvLLmSGAam42NT1OA1rvG9X-Z7zmHYZVZ7yrE_3K1tY_oiLnU6BV-hbz8RvCG7IF_mqSMYD_wMj1wBVLihxVfJvXBsRTLhKWQLb1Zp-hiizD3quB_C8lhOvjDvuHiTrOOx451e1TdqhoFVzK6jcpSi_2Y4ZM_cr9Pa1vFQGddHCiDPSXaTo4NGGWTtskE_4UILVxPWNfR1UcIt2_DnSD263_I6KyTE17RvhSp8Besq7brp0VxSJcVJ'],
  ['cat'=>'Equipamentos comerciais','nome'=>'Máquina Espresso Pro',    'preco'=>4500.00,  'badge'=>null,   'img'=>'https://lh3.googleusercontent.com/aida-public/AB6AXuC3OisYpxCHD_pH0FeABifqMouYQN26XVEK4Fb6dKk-s7Ij3QcXonVEg898VQguCbl-6dPAQ4KH4OmNvlpJHs6u12Iq2s4Z-vpFt3lqFs7DcvIo48pmqI3zb6cJdX0spNg876RgeeiD5TprGrtfvGKHCZqq3sSKSONYNhDN2Hrsk8z1K6xYMrafqEu1H3_xOuuDMi-jzpvW805uU-GuM-sr6ZpkxOzeGHaprnaMDPM-8HD7Zv2W_wXEEhRv5BS7iHiHto0GQ_FI5E9R'],
  ['cat'=>'Utensílios',       'nome'=>'Kit Utensílios Signature',      'preco'=>290.00,   'badge'=>null,    'img'=>'https://lh3.googleusercontent.com/aida-public/AB6AXuA9Cf7gRBTG9LrcA7_qfqy8QVJmFFCv4kkQ3zQ6gI32AFJn_NODKB-dC1a6HALG5DmTaquwS6AmwpeK0ZjywHqU8BUIdGtE9NVUYZfQTW2ZnsNsv6AP7g20t0ZSzTGuVfqR2vwVd6EEt2oDP3BNg5h2Bk6xZ8YllqhYrcOGlxlC99LJ72MKu3znuQF6TASnwk_AsdvsbMmz3yGytOPdJj9a-Lu_16e2xm-zf6OV78-fPRSVCJeRKOUHWc1RtSAxCboXooOsYF0FoCwA'],
  ['cat'=>'Decoração',        'nome'=>'Terrário Geometric Gold',       'preco'=>180.00,   'badge'=>'OFERTA','img'=>'https://lh3.googleusercontent.com/aida-public/AB6AXuCyRMX9N96nD1LacRS7z1JTFzCy6D1aH7xffqmgZeZCuh1cIaM995u9c9HPNSJlMj2LQ_yhgfGWNse50zmJRxdj30CcWEmnqkeYP5DncL9flRiqOGlUBXV3RgCHcI9M2odIm5BbPCgWLGy2GmoLRkggGskOpB-J83_I6vvc9XWj8qdBRUIfoPjF2vUVY7Nv4Q-ODimscozT24jfz6R7ZeJMFTV_SMC2LsYC9kjtu3WfuAtGX5yqRrD4i4pLuINTOQYX_ilk2bziLmd8'],
];

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0;');
$pdo->exec('TRUNCATE TABLE produtos');
$pdo->exec('TRUNCATE TABLE categorias');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1;');

foreach ($produtos as $p) {
    $stmt = $pdo->prepare('SELECT id FROM categorias WHERE nome = ?');
    $stmt->execute([$p['cat']]);
    $cat = $stmt->fetch();
    if (!$cat) {
        $stmt = $pdo->prepare('INSERT INTO categorias (nome) VALUES (?)');
        $stmt->execute([$p['cat']]);
        $id_cat = $pdo->lastInsertId();
    } else {
        $id_cat = $cat['id'];
    }

    $filename = uniqid('prod_') . '.jpg';
    $filepath = $img_dir . '/' . $filename;

    echo "Baixando imagem para " . $p['nome'] . "...\n";
    $img_data = @file_get_contents($p['img']);
    if ($img_data) {
        file_put_contents($filepath, $img_data);
    } else {
        echo "Falha ao baixar imagem, usando placeholder.\n";
        $filename = 'placeholder.jpg';
    }

    $stmt = $pdo->prepare('INSERT INTO produtos (id_categoria, nome, descricao, preco, badge, img_url) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $id_cat,
        $p['nome'],
        'Descrição detalhada para ' . $p['nome'] . '. Ideal para uso no dia a dia ou profissional.',
        $p['preco'],
        $p['badge'],
        'img/produtos/' . $filename,
    ]);
}

echo "Seed completado com sucesso!\n";
