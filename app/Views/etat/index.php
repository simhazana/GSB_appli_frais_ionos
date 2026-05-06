<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'États') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif;margin:24px;}
        table{border-collapse:collapse;width:100%;max-width:900px;}
        th,td{border:1px solid #ddd;padding:8px;text-align:left;}
        th{background:#f6f6f6;}
        .topbar{margin-bottom:16px;display:flex;gap:12px;align-items:center;}
        .flash{color:#b30000;margin:8px 0;}
        a.button{display:inline-block;padding:6px 10px;border:1px solid #ccc;border-radius:6px;text-decoration:none;}
        .actions a{margin-right:6px;}
        .actions form { display:inline; margin:0; }
        .actions button { border:1px solid #ccc; border-radius:6px; padding:4px 8px; background:#fff; cursor:pointer; }

    </style>
</head>
<body>
    <div class="topbar">
        <h1 style="margin:0;">Liste des États test</h1>
       <a class="button" href="/index.php/dashboard">Dashboard</a>
	<a class="button" href="/index.php/logout">Se déconnecter</a>
	<a class="button" href="/index.php/fraisForfait">Frais forfait</a>
    </div>

    <a class="button" href="/index.php/etat/create">➕ Ajouter un état</a>

    <?php if (!empty($message)): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (empty($etats)): ?>
        <p>Aucun état trouvé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Libellé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etats as $etat): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)$etat['id']) ?></td>
                        <td><?= htmlspecialchars((string)$etat['libelle']) ?></td>
                        <td class="actions">
                            <a href="/index.php/etat/<?= urlencode($etat['id']) ?>">Voir</a>
							<a href="/index.php/etat/<?= urlencode($etat['id']) ?>/edit">Modifier</a>
                             <form action="/index.php/etat/<?= urlencode($etat['id']) ?>/delete"
     							 method="post"
      							style="display:inline"
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
