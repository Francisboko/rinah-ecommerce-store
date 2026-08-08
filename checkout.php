<?php
session_start();
require 'config/db.php';

// Sécurité : rediriger si panier vide
if (empty($_SESSION['panier'])) {
    header("Location: panier.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $whatsapp = trim($_POST['whatsapp']);

    // Calcul du total
    $total = 0;
    $details = []; // on stocke pour éviter de refaire les requêtes 2 fois
    foreach ($_SESSION['panier'] as $id => $qte) {
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
        $stmt->execute([$id]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$p) continue;

        $total += $p['prix'] * $qte;
        $details[] = ['id' => $id, 'qte' => $qte, 'prix' => $p['prix']];
    }

    // Insertion de la commande
    $token = bin2hex(random_bytes(16));
    $stmt = $pdo->prepare("INSERT INTO commandes (nom_client, email, whatsapp, total, token) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $email, $whatsapp, $total, $token]);
    $cmd_id = $pdo->lastInsertId();

    // Insertion des détails
    $stmt = $pdo->prepare("INSERT INTO commande_details (commande_id, produit_id, quantite, prix) VALUES (?, ?, ?, ?)");
    foreach ($details as $d) {
        $stmt->execute([$cmd_id, $d['id'], $d['qte'], $d['prix']]);
    }

    unset($_SESSION['panier']);
    header("Location: merci.php");
    exit;
}

require 'includes/header.php';
?>
<div class="container">
<h1>Finaliser la commande</h1>
<form method="POST">
    <input type="text" name="nom" placeholder="Nom complet" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="tel" name="whatsapp" placeholder="Numéro WhatsApp (ex: 0341240680)" required>
    <button type="submit">Payer à la livraison</button>
</form>
</div>
<?php require 'includes/footer.php'; ?>