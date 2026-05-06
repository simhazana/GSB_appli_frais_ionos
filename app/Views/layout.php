<?php /** @var string $title */ ?>
<!doctype html>
<html lang="fr"><head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($title ?? 'App', ENT_QUOTES) ?></title>

  <style>body{font-family:system-ui;margin:2rem}a{color:inherit}</style>
</head><body>
  <main>
    <?php require $viewFile; ?>
  </main>
</body></html>
