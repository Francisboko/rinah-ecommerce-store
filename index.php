<?php require 'config/db.php'; require 'includes/header.php'; ?>
<div class="container">
    <h1>Nos Produits</h1>
    <div class="produits">
        <?php
        $stmt = $pdo->query("SELECT * FROM produits");
        while ($p = $stmt->fetch(PDO::FETCH_ASSOC)):
        ?>
        <div class="card">
    <img src="assets/img/<?= htmlspecialchars($p['image']) ?>" data-nom="<?= htmlspecialchars($p['nom']) ?>" class="photo-produit">
    <h3><?= htmlspecialchars($p['nom']) ?></h3>
            <p><?= number_format($p['prix'], 0, ',', ' ') ?> Ar<?= $p['unite'] !== 'pièce' ? '/' . htmlspecialchars($p['unite']) : '' ?></p>
            <a href="panier.php?add=<?= (int)$p['id'] ?>">Ajouter au panier</a>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<!-- Lightbox -->
<div id="lightbox-overlay" class="lightbox-overlay" onclick="fermerLightbox()">
    <span class="lightbox-close" onclick="fermerLightbox()">&times;</span>
    <img id="lightbox-img" class="lightbox-content" src="" alt="">
    <p id="lightbox-caption" class="lightbox-caption"></p>
</div>

<script>
document.querySelectorAll('.photo-produit').forEach(function(img) {
    img.style.cursor = 'zoom-in';
    img.addEventListener('click', function() {
        document.getElementById('lightbox-img').src = this.src;
        document.getElementById('lightbox-caption').textContent = this.dataset.nom;
        document.getElementById('lightbox-overlay').style.display = 'flex';
    });
});

function fermerLightbox() {
    document.getElementById('lightbox-overlay').style.display = 'none';
}
</script>
<?php require 'includes/footer.php'; ?>



