<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Boutique en ligne</title>
    <link rel="stylesheet" href="/ecommerce/assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="logo">
    <a href="/ecommerce/index.php">
        <img src="/ecommerce/assets/img/logo.jpg" alt="Rinah Multi-Services" class="logo-img">
        Rinah Multi-Services
    </a>
</div>
        <nav>
            <a href="/ecommerce/index.php">Accueil</a>
            <a href="/ecommerce/panier.php">
                Panier
                <?php
                $nbArticles = 0;
                if (!empty($_SESSION['panier'])) {
                    foreach ($_SESSION['panier'] as $qte) {
                        $nbArticles += $qte;
                    }
                }
                ?>
                (<?= $nbArticles ?>)
            </a>
        </nav>
    </header>
