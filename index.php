<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Accueil</title>
</head>
<body>

<h1>ENSA de Tétouan</h1>
<h2>Fiche de renseignements des étudiants</h2>

<ul>
    <li><a href="formulaire.php?nouveau=1">Remplir une nouvelle fiche</a></li>
    <?php if (!empty($_SESSION['data'])): ?>
        <li><a href="formulaire.php">Reprendre ma fiche</a></li>
        <li><a href="recap.php">Voir le récapitulatif</a></li>
    <?php endif; ?>
</ul>

</body>
</html>