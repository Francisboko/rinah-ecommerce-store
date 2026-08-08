<?php
session_start();
require 'config/db.php';

// Ajouter au panier
if (isset($_GET['add'])) {
    $id = (int) $_GET['add'];
    $_SESSION['panier'][$id] = ($_SESSION['panier'][$id] ?? 0) + 1;
    header("Location: panier.php");
    exit;
}

// Augmenter la quantité
if (isset($_GET['inc'])) {
    $id = (int) $_GET['inc'];
    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]++;
    }
    header("Location: panier.php");
    exit;
}

// Diminuer la quantité (supprime si ça atteint 0)
if (isset($_GET['dec'])) {
    $id = (int) $_GET['dec'];
    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]--;
        if ($_SESSION['panier'][$id] <= 0) {
            unset($_SESSION['panier'][$id]);
        }
    }
    header("Location: panier.php");
    exit;
}

// Supprimer complètement
if (isset($_GET['del'])) {
    $id = (int) $_GET['del'];
    unset($_SESSION['panier'][$id]);
    header("Location: panier.php");
    exit;
}

require 'includes/header.php';
?>
<div class="container">
<h1>Votre Panier</h1>
<table>
<?php
$total = 0;
if (!empty($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $id => $qte) {
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
        $stmt->execute([$id]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$p) continue;

        $sous_total = $p['prix'] * $qte;
        $total += $sous_total;
        echo "<tr>
            <td>" . htmlspecialchars($p['nom']) . "</td>
            <td>
                <div class='counter'>
                    <a href='?dec=$id' class='counter-btn'>−</a>
                    <span class='counter-val'>$qte</span>
                    <a href='?inc=$id' class='counter-btn'>+</a>
                </div>
            </td>
            <td>" . number_format($sous_total, 0, ',', ' ') . " Ar</td>
            <td><a href='?del=$id' class='counter-del'>✕</a></td>
        </tr>";
    }
}
?>
</table>
<h3>Total: <?= number_format($total, 0, ',', ' ') ?> Ar</h3>
<a href="checkout.php">Commander</a>
</div>
<?php require 'includes/footer.php'; ?>