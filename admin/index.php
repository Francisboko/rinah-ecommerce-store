<?php
require 'auth.php';
require '../config/db.php';

$nbProduits = $pdo->query("SELECT COUNT(*) FROM produits")->fetchColumn();
$nbCommandes = $pdo->query("SELECT COUNT(*) FROM commandes")->fetchColumn();
$nbEnAttente = $pdo->query("SELECT COUNT(*) FROM commandes WHERE statut = 'En attente'")->fetchColumn();
$totalVentes = $pdo->query("SELECT SUM(total) FROM commandes WHERE statut != 'En attente'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Tableau de bord</title>
    <link rel="stylesheet" href="/ecommerce/assets/css/style.css">
</head>
<body>
<div class="container">
    <h1>Tableau de bord</h1>
    <p><a href="logout.php">Se déconnecter</a></p>

    <div class="produits" style="margin-top:24px;">
        <div class="card" style="padding:20px;">
            <h3>Produits</h3>
            <p style="font-size:1.8rem; font-weight:700;"><?= $nbProduits ?></p>
        </div>
        <div class="card" style="padding:20px;">
            <h3>Commandes totales</h3>
            <p style="font-size:1.8rem; font-weight:700;"><?= $nbCommandes ?></p>
        </div>
        <div class="card" style="padding:20px;">
            <h3>En attente</h3>
            <p style="font-size:1.8rem; font-weight:700;"><?= $nbEnAttente ?></p>
        </div>
        <div class="card" style="padding:20px;">
            <h3>Ventes confirmées</h3>
            <p style="font-size:1.8rem; font-weight:700;"><?= number_format($totalVentes ?? 0, 0, ',', ' ') ?> Ar</p>
        </div>
    </div>

    <div style="margin-top:32px; display:flex; gap:16px;">
        <a href="produits.php" style="background:var(--navy); color:white; padding:14px 24px; border-radius:4px; font-weight:700;">Gérer les produits</a>
        <a href="commandes.php" style="background:var(--navy); color:white; padding:14px 24px; border-radius:4px; font-weight:700;">Voir les commandes</a>
    </div>
</div>
</body>
</html>