<?php
namespace Core;

abstract class Controller {
    // Affiche une vue via le layout
    protected function render(string $view, array $data = []): void {
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (!is_file($viewFile)) {
            http_response_code(500);
            echo 'Vue introuvable: ' . htmlspecialchars($view, ENT_QUOTES);
            return;
        }
        extract($data, EXTR_SKIP);
        // layout.php doit faire: require $viewFile;
        require __DIR__ . '/../Views/layout.php';
    }

    // Redirection qui respecte le sous-dossier (base path)
    // Si tu es en PHP < 8.1, garde bien : void
  /*  protected function redirect(string $to): void {
        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        $isAbsolute = preg_match('~^https?://~i', $to) === 1;

        if (!$isAbsolute) {
            $to = '/' . ltrim($to, '/');
            if ($base !== '' && $base !== '/') {
                $to = $base . $to;
            }
        }
        header('Location: ' . $to, true, 302);
        exit;
    }*/
  
  // Redirection simple (adaptée quand ton domaine pointe sur /public)
protected function redirect(string $to): void {
    $isAbsolute = preg_match('~^https?://~i', $to) === 1;

    if (!$isAbsolute) {
        // Toujours une URL absolue depuis la racine du domaine
        $to = '/' . ltrim($to, '/');
    }

    header('Location: ' . $to, true, 302);
    exit;
}


    // CSRF helpers
    protected function csrfToken(): string {
        return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
    }
    protected function checkCsrf(?string $t): bool {
        return isset($_SESSION['csrf']) && is_string($t) && hash_equals($_SESSION['csrf'], $t);
    }
}
