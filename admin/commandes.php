<?php
require 'auth.php';
require '../config/db.php';

// Changer le statut d'une commande
if (isset($_POST['statut'], $_POST['commande_id'])) {
    $statutsValides = ['En attente', 'Payé', 'Expédié'];
    if (in_array($_POST['statut'], $statutsValides)) {
        $stmt = $pdo->prepare("UPDATE commandes SET statut = ? WHERE id = ?");
        $stmt->execute([$_POST['statut'], (int) $_POST['commande_id']]);
    }
    header("Location: commandes.php");
    exit;
}

$commandes = $pdo->query("SELECT * FROM commandes ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Commandes</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container">
    <h1>Commandes (<?= count($commandes) ?>)</h1>
    <p><a href="index.php">← Tableau de bord</a> &nbsp;|&nbsp; <a href="logout.php">Se déconnecter</a></p>

    <?php foreach ($commandes as $cmd): ?>
        <div class="card" style="padding:20px; margin-bottom:16px; max-width:700px;">
            <h3>Commande #<?= $cmd['id'] ?> — <?= htmlspecialchars($cmd['nom_client']) ?>
    &nbsp;<a href="facture.php?id=<?= $cmd['id'] ?>" target="_blank" style="font-size:0.8rem;">🧾 Voir la facture</a>
</h3>
            <p><?= htmlspecialchars($cmd['email']) ?></p>            
            <p>📱 <?= htmlspecialchars($cmd['whatsapp']) ?></p>
            <p><?= date('d/m/Y H:i', strtotime($cmd['date_creation'])) ?></p>
            <p><strong><?= number_format($cmd['total'], 0, ',', ' ') ?> Ar</strong></p>

            <?php
            // Détail des produits commandés
            $stmt = $pdo->prepare("
                SELECT p.nom, cd.quantite, cd.prix
                FROM commande_details cd
                JOIN produits p ON p.id = cd.produit_id
                WHERE cd.commande_id = ?
            ");
            $stmt->execute([$cmd['id']]);
            $lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <ul>
                <?php foreach ($lignes as $l): ?>
                    <li><?= htmlspecialchars($l['nom']) ?> × <?= $l['quantite'] ?> — <?= number_format($l['prix'] * $l['quantite'], 0, ',', ' ') ?> Ar</li>
                <?php endforeach; ?>
            </ul>

            <form method="POST" style="margin-top:12px; max-width:250px;">
                <input type="hidden" name="commande_id" value="<?= $cmd['id'] ?>">
                <select name="statut" onchange="this.form.submit()" style="padding:10px; border-radius:3px; border:1px solid #e3e1da;">
                    <option value="En attente" <?= $cmd['statut'] == 'En attente' ? 'selected' : '' ?>>En attente</option>
                    <option value="Payé" <?= $cmd['statut'] == 'Payé' ? 'selected' : '' ?>>Payé</option>
                    <option value="Expédié" <?= $cmd['statut'] == 'Expédié' ? 'selected' : '' ?>>Expédié</option>
                </select>
            </form>
            <?php
$lienFacture = "http://localhost/ecommerce/facture-client.php?id=" . $cmd['id'] . "&token=" . $cmd['token'];

$texteFacture = "🧾 Rinah Multi-Services\n";
$texteFacture .= "Bonjour " . $cmd['nom_client'] . ",\n\n";
$texteFacture .= "Voici votre facture (commande #" . str_pad($cmd['id'], 4, '0', STR_PAD_LEFT) . ") :\n";
$texteFacture .= $lienFacture . "\n\n";
$texteFacture .= "Total : " . number_format($cmd['total'], 0, ',', ' ') . " Ar\n";
$texteFacture .= "Merci de votre confiance !";

// Nettoyer le numéro : enlever espaces/tirets, remplacer le 0 initial par l'indicatif Madagascar (261)
$numeroPropre = preg_replace('/[^0-9]/', '', $cmd['whatsapp']);
if (substr($numeroPropre, 0, 1) === '0') {
    $numeroPropre = '261' . substr($numeroPropre, 1);
}
$lienWhatsapp = "https://wa.me/" . $numeroPropre . "?text=" . urlencode($texteFacture);
?>
<a href="<?= $lienWhatsapp ?>" target="_blank" style="display:inline-block; margin-top:10px; background:#25D366; color:white; padding:8px 16px; border-radius:4px; font-weight:600; text-decoration:none;">
    📱 Envoyer par WhatsApp
</a>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
