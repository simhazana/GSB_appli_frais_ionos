<?php
namespace Controllers;

use Core\Controller;
use Models\FraisHorsForfait;

final class FraisHorsForfaitController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['uid'])) {
            $this->redirect('/index.php/');
        }

        try {
            $fraisHorsForfait = FraisHorsForfait::findAll();
        } catch (\Throwable $e) {
            $_SESSION['flash'] = 'Impossible de charger les frais hors forfait.';
            $fraisHorsForfait = [];
        }

        $this->render('fraisHorsForfait/index', [
            'title'   => 'Liste des frais hors forfait',
            'fraisHorsForfait'   => $fraisHorsForfait,
            'message' => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

    public function show($id): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $id = (int)$id;

        try {
            $fraisHorsForfait = \Models\FraisHorsForfait::findById($id);
            if (!$fraisHorsForfait) {
                http_response_code(404);
                $_SESSION['flash'] = 'frais hors forfait introuvable.';
                $this->redirect('/index.php/fraisHorsForfait');
            }
        } catch (\Throwable $e) {
            $_SESSION['flash'] = 'Erreur lors du chargement du frais hors forfait.';
            $fraisHorsForfait = null;
        }

        $this->render('fraisHorsForfait/show', [
            'title' => 'Détail du frais hors forfait',
            'fraisHorsForfait'  => $fraisHorsForfait,
            'message' => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

    public function create(): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $this->render('fraisHorsForfait/create', [
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
        $date = $_POST['date'] ?? '';

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
        if ($date === '') {
            $errors['date'] = 'La date est obligatoire.';
        } elseif ($date <= 0) {
            $errors['date'] = 'La date ne doit pas etre correcte.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old']    = [
                'libelle' => $libelle,
                'montant'=> $montant,
                'date'=>$date];
            $_SESSION['flash']  = 'Merci de corriger les erreurs du formulaire.';
            $this->redirect('/index.php/fraisHorsForfait/create');
        }

        try {
            $id = \Models\FraisHorsForfait::create($libelle,$montant,$date);
            $_SESSION['flash'] = 'Frais  créé avec succès.';
            $this->redirect('/index.php/fraisHorsForfait/' . $id);
        } catch (\Throwable $e) {
            $_SESSION['flash'] = 'Impossible de créer le frais.';
            $this->redirect('/index.php/fraisHorsForfait');
        }
    }

    public function edit($id): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $id = (int)$id;

        try {
            $fraisHorsForfait = \Models\FraisHorsForfait::findById($id);
            if (!$fraisHorsForfait) {
                $_SESSION['flash'] = "Frais hors forfait introuvable.";
                $this->redirect('/index.php/fraisHorsForfait');
            }
        } catch (\Throwable $e) {
            $_SESSION['flash'] = "Erreur lors du chargement du frais hors forfait.";
            $this->redirect('/index.php/fraisHorsForfait');
        }

        $old = $_SESSION['old'] ?? [
            'libelle' => $fraisHorsForfait['libelle'],
            'montant' => $fraisHorsForfait['montant'],
            'date' => $fraisHorsForfait['date'],
        ];

        $this->render('fraisHorsForfait/edit', [
            'title'   => 'Modifier un frais hors forfait',
            'fraisHorsForfait'  => $fraisHorsForfait,
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
        $montant = ($_POST['montant'] ?? '');
        $date = ($_POST['date'] ?? '');

        $errors = [];

        if ($libelle === '') {
            $errors['libelle'] = 'Le libellé est obligatoire.';
        }

        if ($montant === '') {
            $errors['montant'] = 'Le montant est obligatoire.';
        }

        if ($montant === '') {
            $errors['date'] = 'La date est obligatoire.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = ['libelle' => $libelle];
            $_SESSION['flash'] = "Merci de corriger les erreurs.";
            $this->redirect("/index.php/fraisHorsForfait/$id/edit");
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = ['montant' => $montant];
            $_SESSION['flash'] = "Merci de corriger les erreurs.";
            $this->redirect("/index.php/fraisHorsForfait/$id/edit");
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = ['date' => $date];
            $_SESSION['flash'] = "Merci de corriger les erreurs.";
            $this->redirect("/index.php/fraisHorsForfait/$id/edit");
        }

        try {
            \Models\FraisHorsForfait::update($id, $libelle, $montant,$date);
            $_SESSION['flash'] = "Frais hors Forfait modifié avec succès.";
            $this->redirect("/index.php/fraisHorsForfait/$id");
        } catch (\Throwable $e) {
            $_SESSION['flash'] = "Erreur lors de la mise à jour.";
            $this->redirect("/index.php/fraisHorsForfait");
        }
    }

    public function delete($id): void
    {
        if (empty($_SESSION['uid'])) {
            $this->redirect('/index.php/');
        }

        $id = (int)$id;

        try {
            $ok = \Models\FraisHorsForfait::delete($id);

            if ($ok) {
                $_SESSION['flash'] = " Frais hors forfait supprimé avec succès.";
            } else {
                $_SESSION['flash'] = "Impossible de supprimer ce Frais hors forfait.";
            }
        } catch (\Throwable $e) {
            $_SESSION['flash'] = "Erreur lors de la suppression du Frais hors forfait.";
        }

        $this->redirect('/index.php/fraisHorsForfait');
    }
}