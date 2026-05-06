<?php
declare(strict_types=1);

namespace Controllers;

use Core\Controller;
use Models\FraisForfait;

final class FraisForfaitController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['uid'])) {
            $this->redirect('/index.php/');
        }

        try {
            $fraisForfait = FraisForfait::findAll();
         } catch (\Throwable $e) {
    @file_put_contents(
        __DIR__ . '/../../ppe_logs/php-fraisforfait-exception.log',
        "[".date('c')."] ".get_class($e).": ".$e->getMessage()." in ".$e->getFile().":".$e->getLine()."\n".$e->getTraceAsString()."\n\n",
        FILE_APPEND
    );
    $_SESSION['flash'] = 'Impossible de charger les frais forfait.';
    $fraisForfait = [];
}

        $this->render('fraisForfait/index', [
            'title'        => 'Liste des frais forfait',
            'fraisForfait' => $fraisForfait,
            'message'      => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

    public function show($id): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $id = (int)$id;

        try {
            $fraisForfait = FraisForfait::findById($id);
            if (!$fraisForfait) {
                $_SESSION['flash'] = 'Frais forfait introuvable.';
                $this->redirect('/index.php/fraisForfait');
            }
        } catch (\Throwable $e) {
            $_SESSION['flash'] = 'Erreur lors du chargement du frais forfait.';
            $this->redirect('/index.php/fraisForfait');
        }

        $this->render('fraisForfait/show', [
            'title'       => 'Détail du frais forfait',
            'fraisForfait'=> $fraisForfait,
            'message'     => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }
}
