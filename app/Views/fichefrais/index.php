<?php
$role     = $_SESSION['role'] ?? '';
$isCompta = $role === 'Comptable';
$nomConnecte = trim(($_SESSION['prenom'] ?? '') . ' ' . ($_SESSION['nom'] ?? ''));
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'Fiches Frais') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: #f5f0e8; min-height: 100vh; padding: 40px 20px;
        }
        .container { max-width: 1100px; margin: 0 auto; background: #fffdf7; border-radius: 16px; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
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

        /* Liste déroulante comptable */
        .action-select { padding: 4px 8px; border-radius: 6px; border: 1px solid #c8b89a; background: #f0e6d3; color: #3d2b1f; font-size: 0.85rem; cursor: pointer; }
        .action-select:hover { background: #e0d0b8; }

        /* Card vide */
        .empty-card {
            margin-top: 24px; background: #fffdf7; border: 1px solid #ede5d8;
            border-radius: 16px; padding: 32px; text-align: center;
            color: #7a5c3a; font-size: 1.05rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .empty-card .icon { font-size: 2rem; display: block; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="topbar">
        <h1 style="margin:0;">
            <?= $isCompta ? 'Fiches Frais — tous les visiteurs' : 'Mes fiches frais' ?>
        </h1>
        <a class="button" href="./dashboard">Dashboard</a>
        <a class="button" href="./logout">Se déconnecter</a>
    </div>

    <a class="button add" href="/index.php/fichefrais/create">➕ Ajouter une fiche frais</a>

    <?php if (!empty($message)): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (empty($ficheFrais)): ?>
        <?php if (!$isCompta): ?>
            <div class="empty-card">
                <span class="icon">📭</span>
                Vous n'avez pas de fiches frais
            </div>
        <?php else: ?>
            <p style="color:#888;font-style:italic;margin-top:16px;">Aucune fiche frais trouvée.</p>
        <?php endif; ?>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Visiteur</th>
                    <th>Mois</th>
                    <th>Montant Validé</th>
                    <th>Etat</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ficheFrais as $fiche): ?>
                    <tr>
                        <td>
                            <?php if ($isCompta): ?>
                                <?= htmlspecialchars((string)($fiche['nomVisiteur'] ?? $fiche['IDvisiteur'])) ?>
                            <?php else: ?>
                                <?= htmlspecialchars($nomConnecte) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars((string)$fiche['mois']) ?></td>
                        <td><?= htmlspecialchars((string)$fiche['montantValide']) ?></td>
                        <td><?= htmlspecialchars((string)$fiche['libelleEtat']) ?></td>

                        <td class="actions">
    <?php
        $etatsVerrouilles = ['Validé', 'Remboursé', 'Clôturé', 'Refusé'];
        $estVerrouille    = !$isCompta && in_array($fiche['libelleEtat'], $etatsVerrouilles);
        $peutSupprimer    = $isCompta || ($fiche['libelleEtat'] === 'Créé');
    ?>

    <a href="/index.php/fichefrais/<?= urlencode($fiche['IDvisiteur']) ?>/<?= urlencode($fiche['mois']) ?>">Voir</a>

    <?php if (!$estVerrouille): ?>
        <a href="/index.php/fichefrais/<?= urlencode($fiche['IDvisiteur']) ?>/<?= urlencode($fiche['mois']) ?>/edit">Modifier</a>

        <?php if ($isCompta): ?>
            <select class="action-select"
                data-id="<?= urlencode($fiche['IDvisiteur']) ?>"
                data-mois="<?= urlencode($fiche['mois']) ?>"
                onchange="changerEtat(this)">
                <option value="">-- Changer état --</option>
                <?php foreach ($etats ?? [] as $etat): ?>
                    <option value="setetat-<?= (int)$etat['id'] ?>">
                        <?= htmlspecialchars($etat['libelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($peutSupprimer): ?>
        <form action="/index.php/fichefrais/<?= urlencode($fiche['IDvisiteur']) ?>/<?= urlencode($fiche['mois']) ?>/delete"
              method="post"
              style="display:inline"
              onsubmit="return confirm('Supprimer cette fiche frais ? Cette action est définitive.');">
            <button type="submit">Supprimer</button>
        </form>
    <?php endif; ?>
</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <script>
    function changerEtat(select) {
        const action = select.value;
        if (!action) return;

        const id   = select.dataset.id;
        const mois = select.dataset.mois;

        if (action === 'delete') {
            if (!confirm('Supprimer cette fiche frais ? Cette action est définitive.')) {
                select.value = '';
                return;
            }
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/index.php/fichefrais/' + id + '/' + mois + '/' + action;
        document.body.appendChild(form);
        form.submit();
    }
    </script>
</body>
</html>