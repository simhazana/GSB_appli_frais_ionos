<?php $e = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Connexion – GSB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: #f5f0e8; /* Même fond que le dashboard */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #fffdf7;
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #ede5d8;
        }

        h2 {
            color: #3d2b1f;
            font-size: 1.5rem;
            margin-bottom: 24px;
            text-align: center;
        }

        .error-message {
            background: #fdecea;
            color: #b30000;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #e0a0a0;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #7a5c3a;
            font-weight: 600;
            font-size: 0.9rem;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #c8b89a;
            border-radius: 8px;
            background: #fff;
            font-size: 1rem;
            color: #3d2b1f;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus {
            border-color: #7a5c3a;
            box-shadow: 0 0 0 3px rgba(122, 92, 58, 0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            background: #f0e6d3;
            border: 1px solid #c8b89a;
            color: #3d2b1f;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
        }

        button:hover {
            background: #e0d0b8;
        }

        .logo-placeholder {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 1.8rem;
            color: #7a5c3a;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo-placeholder">GSB</div>
        
        <h2>Connexion</h2>

        <?php if (!empty($message)): ?>
            <div class="error-message">
                <?= $e($message) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/index.php/auth">
            <div class="form-group">
                <label for="username">Utilisateur</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <input type="hidden" name="csrf" value="<?= $e($csrf) ?>">
            
            <button type="submit">Se connecter</button>
        </form>
    </div>

</body>
</html>