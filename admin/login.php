<?php
session_start();
require '../config/db.php';

$erreur = '';

// Identifiants (vous pourrez les changer directement ici plus tard si besoin)
define('ADMIN_USER', 'SDI');
define('ADMIN_PASS', 'sdi_2026');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = trim($_POST['user'] ?? '');
    $pass = trim($_POST['pass'] ?? '');

    if ($user === ADMIN_USER && $pass === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $erreur = "Identifiant ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="/ecommerce/assets/css/style.css">
</head>
<body>
<div class="container" style="max-width:400px; margin-top:80px;">
    <h1>Connexion Admin</h1>
    <?php if ($erreur): ?>
        <p style="color:#EC008C; font-weight:600;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>
    <form method="POST">
        <input name="user" placeholder="Identifiant" required>
        <input name="pass" type="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
</div>
</body>
</html>

