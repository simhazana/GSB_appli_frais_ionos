<?php
// Variables disponibles :
// $title   : titre de la page ("Créer un état")
// $message : message flash éventuel
// $old     : valeurs précédentes du formulaire (['libelle' => '...'])
// $errors  : erreurs de validation (['libelle' => '...'])
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Créer un frais', ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        .error { color: red; }
        .flash { background: #eef; padding: .5rem 1rem; margin-bottom: 1rem; border: 1px solid #99c; }
        .field { margin-bottom: 1rem; }
        label { display: block; margin-bottom: .3rem; }
    </style>
</head>
<body>

    <h1><?= htmlspecialchars($title ?? 'Créer un visiteur', ENT_QUOTES, 'UTF-8'); ?></h1>

    <?php if (!empty($message)): ?>
        <div class="flash">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <form action="/index.php/visiteur/create" method="post">
        <div class="field">
            <label for="nom">Nom</label>
            <input
                type="text"
                name="nom"
                id="nom"
                value="<?= htmlspecialchars($old['nom'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                required
            >
            <?php if (!empty($errors['nom'])): ?>
                <div class="error">
                    <?= htmlspecialchars($errors['nom'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
    

            <label for="prenom">Prenom</label>
            <input
                type="float"
                name="prenom"
                id="prenom"
                value="<?= htmlspecialchars($old['prenom'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                required
            >
            <?php if (!empty($errors['prenom'])): ?>
                <div class="error">
                    <?= htmlspecialchars($errors['prenom'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
        <div class="field">
            <label for="adresse">Adresse *</label>
            <input
                type="text"
                name="adresse"
                id="adresse"
                value="<?= htmlspecialchars($old['adresse'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                required
            >
            <?php if (!empty($errors['adresse'])): ?>
                <div class="error">
                    <?= htmlspecialchars($errors['adresse'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="ville">Ville *</label>
            <input
                type="text"
                name="ville"
                id="ville"
                value="<?= htmlspecialchars($old['ville'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                required
            >
            <?php if (!empty($errors['ville'])): ?>
                <div class="error">
                    <?= htmlspecialchars($errors['ville'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="cp">Code Postal *</label>
            <input
                type="text"
                name="cp"
                id="cp"
                value="<?= htmlspecialchars($old['cp'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                pattern="[0-9]{5}"
                title="Code postal à 5 chiffres"
                required
            >
            <?php if (!empty($errors['cp'])): ?>
                <div class="error">
                    <?= htmlspecialchars($errors['cp'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="date_embauche">Date d'embauche *</label>
            <input
                type="date"
                name="date_embauche"
                id="date_embauche"
                value="<?= htmlspecialchars($old['date_embauche'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                required
            >
            <?php if (!empty($errors['date_embauche'])): ?>
                <div class="error">
                    <?= htmlspecialchars($errors['date_embauche'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="login">Login *</label>
            <input
                type="text"
                name="login"
                id="login"
                value="<?= htmlspecialchars($old['login'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                required
            >
            <?php if (!empty($errors['login'])): ?>
                <div class="error">
                    <?= htmlspecialchars($errors['login'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="mdp">Mot de passe *</label>
            <input
                type="password"
                name="mdp"
                id="mdp"
                required
            >
            <?php if (!empty($errors['mdp'])): ?>
                <div class="error">
                    <?= htmlspecialchars($errors['mdp'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="role"> role *</label>
            <input
                type="text"
                name="role"
                id="role"
                required
            >
            <?php if (!empty($errors['mdp'])): ?>
                <div class="error">
                    <?= htmlspecialchars($errors['mdp'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
        </div>


        <button type="submit">Enregistrer</button>
        <a href="/index.php/visiteur">Annuler</a>
    </form>

</body>
</html>
