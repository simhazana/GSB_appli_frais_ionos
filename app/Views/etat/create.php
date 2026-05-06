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
    <title><?= htmlspecialchars($title ?? 'Créer un état', ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        .error { color: red; }
        .flash { background: #eef; padding: .5rem 1rem; margin-bottom: 1rem; border: 1px solid #99c; }
        .field { margin-bottom: 1rem; }
        label { display: block; margin-bottom: .3rem; }
    </style>
</head>
<body>

    <h1><?= htmlspecialchars($title ?? 'Créer un état', ENT_QUOTES, 'UTF-8'); ?></h1>

    <?php if (!empty($message)): ?>
        <div class="flash">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

   <form action="/index.php/etat/create" method="post">


        <div class="field">
            <label for="libelle">Libellé</label>
            <input
                type="text"
                name="libelle"
                id="libelle"
                value="<?= htmlspecialchars($old['libelle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                required
            >
            <?php if (!empty($errors['libelle'])): ?>
                <div class="error">
                    <?= htmlspecialchars($errors['libelle'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
        </div>

        <button type="submit">Enregistrer</button>
      <a href="/index.php/etat">Annuler</a>
    </form>

</body>
</html>
