<?php
namespace Core;

abstract class Controller {

    protected function render(string $view, array $data = []): void {
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (!is_file($viewFile)) {
            http_response_code(500);
            echo 'Vue introuvable: ' . htmlspecialchars($view, ENT_QUOTES);
            return;
        }
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../Views/layout.php';
    }

    protected function redirect(string $to): void {
        $isAbsolute = preg_match('~^https?://~i', $to) === 1;

        if (!$isAbsolute) {
            $to = '/' . ltrim($to, '/');
        }

        header('Location: ' . $to, true, 302);
        exit;
    }

    protected function csrfToken(): string {
        return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
    }

    protected function checkCsrf(?string $t): bool {
        return isset($_SESSION['csrf']) && is_string($t) && hash_equals($_SESSION['csrf'], $t);
    }

    protected function requireAuth(): void {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php');
    }

    protected function requireComptable(): void {
        $this->requireAuth();
        if (($_SESSION['role'] ?? '') !== 'Comptable') {
            http_response_code(403);
            exit('Accès refusé — réservé aux comptables.');
        }
    }

    protected function requireVisiteur(): void {
        $this->requireAuth();
        if (($_SESSION['role'] ?? '') !== 'visiteur') {
            http_response_code(403);
            exit('Accès refusé — réservé aux visiteurs.');
        }
    }

    protected function isComptable(): bool {
        return ($_SESSION['role'] ?? '') === 'Comptable';
    }

    protected function isVisiteur(): bool {
        return ($_SESSION['role'] ?? '') === 'visiteur';
    }
}