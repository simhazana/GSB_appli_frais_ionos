<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? 'Créer une fiche frais') ?></title>
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
        input[readonly] { background: #f0e9df; color: #7a5c3a; cursor: not-allowed; }
        .error { color: #b30000; font-size: 0.82rem; margin-top: 4px; }
        .form-actions { display: flex; gap: 12px; margin-top: 24px; }
        button[type="submit"] {
            padding: 10px 24px; border-radius: 8px; border: none;
            background: #7a9e7e; color: white; font-size: 0.95rem;
            cursor: pointer; font-weight: 600; transition: background 0.2s;
        }
        button[type="submit"]:hover { background: #6a8e6e; }
        .hint { font-size: 0.8rem; color: #999; margin-top: 4px; }
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <h1>Créer une fiche frais<br><br>Frais forfait</h1>

        <a class="button" href="/index.php/dashboard">Dashboard</a>
        <a class="button" href="/index.php/fichefrais">Retour</a>
    </div>

    <?php if (!empty($message)): ?>
        <div class="flash"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form action="/index.php/fichefrais/create" method="post">

        <?php
        $isCompta = ($_SESSION['role'] ?? '') === 'Comptable';
        $nomConnecte = trim(($_SESSION['prenom'] ?? '') . ' ' . ($_SESSION['nom'] ?? ''));
        $idConnecte  = $_SESSION['uid'] ?? '';
        ?>

        <!-- Champ visiteur : liste déroulante pour comptable, champ readonly pour visiteur -->
        <div class="field">
            <label for="visiteur">Visiteur *</label>
            <?php if ($isCompta): ?>
                <select name="visiteur" id="visiteur" required>
                    <option value="">-- Choisir un visiteur --</option>
                    <?php foreach ($visiteurs ?? [] as $v): ?>
                        <option value="<?= $v['ID'] ?>"
                            <?= ($old['visiteur'] ?? '') == $v['ID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['NOM'] . ' ' . $v['PRENOM']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <input type="text" value="<?= htmlspecialchars($nomConnecte) ?>" readonly>
                <input type="hidden" name="visiteur" value="<?= htmlspecialchars((string)$idConnecte) ?>">
            <?php endif; ?>
            <?php if (!empty($errors['visiteur'])): ?>
                <div class="error"><?= htmlspecialchars($errors['visiteur']) ?></div>
            <?php endif; ?>
        </div>

            <div class="field">
            <label for="fraisforfait">Frais forfait *</label>
            <select name="fraisforfait" id="fraisforfait" required>
                <option value="">-- Choisir un frais forfait --</option>
                <?php foreach ($fraisForfaits ?? [] as $f): ?>
                    <option value="<?= $f['id'] ?>"
                        <?= ($old['fraisforfait'] ?? '') == $f['ID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f['libelle'] . ' — ' . $f['montant'] . ' €') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['fraishorsforfait'])): ?>
                <div class="error"><?= htmlspecialchars($errors['fraishorsforfait']) ?></div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for=" Quantité"> Quantité *</label>
            <input type="number" name="quantite" id="quantite" min="0"
                   value="<?= htmlspecialchars($old['quantite'] ?? '0') ?>" required>
            <?php if (!empty($errors['quantite'])): ?>
                <div class="error"><?= htmlspecialchars($errors['quantite']) ?></div>
            <?php endif; ?>
        </div>

        <h2>Frais hors forfait</h2>
        <br>
        
        <div class="field">
            <label for="fraishorsforfait">Frais hors forfait *</label>
            <select name="fraishorsforfait" id="fraishorsforfait" required>
                <option value="">-- Choisir un frais hors forfait --</option>
                <?php foreach ($fraisHorsForfaits ?? [] as $f): ?>
                    <option value="<?= $f['id'] ?>"
                        <?= ($old['fraishorsforfait'] ?? '') == $f['ID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f['libelle'] . ' — ' . $f['montant'] . ' €') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['fraishorsforfait'])): ?>
                <div class="error"><?= htmlspecialchars($errors['fraishorsforfait']) ?></div>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="dateModif">Date de modification *</label>
            <input type="date" name="dateModif" id="dateModif"
                   value="<?= htmlspecialchars($old['dateModif'] ?? date('Y-m-d')) ?>" required>
            <?php if (!empty($errors['dateModif'])): ?>
                <div class="error"><?= htmlspecialchars($errors['dateModif']) ?></div>
            <?php endif; ?>
        </div>
        
        <div class="field">
            <label for="nbrJustificatifs">Nombre de justificatifs *</label>
            <input type="number" name="nbrJustificatifs" id="nbrJustificatifs" min="0"
                   value="<?= htmlspecialchars($old['nbrJustificatifs'] ?? '0') ?>" required>
            <?php if (!empty($errors['nbrJustificatifs'])): ?>
                <div class="error"><?= htmlspecialchars($errors['nbrJustificatifs']) ?></div>
            <?php endif; ?>
        </div>


        <div class="form-actions">
            <button type="submit">Enregistrer</button>
            <a class="button" href="/index.php/fichefrais">Annuler</a>
        </div>

    </form>
</div>
</body>
</html>