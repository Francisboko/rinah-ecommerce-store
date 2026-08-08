<?php
require 'config/db.php';

echo "✅ Connexion réussie à la base de données !<br><br>";

// On teste en récupérant les produits déjà insérés
$stmt = $pdo->query("SELECT * FROM produits");
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Produits trouvés :</h3>";
foreach ($produits as $produit) {
    echo $produit['nom'] . " - " . $produit['prix'] . " Ar<br>";
}
?>