<?php
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
            error_log($e->getMessage());
            $_SESSION['flash'] = 'Impossible de charger les frais forfait.';
            $fraisForfait = [];
        }

        $this->render('fraisForfait/index', [
            'title'   => 'Liste des frais forfait',
            'fraisForfait'   => $fraisForfait,
            'message' => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

    public function show($id): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $id = (int)$id;

        try {
            $fraisForfait = \Models\FraisForfait::findById($id);
            if (!$fraisForfait) {
                http_response_code(404);
                $_SESSION['flash'] = 'frais forfait introuvable.';
                $this->redirect('/index.php/fraisForfait');
            }
        } catch (\Throwable $e) {
            $_SESSION['flash'] = 'Erreur lors du chargement de le frais forfait.';
            $fraisForfait = null;
        }

        $this->render('fraisForfait/show', [
            'title' => 'Détail du frais forfait',
            'fraisForfait'  => $fraisForfait,
            'message' => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

    public function create(): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $this->render('fraisForfait/create', [
            'title'   => 'Créer un frais',
            'message' => $_SESSION['flash'] ?? '',
            'old'     => $_SESSION['old'] ?? ['libelle' => ''],
            'errors'  => $_SESSION['errors'] ?? [],
        ]);

        unset($_SESSION['flash'], $_SESSION['old'], $_SESSION['errors']);
    }

    public function store(): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $libelle = trim($_POST['libelle'] ?? '');
        $montant = $_POST['montant'] ?? '';

        $errors = [];

        if ($libelle === '') {
            $errors['libelle'] = 'Le libellé est obligatoire.';
        } elseif (mb_strlen($libelle) > 100) {
            $errors['libelle'] = 'Le libellé ne doit pas dépasser 100 caractères.';
        }

        if ($montant === '') {
            $errors['montant'] = 'Le montant est obligatoire.';
        } elseif ($montant <= 0) {
            $errors['montant'] = 'Le montant ne doit pas etre negatif.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old']    = ['libelle' => $libelle,'montant'=> $montant];
            $_SESSION['flash']  = 'Merci de corriger les erreurs du formulaire.';
            $this->redirect('/index.php/fraisForfait/create');
        }

        try {
            $id = \Models\FraisForfait::create($libelle,$montant);
            $_SESSION['flash'] = 'Frais créé avec succès.';
            $this->redirect('/index.php/fraisForfait/' . $id);
        } catch (\Throwable $e) {
            $_SESSION['flash'] = 'Impossible de créer le frais.';
            $this->redirect('/index.php/fraisForfait');
        }
    }

    public function edit($id): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $id = (int)$id;

        try {
            $fraisForfait = \Models\FraisForfait::findById($id);
            if (!$fraisForfait) {
                $_SESSION['flash'] = "Frais forfait introuvable.";
                $this->redirect('/index.php/fraisForfait');
            }
        } catch (\Throwable $e) {
            $_SESSION['flash'] = "Erreur lors du chargement du frais forfait.";
            $this->redirect('/index.php/fraisForfait');
        }

        $old = $_SESSION['old'] ?? [
            'libelle' => $fraisForfait['libelle'],
            'montant' => $fraisForfait['montant']
        ];

        $this->render('fraisForfait/edit', [
            'title'   => 'Modifier un frais forfait',
            'fraisForfait'  => $fraisForfait,
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
        $libelle = trim($_POST['libelle'] ?? '');
        $montant = trim($_POST['montant'] ?? '');

        $errors = [];

        if ($libelle === '') {
            $errors['libelle'] = 'Le libellé est obligatoire.';
        }

        if ($montant === '') {
            $errors['montant'] = 'Le montant est obligatoire.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = ['libelle' => $libelle,'montant' => $montant];
            $_SESSION['flash'] = "Merci de corriger les erreurs.";
            $this->redirect("/index.php/fraisForfait/$id/edit");
        }

        try {
            \Models\FraisForfait::update($id, $libelle, $montant);
            $_SESSION['flash'] = "Frais Forfait modifié avec succès.";
            $this->redirect("/index.php/fraisForfait/$id");
        } catch (\Throwable $e) {
            $_SESSION['flash'] = "Erreur lors de la mise à jour.";
            $this->redirect("/index.php/fraisForfait");
        }
    }

    public function delete($id): void
    {
        if (empty($_SESSION['uid'])) {
            $this->redirect('/index.php/');
        }

        $id = (int)$id;

        try {
            $ok = \Models\FraisForfait::delete($id);

            if ($ok) {
                $_SESSION['flash'] = " Frais forfait supprimé avec succès.";
            } else {
                $_SESSION['flash'] = "Impossible de supprimer ce Frais forfait.";
            }
        } catch (\Throwable $e) {
            $_SESSION['flash'] = "Erreur lors de la suppression du Frais forfait.";
        }

        $this->redirect('/index.php/fraisForfait');
    }
}