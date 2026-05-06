<?php
declare(strict_types=1);

namespace Controllers;

use Core\Controller;
use Models\User;

final class AuthController extends Controller
{
    private function log(string $file, string $msg): void
    {
        @file_put_contents(__DIR__ . '/../../ppe_logs/' . $file, '[' . date('c') . '] ' . $msg . "\n", FILE_APPEND);
    }

    public function login(): void
    {
        $this->log('auth.log', 'login() uid=' . ($_SESSION['uid'] ?? 'NULL'));

        // IMPORTANT IONOS: on redirige vers /index.php/dashboard
        if (!empty($_SESSION['uid'])) {
            $this->redirect('/index.php/dashboard');
        }

        $message = $_SESSION['flash'] ?? '';
        unset($_SESSION['flash']);

        $this->render('login', [
            'title'   => 'Connexion',
            'csrf'    => $this->csrfToken(),
            'message' => $message,
        ]);
    }

    public function doLogin(): void
    {
        $this->log('auth.log', 'doLogin() START');

        try {
            if (!$this->checkCsrf($_POST['csrf'] ?? null)) {
                $this->log('auth.log', 'doLogin() CSRF FAIL');
                http_response_code(400);
                echo 'CSRF';
                return;
            }

            $username = trim((string)($_POST['username'] ?? ''));
            $password = (string)($_POST['password'] ?? '');

            $this->log('auth.log', 'doLogin() username=' . $username);

            if ($username === '' || $password === '') {
                $_SESSION['flash'] = 'Identifiants requis';
                $this->log('auth.log', 'doLogin() EMPTY -> redirect /index.php/');
                $this->redirect('/index.php/');
            }

            $user = User::findByUsername($username);
            $this->log('auth.log', 'doLogin() findByUsername returned=' . (is_array($user) ? 'array' : 'null'));

            if (!$user || empty($user['mdp']) || !password_verify($password, (string)$user['mdp'])) {
                $_SESSION['flash'] = 'Mauvais identifiant ou mot de passe';
                $this->log('auth.log', 'doLogin() BAD CREDS -> redirect /index.php/');
                $this->redirect('/index.php/');
            }

            session_regenerate_id(true);
            $_SESSION['uid']  = (int)$user['id'];
            $_SESSION['name'] = (string)($user['login'] ?? $username);

            $this->log('auth.log', 'doLogin() OK uid=' . $_SESSION['uid'] . ' -> redirect /index.php/dashboard');
            $this->redirect('/index.php/dashboard');

        } catch (\Throwable $e) {
            $this->log(
                'php-auth-exception.log',
                get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . "\n" . $e->getTraceAsString()
            );
            http_response_code(500);
            echo "Erreur interne (auth).";
        }
    }

    public function dashboard(): void
    {
        $this->log('dashboard.log', 'dashboard() uid=' . ($_SESSION['uid'] ?? 'NULL') . ' name=' . ($_SESSION['name'] ?? 'NULL'));

        if (empty($_SESSION['uid'])) {
            $this->log('dashboard.log', 'dashboard() not logged -> redirect /index.php/');
            $this->redirect('/index.php/');
        }

        try {
            $this->render('dashboard', [
                'title'    => 'Dashboard',
                'username' => $_SESSION['name'] ?? 'Utilisateur',
            ]);
        } catch (\Throwable $e) {
            $this->log(
                'php-dashboard-exception.log',
                get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . "\n" . $e->getTraceAsString()
            );
            http_response_code(500);
            echo "Erreur dashboard.";
        }
    }

    public function logout(): void
    {
        $this->log('auth.log', 'logout() uid=' . ($_SESSION['uid'] ?? 'NULL'));

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $p['path'] ?? '/',
                $p['domain'] ?? '',
                (bool)($p['secure'] ?? false),
                (bool)($p['httponly'] ?? true)
            );
        }

        session_destroy();
        $this->redirect('/index.php/');
    }
}

/*namespace Controllers;
use Core\Controller;
use Models\User;

final class AuthController extends Controller {

    public function login(): void {
        if (!empty($_SESSION['uid'])) { $this->redirect('/dashboard'); }
        $this->render('login', [
            'title' => 'Connexion',
            'csrf'  => $this->csrfToken(),
            'message' => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

    public function doLogin(): void {
        if (!$this->checkCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit('CSRF'); }

        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $_SESSION['flash'] = 'Identifiants requis';
            $this->redirect('/');
        }

        $user = User::findByUsername($username);
        if (!$user || !password_verify($password, $user['mdp'])) {
            $_SESSION['flash'] = 'Mauvais identifiant ou mot de passe';
            $this->redirect('/');
        }

        $_SESSION['uid'] = (int)$user['id'];
        $_SESSION['name'] = $user['login'];
        $this->redirect('/dashboard');
    }

    public function dashboard(): void {
        if (empty($_SESSION['uid'])) $this->redirect('/');
        $this->render('dashboard', ['title'=>'Dashboard', 'username'=>$_SESSION['name'] ?? 'Utilisateur']);
    }

    public function logout(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time()-42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        $this->redirect('/');
    }
}
*/