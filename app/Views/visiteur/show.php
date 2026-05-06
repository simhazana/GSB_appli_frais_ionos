<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'Visiteur') ?></title>
    <style>
        body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif;margin:24px;}
        .card{border:1px solid #ddd;padding:16px;border-radius:8px;max-width:400px;}
        a.button{display:inline-block;margin-top:12px;padding:6px 10px;border:1px solid #ccc;border-radius:6px;text-decoration:none;}
        .flash{color:#b30000;margin-bottom:10px;}
    </style>
</head>
<body>
    <h1>Détail des visiteurs</h1>

    <?php if (!empty($message)): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (!empty($visiteur)): ?>
        <div class="card">
            <p><strong>ID :</strong> <?= htmlspecialchars($visiteur['ID']) ?></p>
        <p><strong>Nom :</strong> <?= htmlspecialchars($visiteur['NOM']) ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($visiteur['PRENOM']) ?></p>
        <p><strong>Adresse :</strong> <?= htmlspecialchars($visiteur['ADRESSE']) ?></p>
        <p><strong>Ville :</strong> <?= htmlspecialchars($visiteur['VILLE']) ?></p>
        <p><strong>Code Postal :</strong> <?= htmlspecialchars($visiteur['CP']) ?></p>
        <p><strong>Date embauche :</strong> <?= htmlspecialchars($visiteur['DATE_EMBAUCHE']) ?></p>
        <p><strong>Login :</strong> <?= htmlspecialchars($visiteur['LOGIN']) ?></p>
        <p><strong>Mot de passe :</strong> <?= htmlspecialchars($visiteur['MDP']) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($visiteur['role']) ?></p>

        </div>
        <a class="button" href="/index.php/visiteur">⬅ Retour à la liste</a>
    <?php else: ?>
        <p>Visiteur introuvable.</p>
        <a class="button" href="/index.php/visiteur">Retour à la liste</a>
    <?php endif; ?>
</body>
</html>