<?php

session_start();
if (isset($_GET['nouveau'])) unset($_SESSION['data']);   // nouvelle fiche vide depuis l'accueil
$d = $_SESSION['data'] ?? [];   // Q3 : valeurs déjà saisies (retour via MODIFIER)


function v($k, $i = null) {
    global $d;
    $x = $d[$k] ?? '';
    if ($i !== null) $x = is_array($x) ? ($x[$i] ?? '') : '';
    return htmlspecialchars($x);
}

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

    <fieldset>
        <legend>Renseignements Personnels</legend>
        <label>Nom :</label>     <input type="text"   name="nom"       value="<?= v('nom') ?>" required><br>
        <label>Prénom :</label>  <input type="text"   name="prenom"    value="<?= v('prenom') ?>" required><br>
        <label>Âge :</label>     <input type="number" name="age"       value="<?= v('age') ?>" min="15" max="60"><br>
        <label>Numéro de téléphone :</label>
                                 <input type="tel"    name="telephone" id="telephone" value="<?= v('telephone') ?>" placeholder="06 12 34 56 78">
        <span id="msg-telephone" style="color:red"></span><br>
        <label>Email :</label>   <input type="text" inputmode="email" name="email" id="email" value="<?= v('email') ?>" placeholder="nom@exemple.com" required>
        <span id="msg-email" style="color:red"></span>
    </fieldset>

    
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

    
    <fieldset>
        <legend>Profil</legend>
        <label>Centres d'intérêt :</label><br>
        <textarea name="interets"><?= v('interets') ?></textarea><br>
        <label>Compétences :</label><br>
        <textarea name="competences"><?= v('competences') ?></textarea><br>
        <label>Langues :</label><br>
        <textarea name="langues"><?= v('langues') ?></textarea>
    </fieldset>

    
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
    <button type="button" onclick="location.href='index.php'">Retour à l'accueil</button>
</form>

<script src="validation.js"></script>

</body>
</html>