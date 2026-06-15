<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'Créer un visiteur') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: #f5f0e8; min-height: 100vh; padding: 40px 20px;
        }
        .container {
            max-width: 600px; margin: 0 auto; background: #fffdf7;
            border-radius: 16px; padding: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .topbar { display: flex; align-items: center; gap: 12px; margin-bottom: 28px; flex-wrap: wrap; }
        .topbar h1 { flex: 1; font-size: 1.5rem; color: #3d2b1f; }
        a.button {
            display: inline-block; padding: 8px 14px; border: 1px solid #c8b89a;
            border-radius: 8px; text-decoration: none; background: #f0e6d3;
            color: #3d2b1f; font-size: 0.9rem; cursor: pointer; transition: background 0.2s;
        }
        a.button:hover { background: #e0d0b8; }
        .flash { background: #fdecea; color: #b30000; border: 1px solid #f5c6cb; border-radius: 8px; padding: 10px 16px; margin-bottom: 20px; }
        .field { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; color: #3d2b1f; font-size: 0.9rem; }
        input, select {
            width: 100%; padding: 10px 12px; border: 1px solid #c8b89a;
            border-radius: 8px; font-size: 0.95rem; background: #fdf8f2;
            color: #3d2b1f; transition: border-color 0.2s;
        }
        input:focus, select:focus { outline: none; border-color: #7a5c3a; }
        .error { color: #b30000; font-size: 0.82rem; margin-top: 4px; }
        .form-actions { display: flex; gap: 12px; margin-top: 24px; }
        button[type="submit"] {
            padding: 10px 24px; border-radius: 8px; border: none;
            background: #7a9e7e; color: white; font-size: 0.95rem;
            cursor: pointer; font-weight: 600; transition: background 0.2s;
        }
        button[type="submit"]:hover { background: #6a8e6e; }
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <h1><?= ($inscription ?? false) ? 'Inscription' : 'Créer un comptable' ?></h1>
        <?php if (!($inscription ?? false)): ?>
        <a class="button" href="../dashboard">Dashboard</a>
        <a class="button" href="../visiteur">Retour</a>
        <?php endif; ?>
    </div>

    <?php if (!empty($message)): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form action="<?= ($inscription ?? false) ? './inscription' : '../visiteur/create' ?>" method="post">


        <div class="field">
            <label for="nom">Nom *</label>
            <input type="text" name="nom" id="nom"
                   value="<?= htmlspecialchars($old['nom'] ?? '') ?>" required>
            <?php if (!empty($errors['nom'])): ?>
                <div class="error"><?= htmlspecialchars($errors['nom']) ?></div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="prenom">Prénom *</label>
            <input type="text" name="prenom" id="prenom"
                   value="<?= htmlspecialchars($old['prenom'] ?? '') ?>" required>
            <?php if (!empty($errors['prenom'])): ?>
                <div class="error"><?= htmlspecialchars($errors['prenom']) ?></div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="adresse">Adresse *</label>
            <input type="text" name="adresse" id="adresse"
                   value="<?= htmlspecialchars($old['adresse'] ?? '') ?>" required>
            <?php if (!empty($errors['adresse'])): ?>
                <div class="error"><?= htmlspecialchars($errors['adresse']) ?></div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="ville">Ville *</label>
            <input type="text" name="ville" id="ville"
                   value="<?= htmlspecialchars($old['ville'] ?? '') ?>" required>
            <?php if (!empty($errors['ville'])): ?>
                <div class="error"><?= htmlspecialchars($errors['ville']) ?></div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="cp">Code Postal *</label>
            <input type="text" name="cp" id="cp"
                   value="<?= htmlspecialchars($old['cp'] ?? '') ?>"
                   pattern="[0-9]{5}" title="Code postal à 5 chiffres" required>
            <?php if (!empty($errors['cp'])): ?>
                <div class="error"><?= htmlspecialchars($errors['cp']) ?></div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="date_embauche">Date d'embauche *</label>
            <input type="date" name="date_embauche" id="date_embauche"
                   value="<?= htmlspecialchars($old['date_embauche'] ?? '') ?>" required>
            <?php if (!empty($errors['date_embauche'])): ?>
                <div class="error"><?= htmlspecialchars($errors['date_embauche']) ?></div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="login">Login *</label>
            <input type="text" name="login" id="login"
                   value="<?= htmlspecialchars($old['login'] ?? '') ?>" required>
            <?php if (!empty($errors['login'])): ?>
                <div class="error"><?= htmlspecialchars($errors['login']) ?></div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="mdp">Mot de passe *</label>
            <input type="password" name="mdp" id="mdp" required>
            <?php if (!empty($errors['mdp'])): ?>
                <div class="error"><?= htmlspecialchars($errors['mdp']) ?></div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="role">Rôle *</label>
            <select name="role" id="role" required>
                <option value="">-- Choisir un rôle --</option>
                <option value="Visiteur" <?= ($old['role'] ?? '') === 'Visiteur' ? 'selected' : '' ?>>Visiteur</option>
                <option value="Comptable" <?= ($old['role'] ?? '') === 'Comptable' ? 'selected' : '' ?>>Comptable</option>
            </select>
            <?php if (!empty($errors['role'])): ?>
                <div class="error"><?= htmlspecialchars($errors['role']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
    <button type="submit">Enregistrer</button>
    <?php if ($inscription ?? false): ?>
        <a class="button" href="./">Retour à la connexion</a>
    <?php else: ?>
        <a class="button" href="../visiteur">Annuler</a>
    <?php endif; ?>
</div>

    </form>
</div>
</body>
</html>