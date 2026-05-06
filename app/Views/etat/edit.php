<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title, ENT_QUOTES); ?></title>

    <style>
        .error { color: red; }
        .flash { background: #eef; padding: .5rem 1rem; margin-bottom: 1rem; border: 1px solid #99c; }
        .field { margin-bottom: 1rem; }
        label { display: block; margin-bottom: .3rem; }
    </style>
</head>
<body>

<h1><?= htmlspecialchars($title); ?></h1>

<?php if (!empty($message)): ?>
    <div class="flash"><?= htmlspecialchars($message); ?></div>
<?php endif; ?>

<form action="../../etat/<?= $etat['id'] ?>/edit" method="post">

    <div class="field">
        <label for="libelle">Libellé</label>
        <input type="text" name="libelle" id="libelle"
               value="<?= htmlspecialchars($old['libelle'] ?? '', ENT_QUOTES); ?>" required>

        <?php if (!empty($errors['libelle'])): ?>
            <div class="error"><?= htmlspecialchars($errors['libelle']); ?></div>
        <?php endif; ?>
    </div>

    <button type="submit">Enregistrer</button>
    <a href="../etat/<?= $etat['id'] ?>">Annuler</a>
</form>

</body>
</html>
