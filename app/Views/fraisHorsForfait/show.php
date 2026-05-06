<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'Frais frofait') ?></title>
    <style>
        body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif;margin:24px;}
        .card{border:1px solid #ddd;padding:16px;border-radius:8px;max-width:400px;}
        a.button{display:inline-block;margin-top:12px;padding:6px 10px;border:1px solid #ccc;border-radius:6px;text-decoration:none;}
        .flash{color:#b30000;margin-bottom:10px;}
    </style>
</head>
<body>
    <h1>Détail du frais forfait</h1>

    <?php if (!empty($message)): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (!empty($fraisHorsForfait)): ?>
        <div class="card">
            <p><strong>ID :</strong> <?= htmlspecialchars($fraisHorsForfait['id']) ?></p>
            <p><strong>Libellé :</strong> <?= htmlspecialchars($fraisHorsForfait['libelle']) ?></p>
            <p><strong>Montant :</strong> <?= htmlspecialchars($fraisHorsForfait['montant']) ?></p>
            <p><strong>Date :</strong> <?= htmlspecialchars($fraisHorsForfait['date']) ?></p>
        </div>
        <a class="button" href="/index.php/fraisHorsForfait">⬅ Retour à la liste</a>
    <?php else: ?>
        <p>Frais forfais introuvable.</p>
        <a class="button" href="/index.php/fraisHorsForfait">Retour à la liste</a>
    <?php endif; ?>
</body>
</html>
