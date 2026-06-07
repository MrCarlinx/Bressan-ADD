<?php
// includes/head.php
// Parâmetro: $page_title (string) — título específico da página
$page_title = isset($page_title) ? $page_title . ' | ' . SITE_NAME : SITE_NAME;
?>
<!DOCTYPE html>
<html lang="pt-BR" class="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= esc(csrf_token()) ?>">
  <title><?= esc($page_title) ?></title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

  <!-- Fontes -->
  <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

  <!-- CSS customizado do projeto -->
  <link href="<?= asset('css/custom.css') ?>" rel="stylesheet">

  <!-- Tailwind Config (tokens do design system) -->
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "surface-container-low": "#f5f3f3",
            "surface-container": "#efeded",
            "inverse-primary": "#b9c7e4",
            "tertiary-fixed-dim": "#e9c176",
            "secondary-fixed-dim": "#c8c7bd",
            "on-surface-variant": "#44474d",
            "surface-dim": "#dbd9d9",
            "on-tertiary-fixed-variant": "#5d4201",
            "surface-container-lowest": "#ffffff",
            "secondary-fixed": "#e4e3d8",
            "on-tertiary": "#ffffff",
            "on-secondary-fixed-variant": "#474740",
            "surface-container-highest": "#e4e2e2",
            "surface-bright": "#fbf9f8",
            "primary-container": "#0d1c32",
            "error": "#ba1a1a",
            "background": "#fbf9f8",
            "on-primary-fixed": "#0d1c32",
            "tertiary-container": "#261900",
            "on-primary-fixed-variant": "#39475f",
            "tertiary": "#000000",
            "inverse-surface": "#303030",
            "on-primary-container": "#76849f",
            "on-secondary-container": "#65655c",
            "secondary-container": "#e4e3d8",
            "outline-variant": "#c5c6cd",
            "on-tertiary-fixed": "#261900",
            "on-surface": "#1b1c1c",
            "primary": "#000000",
            "surface-container-high": "#eae8e7",
            "primary-fixed-dim": "#b9c7e4",
            "on-error": "#ffffff",
            "on-secondary-fixed": "#1b1c16",
            "on-secondary": "#ffffff",
            "on-tertiary-container": "#a17f3b",
            "error-container": "#ffdad6",
            "on-primary": "#ffffff",
            "surface-tint": "#515f78",
            "secondary": "#5f5f57",
            "surface-variant": "#e4e2e2",
            "outline": "#75777e",
            "primary-fixed": "#d6e3ff",
            "inverse-on-surface": "#f2f0f0",
            "on-background": "#1b1c1c",
            "on-error-container": "#93000a",
            "tertiary-fixed": "#ffdea5",
            "surface": "#fbf9f8",
            "emerald-cta": "#10b981"
          },
          spacing: {
            "stack-md": "16px", "container-max": "1280px",
            "stack-lg": "32px", "stack-sm": "8px",
            "gutter": "24px", "margin-mobile": "16px",
            "margin-desktop": "48px",
            "base": "8px", "section-padding": "80px"
          },
          fontFamily: {
            "headline-lg": ["Lexend"], "headline-xl": ["Lexend"],
            "headline-xl-mobile": ["Lexend"], "headline-md": ["Lexend"],
            "label-sm": ["Inter"], "label-md": ["Inter"],
            "body-lg": ["Inter"], "body-md": ["Inter"]
          },
          fontSize: {
            "headline-xl":         ["48px", { lineHeight:"1.1",  letterSpacing:"-0.02em", fontWeight:"600" }],
            "headline-xl-mobile":  ["32px", { lineHeight:"1.2",  fontWeight:"600" }],
            "headline-lg":         ["32px", { lineHeight:"1.2",  fontWeight:"500" }],
            "headline-md":         ["24px", { lineHeight:"1.3",  fontWeight:"500" }],
            "label-md":            ["14px", { lineHeight:"1.2",  letterSpacing:"0.05em", fontWeight:"600" }],
            "label-sm":            ["12px", { lineHeight:"1.2",  fontWeight:"500" }],
            "body-lg":             ["18px", { lineHeight:"1.6",  fontWeight:"400" }],
            "body-md":             ["16px", { lineHeight:"1.6",  fontWeight:"400" }]
          }
        }
      }
    }
  </script>
  <script>window.SITE_URL = "<?= SITE_URL ?>";</script>
</head>
<body class="bg-background text-on-background font-body-md antialiased">
