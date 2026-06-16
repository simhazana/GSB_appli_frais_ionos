<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
$router->get('/',             [Controllers\AuthController::class, 'login']);
$router->get('/index.php',    [Controllers\AuthController::class, 'login']);
$router->post('/login',       [Controllers\AuthController::class, 'doLogin']);
$router->get('/dashboard',    [Controllers\AuthController::class, 'dashboard']);
$router->get('/logout',       [Controllers\AuthController::class, 'logout']);
$router->get('/inscription',  [Controllers\AuthController::class, 'inscription']);
$router->post('/inscription', [Controllers\VisiteurController::class, 'store']);

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

// LigneFraisForfait
$router->get('/ligneFraisForfait',                           [Controllers\LigneFraisForfaitController::class, 'index']);
$router->get('/ligneFraisForfait/',                          [Controllers\LigneFraisForfaitController::class, 'index']);
$router->get('/ligneFraisForfait/create',                    [Controllers\LigneFraisForfaitController::class, 'create']);
$router->post('/ligneFraisForfait/create',                   [Controllers\LigneFraisForfaitController::class, 'store']);
$router->get('#^/ligneFraisForfait/([0-9]+)$#',              [Controllers\LigneFraisForfaitController::class, 'show']);
$router->get('#^/ligneFraisForfait/([0-9]+)/edit$#',         [Controllers\LigneFraisForfaitController::class, 'edit']);
$router->post('#^/ligneFraisForfait/([0-9]+)/edit$#',        [Controllers\LigneFraisForfaitController::class, 'update']);
$router->post('#^/ligneFraisForfait/([0-9]+)/delete$#',      [Controllers\LigneFraisForfaitController::class, 'delete']);

// FicheFrais
$router->get('/fichefrais',        [Controllers\FicheFraisController::class, 'index']);
$router->get('/fichefrais/',       [Controllers\FicheFraisController::class, 'index']);
$router->get('/fichefrais/create', [Controllers\FicheFraisController::class, 'create']);
$router->post('/fichefrais/create', [Controllers\FicheFraisController::class, 'store']);
$router->get('#^/fichefrais/([^/]+)/([^/]+)/edit$#',       [Controllers\FicheFraisController::class, 'edit']);
$router->post('#^/fichefrais/([^/]+)/([^/]+)/edit$#',      [Controllers\FicheFraisController::class, 'update']);
$router->post('#^/fichefrais/([^/]+)/([^/]+)/delete$#',    [Controllers\FicheFraisController::class, 'delete']);
$router->post('#^/fichefrais/([^/]+)/([^/]+)/validate$#',  [Controllers\FicheFraisController::class, 'validate']);
$router->post('#^/fichefrais/([^/]+)/([^/]+)/refuse$#',    [Controllers\FicheFraisController::class, 'refuse']);
$router->post('#^/fichefrais/([^/]+)/([^/]+)/cloture$#',   [Controllers\FicheFraisController::class, 'cloture']);
$router->post('#^/fichefrais/([^/]+)/([^/]+)/rembourse$#', [Controllers\FicheFraisController::class, 'rembourse']);
$router->post('#^/fichefrais/([^/]+)/([^/]+)/setetat-([0-9]+)$#', [Controllers\FicheFraisController::class, 'setEtat']);
$router->get('#^/fichefrais/([^/]+)/([^/]+)$#',            [Controllers\FicheFraisController::class, 'show']);

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

// -------------------- FALLBACKS MANUELS --------------------
// (au cas où le Router n'accroche pas certaines regex)

// if (preg_match('#^/etat/([0-9]+)$#', $path, $m)) {
//     (new \Controllers\EtatController)->show((int)$m[1]);
//     exit;
// }

// if (preg_match('#^/etat/([0-9]+)/edit$#', $path, $m)) {
//     $id = (int)$m[1];
//     if ($method === 'POST') {
//         (new \Controllers\EtatController)->update($id);
//     } else {
//         (new \Controllers\EtatController)->edit($id);
//     }
//     exit;
// }

// if (preg_match('#^/etat/([0-9]+)/delete$#', $path, $m)) {
//     $id = (int)$m[1];
//     if ($method === 'POST') {
//         (new \Controllers\EtatController)->delete($id);
//     } else {
//         header('Location: /etat');
//     }
//     exit;
// }

// if (preg_match('#^/fraisForfait/([0-9]+)$#', $path, $m)) {
//     (new \Controllers\FraisForfaitController)->show((int)$m[1]);
//     exit;
// }

// if (preg_match('#^/fraisForfait/([0-9]+)/edit$#', $path, $m)) {
//     $id = (int)$m[1];
//     if ($method === 'POST') {
//         (new \Controllers\FraisForfaitController)->update($id);
//     } else {
//         (new \Controllers\FraisForfaitController)->edit($id);
//     }
//     exit;
// }

// if (preg_match('#^/fraisForfait/([0-9]+)/delete$#', $path, $m)) {
//     $id = (int)$m[1];
//     if ($method === 'POST') {
//         (new \Controllers\FraisForfaitController)->delete($id);
//     } else {
//         header('Location: /fraisForfait');
//     }
//     exit;
// }

// if (preg_match('#^/fraisHorsForfait/([0-9]+)$#', $path, $m)) {
//     (new \Controllers\FraisHorsForfaitController)->show((int)$m[1]);
//     exit;
// }

// if (preg_match('#^/fraisHorsForfait/([0-9]+)/edit$#', $path, $m)) {
//     $id = (int)$m[1];
//     if ($method === 'POST') {
//         (new \Controllers\FraisHorsForfaitController)->update($id);
//     } else {
//         (new \Controllers\FraisHorsForfaitController)->edit($id);
//     }
//     exit;
// }

// if (preg_match('#^/fraisHorsForfait/([0-9]+)/delete$#', $path, $m)) {
//     $id = (int)$m[1];
//     if ($method === 'POST') {
//         (new \Controllers\FraisHorsForfaitController)->delete($id);
//     } else {
//         header('Location: /fraisHorsForfait');
//     }
//     exit;
// }

// if (preg_match('#^/visiteur/([0-9]+)$#', $path, $m)) {
//     (new \Controllers\VisiteurController)->show((int)$m[1]);
//     exit;
// }

// if (preg_match('#^/visiteur/([0-9]+)/edit$#', $path, $m)) {
//     $id = (int)$m[1];
//     if ($method === 'POST') {
//         (new \Controllers\VisiteurController)->update($id);
//     } else {
//         (new \Controllers\VisiteurController)->edit($id);
//     }
//     exit;
// }

// if (preg_match('#^/visiteur/([0-9]+)/delete$#', $path, $m)) {
//     $id = (int)$m[1];
//     if ($method === 'POST') {
//         (new \Controllers\VisiteurController)->delete($id);
//     } else {
//         header('Location: /visiteur');
//     }
//     exit;
// }

// if (preg_match('#^/ligneFraisForfait/([0-9]+)$#', $path, $m)) {
//     (new \Controllers\LigneFraisForfaitController)->show((int)$m[1]);
//     exit;
// }

// if (preg_match('#^/ligneFraisForfait/([0-9]+)/edit$#', $path, $m)) {
//     $id = (int)$m[1];
//     if ($method === 'POST') {
//         (new \Controllers\LigneFraisForfaitController)->update($id);
//     } else {
//         (new \Controllers\LigneFraisForfaitController)->edit($id);
//     }
//     exit;
// }

// if (preg_match('#^/ligneFraisForfait/([0-9]+)/delete$#', $path, $m)) {
//     $id = (int)$m[1];
//     if ($method === 'POST') {
//         (new \Controllers\LigneFraisForfaitController)->delete($id);
//     } else {
//         header('Location: /ligneFraisForfait');
//     }
//     exit;
// }

// if (preg_match('#^/fichefrais/([^/]+)/([^/]+)/edit$#', $path, $m)) {
//     if ($method === 'POST') {
//         (new \Controllers\FicheFraisController)->update($m[1], $m[2]);
//     } else {
//         (new \Controllers\FicheFraisController)->edit($m[1], $m[2]);
//     }
//     exit;
// }

// if (preg_match('#^/fichefrais/([^/]+)/([^/]+)/delete$#', $path, $m)) {
//     if ($method === 'POST') {
//         (new \Controllers\FicheFraisController)->delete($m[1], $m[2]);
//     } else {
//         header('Location: /fichefrais');
//     }
//     exit;
// }

// if (preg_match('#^/fichefrais/([^/]+)/([^/]+)/validate$#', $path, $m)) {
//     if ($method === 'POST') {
//         (new \Controllers\FicheFraisController)->validate($m[1], $m[2]);
//     } else { header('Location: /fichefrais'); }
//     exit;
// }

// if (preg_match('#^/fichefrais/([^/]+)/([^/]+)/refuse$#', $path, $m)) {
//     if ($method === 'POST') {
//         (new \Controllers\FicheFraisController)->refuse($m[1], $m[2]);
//     } else { header('Location: /fichefrais'); }
//     exit;
// }

// if (preg_match('#^/fichefrais/([^/]+)/([^/]+)/cloture$#', $path, $m)) {
//     if ($method === 'POST') {
//         (new \Controllers\FicheFraisController)->cloture($m[1], $m[2]);
//     } else { header('Location: /fichefrais'); }
//     exit;
// }

// if (preg_match('#^/fichefrais/([^/]+)/([^/]+)/rembourse$#', $path, $m)) {
//     if ($method === 'POST') {
//         (new \Controllers\FicheFraisController)->rembourse($m[1], $m[2]);
//     } else { header('Location: /fichefrais'); }
//     exit;
// }

// if (preg_match('#^/fichefrais/([^/]+)/([^/]+)/setetat-([0-9]+)$#', $path, $m)) {
//     if ($method === 'POST') {
//         (new \Controllers\FicheFraisController)->setEtat($m[1], $m[2], (int)$m[3]);
//     } else {
//         header('Location: /fichefrais');
//     }
//     exit;
// }

// if (preg_match('#^/fichefrais/([^/]+)/([^/]+)$#', $path, $m)) {
//     (new \Controllers\FicheFraisController)->show((int)$m[1], (int)$m[2]);
//     exit;
// }


// -------------------- DISPATCH PRINCIPAL --------------------
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