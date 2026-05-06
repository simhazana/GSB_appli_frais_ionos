<?php
namespace Controllers;

use Core\Controller;
use Models\Visiteur;

final class VisiteurController extends Controller
{
    public function index(): void
    {
        // verifier si connecter sinon rediriger vers la page de connexion
        if (empty($_SESSION['uid'])) {
            $this->redirect('/index.php/');
        }

        try {
            $visiteur = Visiteur::findAll(); // appel statique aligné avec le modèle
        } catch (\Throwable $e) {
            // Pour déboguer, active temporairement la ligne suivante :
            error_log($e->getMessage());
            $_SESSION['flash'] = 'Impossible de charger les visiteurs.';
            $visiteur = [];
        }

        $this->render('visiteur/index', [
            'title'   => 'Liste des Visiteurs',
            'visiteur'   => $visiteur,
            'message' => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

 public function show($id): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

    $id = (int)$id;

    try {
        $visiteur = \Models\Visiteur::findById($id);
        if (!$visiteur) {
            http_response_code(404);
            $_SESSION['flash'] = 'Visiteur introuvable.';
            $this->redirect('/index.php/visiteur');
            return;
        }
    } catch (\Throwable $e) {
        error_log($e->getMessage()); // utile en debug
        $_SESSION['flash'] = 'Erreur lors du chargement du visiteur.';
        $visiteur = null;
    }

    $this->render('visiteur/show', [
        'title' => 'Détail du visiteur',
        'visiteur'  => $visiteur,
        'message' => $_SESSION['flash'] ?? '',
    ]);
    unset($_SESSION['flash']);
}


 public function create(): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

        $this->render('visiteur/create', [
            'title'   => 'Créer un visiteur',
            'message' => $_SESSION['flash'] ?? '',
            'old'     => $_SESSION['old'] ?? [
                'nom' => '',
                'prenom' => '',
                'adresse' => '',
                'ville' => '',
                'cp' => '',
                'date_embauche' => '',
                'login' => '',
                'role'=> ''
                ],
            'errors'  => $_SESSION['errors'] ?? [],
        ]);

        unset($_SESSION['flash'], $_SESSION['old'], $_SESSION['errors']);
    }

    public function store(): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $cp = trim($_POST['cp'] ?? '');
    $date_embauche = trim($_POST['date_embauche'] ?? '');
    $login = trim($_POST['login'] ?? '');
    $mdp = trim($_POST['mdp'] ?? '');
    $role = trim($_POST['role'] ?? '');

    $errors = [];

    if ($nom === '') {
        $errors['nom'] = 'Le nom est obligatoire.';
    } elseif (mb_strlen($nom) > 100) {
        $errors['nom'] = 'Le nom ne doit pas dépasser 100 caractères.';
    }

    if ($prenom === '') {
        $errors['prenom'] = 'Le prenom est obligatoire.';
    } elseif (mb_strlen($prenom) > 100) {
        $errors['prenom'] = 'Le prenom ne doit pas dépasser 100 caractères.';
    }

    if ($adresse === '') {
        $errors['adresse'] = 'Adresse obligatoire.';
    } elseif (mb_strlen($adresse) > 100) {
        $errors['adresse'] = 'Adresse ne doit pas dépasser 100 caractères.';
    }

    if ($ville === '') {
        $errors['ville'] = 'La ville est obligatoire.';
    } elseif (mb_strlen($ville) > 100) {
        $errors['ville'] = 'La ville ne doit pas dépasser 100 caractères.';
    }

    if ($cp === '') {
        $errors['cp'] = 'Le code postal est obligatoire.';
    } elseif (mb_strlen($cp) > 100) {
        $errors['adresse'] = 'Le code postal ne doit pas dépasser 100 caractères.';
    }

    if ($date_embauche === '') {
        $errors['date_embauche'] = 'La date embauche est obligatoire.';
    } elseif (mb_strlen($date_embauche) > 100) {
        $errors['date_embauche'] = 'La date embauche  ne doit pas dépasser 100 caractères.';
    }

    if ($login === '') {
        $errors['login'] = 'Le login est obligatoire.';
    } elseif (mb_strlen($login) > 100) {
        $errors['login'] = 'Le login ne doit pas dépasser 100 caractères.';
    }

    if ($mdp === '') {
        $errors['mdp'] = 'Le mdp est obligatoire.';
    } elseif (mb_strlen($mdp) > 100) {
        $errors['mdp'] = 'Le mdp ne doit pas dépasser 100 caractères.';
    }

    if ($role === '') {
        $errors['role'] = 'Le role est obligatoire ';
    } elseif (mb_strlen($mdp) > 100) {
        $errors['role'] = 'Le role ne doit pas dépasser 100 caractères.';
    }


    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old']    = [
            'nom' => $nom,
            'prenom' => $prenom,
            'adresse' => $adresse,
            'ville' => $ville,
            'cp' => $cp,
            'date_embauche' => $date_embauche,
            'login' => $login,
            'role'=> $role
            ];
        $_SESSION['flash']  = 'Merci de corriger les erreurs du formulaire.';
        $this->redirect('/index.php/visiteur/create');
    }

    try {
        $id = \Models\Visiteur::create(
            $nom,
            $prenom,
            $adresse,
            $ville,
            $cp,
            $date_embauche,
            $login,
            $mdp,
            $role
        );
        $_SESSION['flash'] = 'visiteur créé avec succès.';
        $this->redirect('/index.php/visiteur/' . $id);
    } catch (\Throwable $e) {
        $_SESSION['flash'] = 'Impossible de créer le visiteur.';
        $this->redirect('/index.php/visiteur');
    }
}


// ---------- EDIT (GET) ----------
public function edit($id): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

    $id = (int)$id;

    try {
        $visiteur = \Models\Visiteur::findById($id);
        if (!$visiteur) {
            $_SESSION['flash'] = "Visiteur introuvable.";
            $this->redirect('/index.php/visiteur');
        }
    } catch (\Throwable $e) {
        $_SESSION['flash'] = "Erreur lors du chargement du visiteur.";
        $this->redirect('/index.php/visiteur');
    }

    // remplissage auto
    $old = $_SESSION['old'] ?? [
    'nom' => $visiteur['NOM'],
    'prenom' => $visiteur['PRENOM'],
    'adresse' => $visiteur['ADRESSE'],
    'ville' => $visiteur['VILLE'],
    'cp' => $visiteur['CP'],
    'date_embauche' => $visiteur['DATE_EMBAUCHE'],
    'login' => $visiteur['LOGIN']
        ];

    $this->render('visiteur/edit', [
        'title'   => 'Modifier un visiteur',
        'visiteur'=> $visiteur,
        'old'     => $old,
        'errors'  => $_SESSION['errors'] ?? [],
        'message' => $_SESSION['flash'] ?? ''
    ]);

    unset($_SESSION['old'], $_SESSION['errors'], $_SESSION['flash']);
}

// ---------- UPDATE (POST) ----------
public function update($id): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

    $id = (int)$id;
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $cp = trim($_POST['cp'] ?? '');
    $date_embauche = trim($_POST['date_embauche'] ?? '');
    $login = trim($_POST['login'] ?? '');
    //$mdp = trim($_POST['mdp'] ?? '');

    $errors = [];

  if ($nom === '') {
        $errors['nom'] = 'Le nom est obligatoire.';
    } 
   

    if ($prenom === '') {
        $errors['prenom'] = 'Le prenom est obligatoire.';
    }

    if ($adresse === '') {
        $errors['adresse'] = 'Adresse obligatoire.';
    }
    
    if ($ville === '') {
        $errors['ville'] = 'La ville est obligatoire.';
    } 

    if ($cp === '') {
        $errors['cp'] = 'Le code postal est obligatoire.';
    } 

    if ($date_embauche === '') {
        $errors['date_embauche'] = 'La date embauche est obligatoire.';
    } 
    
    if ($login === '') {
        $errors['login'] = 'Le login est obligatoire.';
    } 

   /* if ($mdp === '') {
        $errors['mdp'] = 'Le mdp est obligatoire.';
    } */

    if ($errors) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = [
            'nom' => $nom,
            'prenom' => $prenom,
            'adresse' => $adresse,
            'ville' => $ville,
            'cp' => $cp,
            'date_embauche' => $date_embauche,
            'login' => $login
            ];

        $_SESSION['flash'] = "Merci de corriger les erreurs.";
        $this->redirect("/visiteur/$id/edit");
    }

    try {
        \Models\Visiteur::update(
            $id,
            $nom,
            $prenom,
            $adresse,
            $ville,
            $cp,
            $date_embauche,
            $login
            );

        $_SESSION['flash'] = "Visiteur modifié avec succès.";
        $this->redirect("/visiteur/$id");
    } catch (\Throwable $e) {
        $_SESSION['flash'] = "Erreur lors de la mise à jour.";
        $this->redirect("/visiteur");
    }
}



public function delete($id): void
{
    if (empty($_SESSION['uid'])) {
        $this->redirect('/index.php/');
    }

    $id = (int)$id;

    try {
        $ok = \Models\Visiteur::delete($id);

        if ($ok) {
            $_SESSION['flash'] = "Visiteur supprimé avec succès.";
        } else {
            $_SESSION['flash'] = "Impossible de supprimer ce visiteur.";
        }
    } catch (\Throwable $e) {
        // error_log($e->getMessage());
        $_SESSION['flash'] = "Erreur lors de la suppression du visiteur.";
    }

    $this->redirect('/index.php/visiteur');
}



}
