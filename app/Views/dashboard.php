<?php $e = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Tableau de bord – GSB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: #f5f0e8;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .topbar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
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

        a.button.danger {
            background: #fdecea;
            border-color: #e0a0a0;
            color: #b30000;
        }

        a.button.danger:hover { background: #f5c6cb; }

        /* Augmentation de la largeur max pour accueillir 5 cartes à l'aise */
        .welcome, .cards, .container-top {
            max-width: 1100px; 
            margin: 0 auto;
        }

        .welcome {
            margin-bottom: 32px;
            background: #fffdf7;
            border-radius: 16px;
            padding: 24px 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            color: #3d2b1f;
            font-size: 1.1rem;
        }

        .welcome strong { color: #7a5c3a; }

        .cards {
            display: grid;
            /* MODIFICATION ICI : On force 5 colonnes égales */
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
        }

        .card {
            background: #fffdf7;
            border-radius: 16px;
            padding: 24px 10px; /* Réduction du padding latéral pour les petits écrans */
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            text-decoration: none;
            color: #3d2b1f;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            border: 1px solid #ede5d8;
            transition: background 0.2s, transform 0.15s;
        }

        .card:hover {
            background: #f0e6d3;
            transform: translateY(-2px);
        }

        .card .icon { font-size: 2rem; }

        .card .label {
            font-weight: 600;
            font-size: 0.9rem;
            text-align: center;
        }

        /* Optionnel : Retour à 2 ou 3 colonnes sur mobile très étroit */
        @media (max-width: 768px) {
            .cards { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); }
        }
    </style>
</head>
<body>

    <div class="container-top">
        <div class="topbar">
            <h1>Tableau de bord</h1>
            <a class="button danger" href="/index.php/logout">⬅ Se déconnecter</a>
        </div>
    </div>

    <div class="welcome">
        Bienvenue, <strong><?= $e($username) ?></strong> ! Que souhaitez-vous gérer ?
    </div>

    <div class="cards">
        <a class="card" href="/index.php/visiteur">
            <span class="icon">👤</span>
            <span class="label">Visiteurs</span>
        </a>
        <a class="card" href="/index.php/fichefrais">
            <span class="icon">📋</span>
            <span class="label">Fiches Frais</span>
        </a>
        <a class="card" href="/index.php/fraisForfait">
            <span class="icon">💰</span>
            <span class="label">Frais Forfait</span>
        </a>
        <a class="card" href="/index.php/fraisHorsForfait">
            <span class="icon">🧾</span>
            <span class="label">Frais Hors Forfait</span>
        </a>
        <a class="card" href="/index.php/etat">
            <span class="icon">📌</span>
            <span class="label">États</span>
        </a>
    </div>

</body>
</html>