<?php
declare(strict_types=1);

namespace Controllers;

use Core\Controller;
use Models\Etat;

final class EtatController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['uid'])) {
            $this->redirect('/index.php/');
        }

        try {
            $etats = Etat::findAll();
        } catch (\Throwable $e) {
            $_SESSION['flash'] = 'Impossible de charger les états.';
            $etats = [];
        }

        $this->render('etat/index', [
            'title'   => 'Liste des États',
            'etats'   => $etats,
            'message' => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

    public function show($id): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $id = (int)$id;

        try {
            $etat = Etat::findById($id);
            if (!$etat) {
                $_SESSION['flash'] = 'État introuvable.';
                $this->redirect('/index.php/etat');
            }
        } catch (\Throwable $e) {
            $_SESSION['flash'] = 'Erreur lors du chargement de l’état.';
            $this->redirect('/index.php/etat');
        }

        $this->render('etat/show', [
            'title'   => 'Détail de l’état',
            'etat'    => $etat,
            'message' => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

    public function create(): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $this->render('etat/create', [
            'title'   => 'Créer un état',
            'message' => $_SESSION['flash'] ?? '',
            'old'     => $_SESSION['old'] ?? ['libelle' => ''],
            'errors'  => $_SESSION['errors'] ?? [],
        ]);

        unset($_SESSION['flash'], $_SESSION['old'], $_SESSION['errors']);
    }

    public function store(): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $libelle = trim((string)($_POST['libelle'] ?? ''));
        $errors = [];

        if ($libelle === '') {
            $errors['libelle'] = 'Le libellé est obligatoire.';
        } elseif (mb_strlen($libelle) > 100) {
            $errors['libelle'] = 'Le libellé ne doit pas dépasser 100 caractères.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old']    = ['libelle' => $libelle];
            $_SESSION['flash']  = 'Merci de corriger les erreurs du formulaire.';
            $this->redirect('/index.php/etat/create');
        }

        try {
            $id = Etat::create($libelle);
            $_SESSION['flash'] = 'État créé avec succès.';
            $this->redirect('/index.php/etat/' . $id);
        } catch (\Throwable $e) {
            $_SESSION['flash'] = 'Impossible de créer l’état.';
            $this->redirect('/index.php/etat');
        }
    }

    public function edit($id): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $id = (int)$id;

        try {
            $etat = Etat::findById($id);
            if (!$etat) {
                $_SESSION['flash'] = "État introuvable.";
                $this->redirect('/index.php/etat');
            }
        } catch (\Throwable $e) {
            $_SESSION['flash'] = "Erreur lors du chargement de l'état.";
            $this->redirect('/index.php/etat');
        }

        $old = $_SESSION['old'] ?? ['libelle' => $etat['libelle']];

        $this->render('etat/edit', [
            'title'   => 'Modifier un état',
            'etat'    => $etat,
            'old'     => $old,
            'errors'  => $_SESSION['errors'] ?? [],
            'message' => $_SESSION['flash'] ?? ''
        ]);

        unset($_SESSION['old'], $_SESSION['errors'], $_SESSION['flash']);
    }

    public function update($id): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $id = (int)$id;
        $libelle = trim((string)($_POST['libelle'] ?? ''));

        $errors = [];
        if ($libelle === '') {
            $errors['libelle'] = 'Le libellé est obligatoire.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = ['libelle' => $libelle];
            $_SESSION['flash'] = "Merci de corriger les erreurs.";
            $this->redirect('/index.php/etat/' . $id . '/edit');
        }

        try {
            Etat::update($id, $libelle);
            $_SESSION['flash'] = "État modifié avec succès.";
            $this->redirect('/index.php/etat/' . $id);
        } catch (\Throwable $e) {
            $_SESSION['flash'] = "Erreur lors de la mise à jour.";
            $this->redirect('/index.php/etat');
        }
    }

    public function delete($id): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $id = (int)$id;

        try {
            $ok = Etat::delete($id);
            $_SESSION['flash'] = $ok ? "État supprimé avec succès." : "Impossible de supprimer cet état.";
        } catch (\Throwable $e) {
            $_SESSION['flash'] = "Erreur lors de la suppression de l’état.";
        }

        $this->redirect('/index.php/etat');
    }
}
