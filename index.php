<?php
// ================== index.php (page d'accueil) ==================
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Accueil - ENSA Tétouan</title>
<style>
    body { font-family: Arial, sans-serif; margin: 40px; text-align: center; }
    a.btn { display: inline-block; margin: 10px; padding: 10px 20px;
            background: #2a5db0; color: white; text-decoration: none; border-radius: 4px; }
    a.btn:hover { background: #1d4480; }
</style>
</head>
<body>

<h1>ENSA de Tétouan</h1>
<h2>Fiche de renseignements des étudiants</h2>

<a class="btn" href="formulaire.php?nouveau=1">Remplir une nouvelle fiche</a>

<?php if (!empty($_SESSION['data'])): ?>
    <a class="btn" href="formulaire.php">Reprendre ma fiche</a>
    <a class="btn" href="recap.php">Voir le récapitulatif</a>
<?php endif; ?>

</body>
</html>