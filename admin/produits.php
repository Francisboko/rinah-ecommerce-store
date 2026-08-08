<?php
require 'auth.php';
session_start();
require '../config/db.php';

// Ajouter un produit
if (isset($_POST['ajouter'])) {
    $stmt = $pdo->prepare("INSERT INTO produits (nom, categorie, description, prix, unite, image, stock) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        trim($_POST['nom']),
        trim($_POST['categorie']),
        trim($_POST['description']),
        (float) $_POST['prix'],
        trim($_POST['unite']),
        trim($_POST['image']),
        (int) $_POST['stock']
    ]);
    header("Location: produits.php");
    exit;
}

// Modifier un produit
if (isset($_POST['modifier'])) {
    $stmt = $pdo->prepare("UPDATE produits SET nom=?, categorie=?, description=?, prix=?, unite=?, image=?, stock=? WHERE id=?");
    $stmt->execute([
        trim($_POST['nom']),
        trim($_POST['categorie']),
        trim($_POST['description']),
        (float) $_POST['prix'],
        trim($_POST['unite']),
        trim($_POST['image']),
        (int) $_POST['stock'],
        (int) $_POST['id']
    ]);
    header("Location: produits.php");
    exit;
}

// Supprimer un produit
if (isset($_GET['del'])) {
    $stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?");
    $stmt->execute([(int) $_GET['del']]);
    header("Location: produits.php");
    exit;
}

// Si on clique "Éditer", on charge le produit à modifier
$produit_edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([(int) $_GET['edit']]);
    $produit_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Liste de tous les produits
$produits = $pdo->query("SELECT * FROM produits ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Produits</title>
    <link rel="stylesheet" href="/ecommerce/assets/css/style.css">
</head>
<body>
<div class="container">
    <h1><?= $produit_edit ? 'Modifier le produit' : 'Ajouter un produit' ?></h1>
    <p><a href="logout.php">Se déconnecter</a></p>

    <form method="POST">
        <?php if ($produit_edit): ?>
            <input type="hidden" name="id" value="<?= $produit_edit['id'] ?>">
        <?php endif; ?>

        <input name="nom" placeholder="Nom du produit" value="<?= htmlspecialchars($produit_edit['nom'] ?? '') ?>" required>
        <input name="categorie" placeholder="Catégorie" value="<?= htmlspecialchars($produit_edit['categorie'] ?? '') ?>" required>
        <textarea name="description" placeholder="Description"><?= htmlspecialchars($produit_edit['description'] ?? '') ?></textarea>
        <input name="prix" type="number" step="0.01" placeholder="Prix" value="<?= htmlspecialchars($produit_edit['prix'] ?? '') ?>" required>
        <input name="unite" placeholder="Unité (pièce, m²...)" value="<?= htmlspecialchars($produit_edit['unite'] ?? 'pièce') ?>" required>
        <input name="image" placeholder="Nom du fichier image (ex: polo.jpg)" value="<?= htmlspecialchars($produit_edit['image'] ?? '') ?>" required>
        <input name="stock" type="number" placeholder="Stock" value="<?= htmlspecialchars($produit_edit['stock'] ?? 10) ?>" required>

        <button type="submit" name="<?= $produit_edit ? 'modifier' : 'ajouter' ?>">
            <?= $produit_edit ? 'Enregistrer les modifications' : 'Ajouter le produit' ?>
        </button>
    </form>

    <h1 style="margin-top:40px">Liste des produits (<?= count($produits) ?>)</h1>

    <table>
        <tr>
            <td><strong>Nom</strong></td>
            <td><strong>Catégorie</strong></td>
            <td><strong>Prix</strong></td>
            <td><strong>Stock</strong></td>
            <td><strong>Actions</strong></td>
        </tr>
        <?php foreach ($produits as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['nom']) ?></td>
            <td><?= htmlspecialchars($p['categorie']) ?></td>
            <td><?= number_format($p['prix'], 0, ',', ' ') ?> Ar</td>
            <td><?= $p['stock'] ?></td>
            <td>
                <a href="?edit=<?= $p['id'] ?>">Modifier</a>
                &nbsp;|&nbsp;
                <a href="?del=<?= $p['id'] ?>" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>