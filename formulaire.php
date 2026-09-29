<?php
// ================== formulaire.php ==================
session_start();
$d = $_SESSION['data'] ?? [];   // Q3 : valeurs déjà saisies (retour via MODIFIER)

// Réafficher une valeur texte (ou la case $i d'un tableau)
function v($k, $i = null) {
    global $d;
    $x = $d[$k] ?? '';
    if ($i !== null) $x = is_array($x) ? ($x[$i] ?? '') : '';
    return htmlspecialchars($x);
}
// Cocher un bouton radio / une case à cocher
function coche($k, $val) {
    global $d;
    $x = $d[$k] ?? '';
    $ok = is_array($x) ? in_array($val, $x) : $x === $val;
    return $ok ? 'checked' : '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Fiche de Renseignements</title>
<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    fieldset { margin-bottom: 15px; }
    label { display: inline-block; min-width: 150px; }
    textarea { width: 400px; height: 60px; }
    td { padding: 3px; }
</style>
</head>
<body>

<h2>Fiche de Renseignements</h2>

<form action="recap.php" method="post" enctype="multipart/form-data">

    <!-- ========== Q1 : Renseignements personnels ========== -->
    <fieldset>
        <legend>Renseignements Personnels</legend>
        <label>Nom :</label>     <input type="text"   name="nom"       value="<?= v('nom') ?>" required><br>
        <label>Prénom :</label>  <input type="text"   name="prenom"    value="<?= v('prenom') ?>" required><br>
        <label>Âge :</label>     <input type="number" name="age"       value="<?= v('age') ?>" min="15" max="60"><br>
        <label>Numéro de téléphone :</label>
                                 <input type="tel"    name="telephone" value="<?= v('telephone') ?>"><br>
        <label>Email :</label>   <input type="email"  name="email"     value="<?= v('email') ?>" required>
    </fieldset>

    <!-- ========== Q1 : Renseignements académiques ========== -->
    <fieldset>
        <legend>Renseignements Académiques</legend>

        <b>Vous êtes en :</b>
        <?php foreach (['2AP', 'GSTR', 'GI', 'SCM', 'GC', 'MS'] as $f): ?>
            <input type="radio" name="filiere" value="<?= $f ?>" <?= coche('filiere', $f) ?>> <?= $f ?>
        <?php endforeach; ?>
        <br><br>

        <?php foreach (['1ère année', '2ème année', '3ème année'] as $a): ?>
            <input type="radio" name="annee" value="<?= $a ?>" <?= coche('annee', $a) ?>> <?= $a ?>
        <?php endforeach; ?>
        <br><br>

        <b>Modules suivis cette année :</b>
        <?php foreach (['Pro Av', 'Compilation', 'Réseaux Av', 'Web Avancé', 'POO', 'BD'] as $m): ?>
            <input type="checkbox" name="modules[]" value="<?= $m ?>" <?= coche('modules', $m) ?>> <?= $m ?>
        <?php endforeach; ?>
        <br><br>

        <b>Nombre de projets réalisés cette année :</b>
        <select name="nb_projets">
            <?php for ($n = 1; $n <= 10; $n++): ?>
                <option <?= ($d['nb_projets'] ?? '1') == $n ? 'selected' : '' ?>><?= $n ?></option>
            <?php endfor; ?>
        </select>
    </fieldset>

    <!-- ========== Q2 : Projets et stages ========== -->
    <fieldset>
        <legend>Projets et stages réalisés</legend>
        <table>
            <tr><th>Type</th><th>Date début</th><th>Date fin</th><th>Lieu</th><th>Description</th></tr>
            <?php for ($i = 0; $i < 3; $i++): ?>
            <tr>
                <td>
                    <select name="type[]">
                        <option <?= v('type', $i) === 'Projet' ? 'selected' : '' ?>>Projet</option>
                        <option <?= v('type', $i) === 'Stage'  ? 'selected' : '' ?>>Stage</option>
                    </select>
                </td>
                <td><input type="date" name="debut[]"       value="<?= v('debut', $i) ?>"></td>
                <td><input type="date" name="fin[]"         value="<?= v('fin', $i) ?>"></td>
                <td><input type="text" name="lieu[]"        value="<?= v('lieu', $i) ?>"></td>
                <td><input type="text" name="description[]" value="<?= v('description', $i) ?>" size="35"></td>
            </tr>
            <?php endfor; ?>
        </table>
    </fieldset>

    <!-- ========== Q2 : Centres d'intérêt, compétences, langues ========== -->
    <fieldset>
        <legend>Profil</legend>
        <label>Centres d'intérêt :</label><br>
        <textarea name="interets"><?= v('interets') ?></textarea><br>
        <label>Compétences :</label><br>
        <textarea name="competences"><?= v('competences') ?></textarea><br>
        <label>Langues :</label><br>
        <textarea name="langues"><?= v('langues') ?></textarea>
    </fieldset>

    <!-- ========== Q1 : Remarques + fichier ========== -->
    <fieldset>
        <legend>Vos remarques</legend>
        <textarea name="remarques"><?= v('remarques') ?></textarea><br>
        <input type="file" name="fichier">
        <?php if (!empty($d['fichier'])): ?>
            (fichier actuel : <?= v('fichier') ?>)
        <?php endif; ?>
    </fieldset>

    <input type="submit" value="Envoyer">
    <input type="reset"  value="Effacer">
</form>

</body>
</html>