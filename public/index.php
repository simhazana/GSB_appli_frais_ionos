<?php
declare(strict_types=1);

/*
 * public/index.php (IONOS safe)
 * - logs dans /ppe_logs
 * - trace toutes les requêtes
 * - normalisation robuste du path (sous-dossier + index.php)
 */

$logDir = __DIR__ . '/../ppe_logs';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}

// Ping
@file_put_contents($logDir . '/ping.log', "[" . date('c') . "] index.php reached\n", FILE_APPEND);

// Fatal errors
register_shutdown_function(function () use ($logDir) {
    $e = error_get_last();
    if ($e) {
        @file_put_contents(
            $logDir . '/php-fatal.log',
            "[" . date('c') . "] {$e['type']} {$e['message']} in {$e['file']}:{$e['line']}\n",
            FILE_APPEND
        );
    }
});

// Session
session_start();

// Raw request log (pour voir si POST arrive)
@file_put_contents(
    $logDir . '/raw.log',
    "[" . date('c') . "] " . ($_SERVER['REQUEST_METHOD'] ?? '?') . " " . ($_SERVER['REQUEST_URI'] ?? '?') .
    " CT=" . ($_SERVER['CONTENT_TYPE'] ?? '-') .
    " CL=" . ($_SERVER['CONTENT_LENGTH'] ?? '-') . "\n",
    FILE_APPEND
);

// Autoload simple
spl_autoload_register(function (string $class): void {
    $path = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

use Core\Router;

$router = new Router();

// -------------------- ROUTES --------------------
// Auth
$router->get('/',           [Controllers\AuthController::class, 'login']);
$router->get('/login',      [Controllers\AuthController::class, 'login']);   // si on tape /login
$router->get('/auth',       [Controllers\AuthController::class, 'login']);   // si on tape /auth en GET
$router->post('/auth',      [Controllers\AuthController::class, 'doLogin']); // formulaire -> /index.php/auth
$router->get('/dashboard',  [Controllers\AuthController::class, 'dashboard']);
$router->get('/logout',     [Controllers\AuthController::class, 'logout']);

// Test POST
$router->post('/pingpost', function () use ($logDir) {
    @file_put_contents($logDir . '/post-ok.log', "[" . date('c') . "] POST /pingpost OK\n", FILE_APPEND);
    echo "POST OK";
});

// Etat
$router->get('/etat',                       [Controllers\EtatController::class, 'index']);
$router->get('/etat/',                      [Controllers\EtatController::class, 'index']);
$router->get('/etat/create',                [Controllers\EtatController::class, 'create']);
$router->post('/etat/create',               [Controllers\EtatController::class, 'store']);
$router->get('#^/etat/([0-9]+)$#',          [Controllers\EtatController::class, 'show']);
$router->get('#^/etat/([0-9]+)/edit$#',     [Controllers\EtatController::class, 'edit']);
$router->post('#^/etat/([0-9]+)/edit$#',    [Controllers\EtatController::class, 'update']);
$router->post('#^/etat/([0-9]+)/delete$#',  [Controllers\EtatController::class, 'delete']);

// FraisForfait
$router->get('/fraisForfait',                           [Controllers\FraisForfaitController::class, 'index']);
$router->get('/fraisForfait/',                          [Controllers\FraisForfaitController::class, 'index']);
$router->get('/fraisForfait/create',                    [Controllers\FraisForfaitController::class, 'create']);
$router->post('/fraisForfait/create',                   [Controllers\FraisForfaitController::class, 'store']);
$router->get('#^/fraisForfait/([0-9]+)$#',              [Controllers\FraisForfaitController::class, 'show']);
$router->get('#^/fraisForfait/([0-9]+)/edit$#',         [Controllers\FraisForfaitController::class, 'edit']);
$router->post('#^/fraisForfait/([0-9]+)/edit$#',        [Controllers\FraisForfaitController::class, 'update']);
$router->post('#^/fraisForfait/([0-9]+)/delete$#',      [Controllers\FraisForfaitController::class, 'delete']);

// FraisHorsForfait
$router->get('/fraisHorsForfait',                           [Controllers\FraisHorsForfaitController::class, 'index']);
$router->get('/fraisHorsForfait/',                          [Controllers\FraisHorsForfaitController::class, 'index']);
$router->get('/fraisHorsForfait/create',                    [Controllers\FraisHorsForfaitController::class, 'create']);
$router->post('/fraisHorsForfait/create',                   [Controllers\FraisHorsForfaitController::class, 'store']);
$router->get('#^/fraisHorsForfait/([0-9]+)$#',              [Controllers\FraisHorsForfaitController::class, 'show']);
$router->get('#^/fraisHorsForfait/([0-9]+)/edit$#',         [Controllers\FraisHorsForfaitController::class, 'edit']);
$router->post('#^/fraisHorsForfait/([0-9]+)/edit$#',        [Controllers\FraisHorsForfaitController::class, 'update']);
$router->post('#^/fraisHorsForfait/([0-9]+)/delete$#',      [Controllers\FraisHorsForfaitController::class, 'delete']);

// Visiteur
$router->get('/visiteur',                           [Controllers\VisiteurController::class, 'index']);
$router->get('/visiteur/',                          [Controllers\VisiteurController::class, 'index']);
$router->get('/visiteur/create',                    [Controllers\VisiteurController::class, 'create']);
$router->post('/visiteur/create',                   [Controllers\VisiteurController::class, 'store']);
$router->get('#^/visiteur/([0-9]+)$#',              [Controllers\VisiteurController::class, 'show']);
$router->get('#^/visiteur/([0-9]+)/edit$#',         [Controllers\VisiteurController::class, 'edit']);
$router->post('#^/visiteur/([0-9]+)/edit$#',        [Controllers\VisiteurController::class, 'update']);
$router->post('#^/visiteur/([0-9]+)/delete$#',      [Controllers\VisiteurController::class, 'delete']);

// FicheFrais (si présent)
$router->get('/ficheFrais',                         [Controllers\FicheFraisController::class, 'index']);
$router->get('/ficheFrais/',                        [Controllers\FicheFraisController::class, 'index']);
$router->get('/ficheFrais/create',                  [Controllers\FicheFraisController::class, 'create']);
$router->post('/ficheFrais/create',                 [Controllers\FicheFraisController::class, 'store']);
$router->get('#^/ficheFrais/([0-9]+)/([0-9]+)$#',          [Controllers\FicheFraisController::class, 'show']);
$router->get('#^/ficheFrais/([0-9]+)/([0-9]+)/edit$#',     [Controllers\FicheFraisController::class, 'edit']);
$router->post('#^/ficheFrais/([0-9]+)/([0-9]+)/edit$#',    [Controllers\FicheFraisController::class, 'update']);
$router->post('#^/ficheFrais/([0-9]+)/([0-9]+)/delete$#',  [Controllers\FicheFraisController::class, 'delete']);

// -------------------- DISPATCH (normalisation robuste) --------------------
$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$scriptDir  = rtrim(dirname($scriptName), '/');

$path = $uriPath;

// Enlève le sous-dossier si besoin
if ($scriptDir !== '' && $scriptDir !== '/' && str_starts_with($path, $scriptDir)) {
    $path = substr($path, strlen($scriptDir)) ?: '/';
}

// Enlève index.php si présent
if ($path === '/index.php') {
    $path = '/';
} elseif (str_starts_with($path, '/index.php/')) {
    $path = substr($path, strlen('/index.php')) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Trace
@file_put_contents($logDir . '/trace.log', "[" . date('c') . "] DISPATCH $method $path\n", FILE_APPEND);

// Dispatch + exceptions
try {
    $router->dispatch($method, $path);
    @file_put_contents($logDir . '/trace.log', "[" . date('c') . "] DISPATCH DONE\n", FILE_APPEND);
} catch (\Throwable $e) {
    @file_put_contents(
        $logDir . '/php-exception.log',
        "[" . date('c') . "] " . get_class($e) . ": " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n" .
        $e->getTraceAsString() . "\n\n",
        FILE_APPEND
    );
    http_response_code(500);
    echo "Erreur interne.";
}


/*declare(strict_types=1);

ini_set('display_errors','1');
ini_set('display_startup_errors','1');
error_reporting(E_ALL);


session_start();

// Autoload très simple
spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($path)) require $path;
});

use Core\Router;

$router = new Router();

// Routes
$router->get ('/',           [Controllers\AuthController::class, 'login']);
$router->get('/login', [Controllers\AuthController::class, 'login']);

$router->get ('/index.php',  [Controllers\AuthController::class, 'login']); // fallback si /index.php est appelé
$router->post('/login',      [Controllers\AuthController::class, 'doLogin']);
$router->get ('/dashboard',  [Controllers\AuthController::class, 'dashboard']);
$router->get ('/logout',     [Controllers\AuthController::class, 'logout']);
$router->get ('/logout',     [Controllers\AuthController::class, 'logout']);
$router->get('#^/etat/([0-9]+)$#', [Controllers\EtatController::class, 'show']);
$router->get('/etat',       [Controllers\EtatController::class, 'index']);

$router->get('/etat/',       [Controllers\EtatController::class, 'index']);

// --- routes create ---
$router->get ('/etat/create',       [Controllers\EtatController::class, 'create']);
$router->post('/etat/create',       [Controllers\EtatController::class, 'store']);




$router->get ('#^/etat/([0-9]+)/edit$#', [Controllers\EtatController::class, 'edit']);
$router->post('#^/etat/([0-9]+)/edit$#', [Controllers\EtatController::class, 'update']);

// DELETE
$router->post('#^/etat/([0-9]+)/delete$#', [Controllers\EtatController::class, 'delete']);


$router->get('#^/fraisForfait/([0-9]+)$#', [Controllers\FraisForfaitController::class, 'show']);
$router->get('/fraisForfait',       [Controllers\FraisForfaitController::class, 'index']);

$router->get('/fraisForfait/',       [Controllers\FraisForfaitController::class, 'index']);






// Normalisation du path (gère le projet dans un sous-dossier, ex. /monapp/public)
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$scriptDir   = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/'); // ex: /monapp/public

if ($scriptDir !== '' && $scriptDir !== '/' && strncmp($requestPath, $scriptDir, strlen($scriptDir)) === 0) {
    $requestPath = substr($requestPath, strlen($scriptDir)) ?: '/';
}

if ($requestPath === '/index.php') $requestPath = '/';

// Fallback manuel si le Router n'accroche pas la regex
if (preg_match('#^' . preg_quote($scriptDir, '#') . '/etat/([0-9]+)$#', $_SERVER['REQUEST_URI'] ?? '', $m)
    || preg_match('#^/etat/([0-9]+)$#', $requestPath, $m)) {
    (new \Controllers\EtatController)->show((int)$m[1]);
    exit;
}

if (preg_match('#^' . preg_quote($scriptDir, '#') . '/fraisForfait/([0-9]+)$#', $_SERVER['REQUEST_URI'] ?? '', $m)
    || preg_match('#^/fraisForfait/([0-9]+)$#', $requestPath, $m)) {
    (new \Controllers\FraisForfaitController)->show((int)$m[1]);
    exit;
}
// Fallback manuel pour /etat/{id}/edit
if (preg_match('#^' . preg_quote($scriptDir, '#') . '/etat/([0-9]+)/edit$#', $_SERVER['REQUEST_URI'] ?? '', $m)
    || preg_match('#^/etat/([0-9]+)/edit$#', $requestPath, $m)) {

    $id = (int)$m[1];

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        (new \Controllers\EtatController)->update($id);
    } else {
        (new \Controllers\EtatController)->edit($id);
    }
    exit;
}

// Fallback manuel pour /etat/{id}/delete
if (preg_match('#^' . preg_quote($scriptDir, '#') . '/etat/([0-9]+)/delete$#', $_SERVER['REQUEST_URI'] ?? '', $m)
    || preg_match('#^/etat/([0-9]+)/delete$#', $requestPath, $m)) {

    $id = (int)$m[1];

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        (new \Controllers\EtatController)->delete($id);
    } else {
        // On ne fait rien en GET sur /delete, on renvoie vers la liste
        header('Location: /etat');
    }
    exit;
}



$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $requestPath);*/

