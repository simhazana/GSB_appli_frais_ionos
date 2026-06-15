<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'Modifier un état') ?></title>
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
        input {
            width: 100%; padding: 10px 12px; border: 1px solid #c8b89a;
            border-radius: 8px; font-size: 0.95rem; background: #fdf8f2;
            color: #3d2b1f; transition: border-color 0.2s;
        }
        input:focus { outline: none; border-color: #7a5c3a; }
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
        <h1>Modifier un état</h1>
        <a class="button" href="../../etat">⬅ Retour à la liste</a>
    </div>

    <?php if (!empty($message)): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form action="../../etat/<?= $etat['id'] ?>/edit" method="post">

        <div class="field">
            <label for="libelle">Libellé *</label>
            <input type="text" name="libelle" id="libelle"
                   value="<?= htmlspecialchars($old['libelle'] ?? '', ENT_QUOTES) ?>" required>
            <?php if (!empty($errors['libelle'])): ?>
                <div class="error"><?= htmlspecialchars($errors['libelle']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit">Enregistrer</button>
        </div>

    </form>
</div>
</body>
</html>