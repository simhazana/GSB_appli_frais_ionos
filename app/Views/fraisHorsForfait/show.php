<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'Détail du frais hors forfait') ?></title>
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
        .card {
            background: #fdf8f2; border: 1px solid #c8b89a;
            border-radius: 12px; padding: 24px; display: flex; flex-direction: column; gap: 14px;
        }
        .field-row {
            display: flex; align-items: baseline; gap: 12px;
            padding-bottom: 14px; border-bottom: 1px solid #ede5d8;
        }
        .field-row:last-child { border-bottom: none; padding-bottom: 0; }
        .field-label {
            font-weight: 600; color: #7a5c3a; font-size: 0.85rem;
            text-transform: uppercase; letter-spacing: 0.04em;
            min-width: 140px; flex-shrink: 0;
        }
        .field-value { color: #3d2b1f; font-size: 0.95rem; }
        .badge {
            display: inline-block; padding: 3px 10px; border-radius: 20px;
            background: #e8f5e9; color: #2e7d32; font-size: 0.85rem; font-weight: 600;
        }
        .empty { color: #999; font-style: italic; text-align: center; padding: 24px 0; }
        .actions { display: flex; gap: 12px; margin-top: 24px; flex-wrap: wrap; }
        a.button-edit {
            display: inline-block; padding: 10px 24px; border-radius: 8px;
            text-decoration: none; background: #7a9e7e; color: white;
            font-size: 0.95rem; font-weight: 600; transition: background 0.2s;
        }
        a.button-edit:hover { background: #6a8e6e; }
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <h1>Détail — Frais hors forfait</h1>
        <a class="button" href="/index.php/dashboard">Dashboard</a>
        <a class="button" href="/index.php/fraisHorsForfait">Retour</a>
    </div>

    <?php if (!empty($message)): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (!empty($fraisHorsForfait)): ?>
        <div class="card">
            <div class="field-row">
                <span class="field-label">ID</span>
                <span class="field-value"><?= htmlspecialchars($fraisHorsForfait['id']) ?></span>
            </div>
            <div class="field-row">
                <span class="field-label">Libellé</span>
                <span class="field-value"><?= htmlspecialchars($fraisHorsForfait['libelle']) ?></span>
            </div>
            <div class="field-row">
                <span class="field-label">Montant</span>
                <span class="field-value">
                    <span class="badge"><?= htmlspecialchars($fraisHorsForfait['montant']) ?> €</span>
                </span>
            </div>
            <div class="field-row">
                <span class="field-label">Date</span>
                <span class="field-value"><?= htmlspecialchars($fraisHorsForfait['date']) ?></span>
            </div>
        </div>

        <div class="actions">
            <a class="button-edit" href="/index.php/fraisHorsForfait/<?= htmlspecialchars($fraisHorsForfait['id']) ?>/edit">Modifier</a>
            <a class="button" href="/index.php/fraisHorsForfait">Retour à la liste</a>
        </div>

    <?php else: ?>
        <p class="empty">Frais hors forfait introuvable.</p>
        <div class="actions">
            <a class="button" href="/index.php/fraisHorsForfait">Retour à la liste</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>