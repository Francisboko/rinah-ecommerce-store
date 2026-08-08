<?php
require 'auth.php';
require '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: commandes.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM commandes WHERE id = ?");
$stmt->execute([$id]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    die("Commande introuvable.");
}

$stmt = $pdo->prepare("
    SELECT p.nom, cd.quantite, cd.prix
    FROM commande_details cd
    JOIN produits p ON p.id = cd.produit_id
    WHERE cd.commande_id = ?
");
$stmt->execute([$id]);
$lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #<?= $commande['id'] ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "Segoe UI", system-ui, sans-serif;
            color: #1C1B19;
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
        }
        .no-print { margin-bottom: 24px; }
        .no-print button, .no-print a {
            background: #14213D;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        .entete {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #14213D;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .entete img { width: 80px; height: 80px; border-radius: 50%; }
        .entete .societe { text-align: right; }
        .entete h1 { font-size: 1.4rem; color: #14213D; }
        .titre-facture {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .titre-facture h2 { font-size: 1.6rem; text-transform: uppercase; }
        .client-info { line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { text-align: left; padding: 10px; border-bottom: 1px solid #e3e1da; }
        th { background: #f5f4f0; text-transform: uppercase; font-size: 0.85rem; }
        td.montant, th.montant { text-align: right; }
        .total-ligne {
            display: flex;
            justify-content: flex-end;
            font-size: 1.3rem;
            font-weight: 700;
            margin-top: 10px;
        }
        .statut-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 3px;
            background: #FFC400;
            font-weight: 700;
            font-size: 0.85rem;
        }
        footer.mentions {
            margin-top: 60px;
            text-align: center;
            font-size: 0.8rem;
            color: #6b6a67;
        }

        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()">🖨️ Imprimer / Enregistrer en PDF</button>
        <a href="commandes.php">← Retour aux commandes</a>
    </div>

    <div class="entete">
        <img src="/ecommerce/assets/img/logo.jpg" alt="Rinah Multi-Services">
        <div class="societe">
            <h1>RINAH MULTI-SERVICES</h1>
            <p>Tél : 034 34 469 52</p>
        </div>
    </div>

    <div class="titre-facture">
        <h2>Facture #<?= str_pad($commande['id'], 4, '0', STR_PAD_LEFT) ?></h2>
        <div class="client-info">
            <strong><?= htmlspecialchars($commande['nom_client']) ?></strong><br>
            <?= htmlspecialchars($commande['email']) ?><br>
            Date : <?= date('d/m/Y', strtotime($commande['date_creation'])) ?><br>
            Statut : <span class="statut-badge"><?= htmlspecialchars($commande['statut']) ?></span>
        </div>
    </div>

    <table>
        <tr>
            <th>Produit</th>
            <th>Qté</th>
            <th class="montant">Prix unitaire</th>
            <th class="montant">Sous-total</th>
        </tr>
        <?php foreach ($lignes as $l): ?>
        <tr>
            <td><?= htmlspecialchars($l['nom']) ?></td>
            <td><?= $l['quantite'] ?></td>
            <td class="montant"><?= number_format($l['prix'], 0, ',', ' ') ?> Ar</td>
            <td class="montant"><?= number_format($l['prix'] * $l['quantite'], 0, ',', ' ') ?> Ar</td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="total-ligne">
        Total : <?= number_format($commande['total'], 0, ',', ' ') ?> Ar
    </div>

    <footer class="mentions">
        Merci de votre confiance — Rinah Multi-Services
    </footer>

</body>
</html>
