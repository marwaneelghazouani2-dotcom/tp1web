<?php
// ================== recap.php ==================
session_start();
$message = '';

// ---------- Q3 : bouton VALIDER -> enregistrement dans un fichier texte ----------
if (isset($_POST['valider']) && !empty($_SESSION['data'])) {
    $d = $_SESSION['data'];
    $txt = "===== Étudiant enregistré le " . date('Y-m-d H:i') . " =====\n";

    foreach ($d as $k => $val) {
        if (in_array($k, ['type', 'debut', 'fin', 'lieu', 'description'])) continue; // traités plus bas
        if (is_array($val)) $val = implode(', ', $val);
        $txt .= "$k : $val\n";
    }
    // Projets / stages, une ligne chacun
    foreach ($d['type'] ?? [] as $i => $type) {
        if (trim($d['description'][$i] ?? '') === '' && trim($d['lieu'][$i] ?? '') === '') continue;
        $txt .= "$type : du {$d['debut'][$i]} au {$d['fin'][$i]} à {$d['lieu'][$i]} - {$d['description'][$i]}\n";
    }

    file_put_contents('etudiants.txt', $txt . "\n", FILE_APPEND);
    $message = "Informations enregistrées dans etudiants.txt";
}

// ---------- Q1 : traitement du formulaire (clic sur Envoyer) ----------
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;

    // Fichier joint
    if (!empty($_FILES['fichier']['name']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
        if (!is_dir('uploads')) mkdir('uploads');
        $nomFichier = basename($_FILES['fichier']['name']);
        move_uploaded_file($_FILES['fichier']['tmp_name'], "uploads/$nomFichier");
        $data['fichier'] = $nomFichier;
    } else {
        $data['fichier'] = $_SESSION['data']['fichier'] ?? '';   // garder l'ancien
    }

    $_SESSION['data'] = $data;   // Q3 : sert à pré-remplir formulaire.php
}

$d = $_SESSION['data'] ?? null;
if (!$d) { header('Location: formulaire.php'); exit; }

function e($x) {
    if (is_array($x)) $x = implode(', ', $x);
    return htmlspecialchars($x ?? '');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Récapitulatif</title>
<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { border-collapse: collapse; margin-bottom: 15px; }
    td, th { border: 1px solid #999; padding: 5px 10px; text-align: left; }
    .ok { color: green; font-weight: bold; }
</style>
</head>
<body>

<h2>Récapitulatif des informations saisies</h2>

<?php if ($message): ?><p class="ok"><?= $message ?></p><?php endif; ?>

<!-- Q1 : récapitulatif -->
<table>
    <tr><th>Nom</th>        <td><?= e($d['nom'] ?? '') ?></td></tr>
    <tr><th>Prénom</th>     <td><?= e($d['prenom'] ?? '') ?></td></tr>
    <tr><th>Âge</th>        <td><?= e($d['age'] ?? '') ?></td></tr>
    <tr><th>Téléphone</th>  <td><?= e($d['telephone'] ?? '') ?></td></tr>
    <tr><th>Email</th>      <td><?= e($d['email'] ?? '') ?></td></tr>
    <tr><th>Filière</th>    <td><?= e($d['filiere'] ?? '') ?> — <?= e($d['annee'] ?? '') ?></td></tr>
    <tr><th>Modules</th>    <td><?= e($d['modules'] ?? []) ?></td></tr>
    <tr><th>Nb de projets</th><td><?= e($d['nb_projets'] ?? '') ?></td></tr>
    <tr><th>Remarques</th>  <td><?= nl2br(e($d['remarques'] ?? '')) ?></td></tr>
    <tr><th>Fichier</th>    <td><?= e($d['fichier'] ?? '') ?: 'Aucun' ?></td></tr>
</table>

<!-- Q2 : projets / stages -->
<h3>Projets et stages</h3>
<table>
    <tr><th>Type</th><th>Début</th><th>Fin</th><th>Lieu</th><th>Description</th></tr>
    <?php foreach ($d['type'] ?? [] as $i => $type): ?>
        <?php if (trim($d['description'][$i] ?? '') === '' && trim($d['lieu'][$i] ?? '') === '') continue; ?>
        <tr>
            <td><?= e($type) ?></td>
            <td><?= e($d['debut'][$i]) ?></td>
            <td><?= e($d['fin'][$i]) ?></td>
            <td><?= e($d['lieu'][$i]) ?></td>
            <td><?= e($d['description'][$i]) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Q2 : profil -->
<table>
    <tr><th>Centres d'intérêt</th><td><?= nl2br(e($d['interets'] ?? '')) ?></td></tr>
    <tr><th>Compétences</th>      <td><?= nl2br(e($d['competences'] ?? '')) ?></td></tr>
    <tr><th>Langues</th>          <td><?= nl2br(e($d['langues'] ?? '')) ?></td></tr>
</table>

<!-- Q3 : boutons VALIDER et MODIFIER -->
<form method="post" style="display:inline">
    <button type="submit" name="valider">VALIDER</button>
</form>
<form action="formulaire.php" method="get" style="display:inline">
    <button type="submit">MODIFIER</button>
</form>

</body>
</html>