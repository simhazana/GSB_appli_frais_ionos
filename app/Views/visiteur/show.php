<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'Visiteur') ?></title>
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
        .topbar { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
        .topbar h1 { flex: 1; font-size: 1.5rem; color: #3d2b1f; }
        a.button {
            display: inline-block; padding: 8px 14px; border: 1px solid #c8b89a;
            border-radius: 8px; text-decoration: none; background: #f0e6d3;
            color: #3d2b1f; font-size: 0.9rem; cursor: pointer; transition: background 0.2s;
        }
        a.button:hover { background: #e0d0b8; }
        .flash { background: #fdecea; color: #b30000; border: 1px solid #f5c6cb; border-radius: 8px; padding: 10px 16px; margin-bottom: 16px; }
        .card { background: #fffdf7; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 24px; }
        .card-header { background: #e8ddd0; padding: 12px 20px; font-weight: 700; color: #3d2b1f; font-size: 0.95rem; }
        .card-row { display: flex; justify-content: space-between; align-items: center; padding: 14px 20px; border-bottom: 1px solid #ede5d8; }
        .card-row:last-child { border-bottom: none; }
        .card-row:hover { background: #fdf7f0; }
        .card-label { font-weight: 600; color: #3d2b1f; font-size: 0.9rem; }
        .card-value { color: #555; font-size: 0.9rem; text-align: right; }
    </style>
</head>
<body>
<div class="container">

    <div class="topbar">
        <h1>Détail du visiteur</h1>
        <a class="button" href="../visiteur">⬅ Retour à la liste</a>
    </div>

    <?php if (!empty($message)): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (!empty($visiteur)): ?>
        <div class="card">
            <div class="card-header">Informations du visiteur</div>
            <div class="card-row">
                <span class="card-label">ID</span>
                <span class="card-value"><?= htmlspecialchars($visiteur['ID']) ?></span>
            </div>
            <div class="card-row">
                <span class="card-label">Nom</span>
                <span class="card-value"><?= htmlspecialchars($visiteur['NOM']) ?></span>
            </div>
            <div class="card-row">
                <span class="card-label">Prénom</span>
                <span class="card-value"><?= htmlspecialchars($visiteur['PRENOM']) ?></span>
            </div>
            <div class="card-row">
                <span class="card-label">Adresse</span>
                <span class="card-value"><?= htmlspecialchars($visiteur['ADRESSE']) ?></span>
            </div>
            <div class="card-row">
                <span class="card-label">Ville</span>
                <span class="card-value"><?= htmlspecialchars($visiteur['VILLE']) ?></span>
            </div>
            <div class="card-row">
                <span class="card-label">Code postal</span>
                <span class="card-value"><?= htmlspecialchars($visiteur['CP']) ?></span>
            </div>
            <div class="card-row">
                <span class="card-label">Date d'embauche</span>
                <span class="card-value"><?= htmlspecialchars($visiteur['DATE_EMBAUCHE']) ?></span>
            </div>
            <div class="card-row">
                <span class="card-label">Login</span>
                <span class="card-value"><?= htmlspecialchars($visiteur['LOGIN']) ?></span>
            </div>
            <div class="card-row">
                <span class="card-label">Rôle</span>
                <span class="card-value"><?= htmlspecialchars($visiteur['role']) ?></span>
            </div>
        </div>

    <?php else: ?>
        <p style="color:#888; font-style:italic;">Visiteur introuvable.</p>
        <a class="button" href="../visiteur">Retour à la liste</a>
    <?php endif; ?>

</div>
</body>
</html>