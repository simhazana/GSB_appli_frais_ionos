<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'Modifier la fiche frais') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: #f5f0e8;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fffdf7;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .topbar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .topbar h1 {
            flex: 1;
            font-size: 1.5rem;
            color: #3d2b1f;
        }

        a.button {
            display: inline-block;
            padding: 8px 14px;
            border: 1px solid #c8b89a;
            border-radius: 8px;
            text-decoration: none;
            background: #f0e6d3;
            color: #3d2b1f;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        a.button:hover { background: #e0d0b8; }

        .flash {
            background: #fdecea;
            color: #b30000;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 10px 16px;
            margin-bottom: 16px;
        }

        .card {
            background: #fffdf7;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            margin-bottom: 24px;
        }

        .card-header {
            background: #e8ddd0;
            padding: 12px 20px;
            font-weight: 700;
            color: #3d2b1f;
            font-size: 0.95rem;
        }

        .field {
            padding: 14px 20px;
            border-bottom: 1px solid #ede5d8;
        }

        .field:last-child { border-bottom: none; }

        label {
            display: block;
            font-weight: 600;
            color: #3d2b1f;
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        input, select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #c8b89a;
            border-radius: 8px;
            font-size: 0.9rem;
            background: #fdf8f2;
            color: #3d2b1f;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #7a9e7e;
            background: #fff;
        }

        .error {
            color: #b30000;
            font-size: 0.82rem;
            margin-top: 4px;
        }

        .actions {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 8px;
        }

        button.save {
            padding: 10px 20px;
            background: #7a9e7e;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        button.save:hover { background: #6a8e6e; }
    </style>
</head>
<body>
    <div class="container">

        <div class="topbar">
            <h1>Modification de la fiche frais</h1>
            <a class="button" href="/index.php/fichefrais">⬅ Retour à la liste</a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="flash"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

<form action="/index.php/fichefrais/<?= htmlspecialchars($ficheFrais['IDvisiteur']) ?>/<?= htmlspecialchars($ficheFrais['mois']) ?>/edit" method="post">
            <div class="card">
                <div class="card-header">Informations de la fiche</div>

                <div class="field">
                    <label>ID Visiteur</label>
                    <input type="text" value="<?= htmlspecialchars($ficheFrais['IDvisiteur']) ?>" disabled>
                </div>

                <div class="field">
                    <label>Mois</label>
                    <input type="text" value="<?= htmlspecialchars($ficheFrais['mois']) ?>" disabled>
                </div>

                <div class="field">
                    <label for="nbrJustificatifs">Nombre de justificatifs</label>
                    <input type="number" name="nbrJustificatifs" id="nbrJustificatifs"
                           value="<?= htmlspecialchars($old['nbrJustificatifs'] ?? $ficheFrais['nbrJustificatifs']) ?>">
                    <?php if (!empty($errors['nbrJustificatifs'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['nbrJustificatifs']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="montantValide">Montant validé (€)</label>
                    <input type="number" step="0.01" name="montantValide" id="montantValide"
                           value="<?= htmlspecialchars($old['montantValide'] ?? $ficheFrais['montantValide']) ?>">
                    <?php if (!empty($errors['montantValide'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['montantValide']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="dateModif">Date de modification</label>
                    <input type="date" name="dateModif" id="dateModif"
                           value="<?= htmlspecialchars($old['dateModif'] ?? $ficheFrais['dateModif']) ?>">
                    <?php if (!empty($errors['dateModif'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['dateModif']) ?></div>
                    <?php endif; ?>
                </div>

            </div>

            <div class="actions">
                <button type="submit" class="save">💾 Enregistrer</button>
                <a class="button" href="/index.php/fichefrais">Annuler</a>
            </div>

        </form>

    </div>
</body>
</html>