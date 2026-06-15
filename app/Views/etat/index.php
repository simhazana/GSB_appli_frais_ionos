<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'États') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: #f5f0e8; min-height: 100vh; padding: 40px 20px;
        }
        .topbar { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
        .topbar h1 { flex: 1; font-size: 1.5rem; color: #3d2b1f; }
        a.button, button.button {
            display: inline-block; padding: 8px 14px; border: 1px solid #c8b89a;
            border-radius: 8px; text-decoration: none; background: #f0e6d3;
            color: #3d2b1f; font-size: 0.9rem; cursor: pointer; transition: background 0.2s;
        }
        a.button:hover, button.button:hover { background: #e0d0b8; }
        a.button.add { background: #7a9e7e; color: white; border-color: #6a8e6e; margin-bottom: 20px; }
        a.button.add:hover { background: #6a8e6e; }
        .flash { background: #fdecea; color: #b30000; border: 1px solid #f5c6cb; border-radius: 8px; padding: 10px 16px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: separate; border-spacing: 0; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        thead tr { background: #e8ddd0; }
        th { padding: 12px 16px; text-align: left; color: #3d2b1f; font-weight: 600; font-size: 0.9rem; }
        td { padding: 12px 16px; border-top: 1px solid #ede5d8; color: #444; font-size: 0.9rem; }
        tbody tr:hover { background: #fdf7f0; }
        .actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .actions a { padding: 4px 10px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; border: 1px solid #c8b89a; background: #f0e6d3; color: #3d2b1f; }
        .actions a:hover { background: #e0d0b8; }
        .actions form { margin: 0; }
        .actions button { padding: 4px 10px; border-radius: 6px; font-size: 0.85rem; border: 1px solid #e0a0a0; background: #fdecea; color: #b30000; cursor: pointer; }
        .actions button:hover { background: #f5c6cb; }
    </style>
</head>
<body>
    <div class="topbar">
        <h1 style="margin:0;">Liste des États</h1>
        <a class="button" href="./dashboard">Dashboard</a>
        <a class="button" href="./logout">Se déconnecter</a>
    </div>

    <a class="button add" href="/index.php/etat/create">➕ Ajouter un état</a>

    <?php if (!empty($message)): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php
    // Exclure les IDs 8, 9, 11
    $exclus = [8, 9, 11];
    $etatsAffiches = array_filter($etats ?? [], function($e) use ($exclus) {
        return !in_array((int)$e['id'], $exclus);
    });
    ?>

    <?php if (empty($etatsAffiches)): ?>
        <p style="color:#888;font-style:italic;margin-top:16px;">Aucun état trouvé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    
                    <th>Libellé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etatsAffiches as $etat): ?>
                    <tr>
                        
                        <td><?= htmlspecialchars((string)$etat['libelle']) ?></td>
                        <td class="actions">
                            <a href="/index.php/etat/<?= urlencode($etat['id']) ?>/edit">Modifier</a>
                            <form action="/index.php/etat/<?= urlencode($etat['id']) ?>/delete"
                                  method="post" style="display:inline"
                                  onsubmit="return confirm('Supprimer cet état ? Cette action est définitive.');">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>