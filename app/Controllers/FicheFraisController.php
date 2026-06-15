<?php
namespace Controllers;

use Core\Controller;
use Models\FicheFrais;
use Models\Visiteur;
use Models\Etat;
use Models\FraisHorsForfait;
use Models\FraisForfait;

final class FicheFraisController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        //$ficheFrais = [];
        //$etats      = [];

        try {
            if ($this->isComptable()) {
                $ficheFrais = FicheFrais::findAll();
            } else {
                $ficheFrais = FicheFrais::findByVisiteur((int)$_SESSION['uid']);
            }
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash'] = 'Impossible de charger les fiches frais.';
        }

        try {
            $etats = \Models\Etat::findAll();
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }

        $this->render('fichefrais/index', [
            'title'      => 'Liste des fiches frais',
            'ficheFrais' => $ficheFrais,
            'etats'      => $etats,
            'message'    => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

    public function show($idvisiteur, $mois): void
    {
        $this->requireAuth();

        try {
            $ficheFrais = FicheFrais::findById((int)$idvisiteur, (int)$mois);
            if (!$ficheFrais) { $this->redirect('/index.php/fichefrais'); return; }
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash'] = 'Erreur lors du chargement de la fiche frais.';
            $ficheFrais = null;
        }

        $this->render('fichefrais/show', [
            'title'      => 'Détail de la fiche frais',
            'ficheFrais' => $ficheFrais,
            'message'    => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

    public function create(): void
    {
        $this->requireAuth();

        $this->render('fichefrais/create', [
            'title'            => 'Créer une fiche frais',
            'message'          => $_SESSION['flash'] ?? '',
            'old'              => $_SESSION['old'] ?? [],
            'errors'           => $_SESSION['errors'] ?? [],
            'visiteurs'        => Visiteur::findAll(),
            'etats'            => Etat::findAll(),
            'fraisHorsForfaits'=> FraisHorsForfait::findAll(),
            'fraisForfaits'=> FraisForfait::findAll(),
        ]);
        unset($_SESSION['flash'], $_SESSION['old'], $_SESSION['errors']);
    }

    public function store(): void
    {
        $this->requireAuth();

        $visiteur         = trim($_POST['visiteur'] ?? '');
        $mois             = trim($_POST['mois'] ?? '');
        $nbrJustificatifs = trim($_POST['nbrJustificatifs'] ?? '');
        $quantite         = trim($_POST['quantite'] ?? '');
        $montantValide    = trim($_POST['montantValide'] ?? '');
        $dateModif        = trim($_POST['dateModif'] ?? '');
        $fraisHorsForfait = trim($_POST['fraishorsforfait'] ?? '');
        $fraisForfait     = trim($_POST['fraisforfait'] ?? '');
        $etat             = trim($_POST['etat'] ?? '');

        $errors = [];

        if ($visiteur === '')         $errors['visiteur']         = 'Le visiteur est obligatoire.';
        if ($nbrJustificatifs === '') $errors['nbrJustificatifs'] = 'Le nombre de justificatifs est obligatoire.';
        if ($quantite === '')         $errors['quantite']         = 'La quantité est obligatoire.';
        if ($dateModif === '')        $errors['dateModif']        = 'La date est obligatoire.';
        if ($fraisHorsForfait === '') $errors['fraishorsforfait'] = 'Le frais hors forfait est obligatoire.';
        if ($fraisForfait === '') $errors['fraisforfait'] = 'Le frais hors forfait est obligatoire.';

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old']    = compact('visiteur','fraisForfait', 'quantite', 'fraisHorsForfait','dateModif', 'nbrJustificatifs');
            $_SESSION['flash']  = 'Merci de corriger les erreurs.';
            $this->redirect('/index.php/fichefrais/create');
        }

        try {
            FicheFrais::createFull($visiteur,  $fraisForfait, $quantite, $fraisHorsForfait, $dateModif, $nbrJustificatifs, 8);
            $_SESSION['flash'] = 'Fiche frais créée avec succès.';
            $this->redirect('/index.php/fichefrais');
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            if (str_contains($e->getMessage(), '1062')) {
                $_SESSION['flash'] = 'Une fiche frais existe déjà pour ce visiteur ce mois-ci.';
                $this->redirect('/index.php/fichefrais/create');
                return;
            }
            $_SESSION['flash'] = 'Impossible de créer la fiche frais.';
            $this->redirect('/index.php/fichefrais');
        }
    }

    public function setEtat(string $idvisiteur, string $mois, int $idEtat): void
    {
        $this->requireComptable();
        try {
            $ok = FicheFrais::setEtat($idvisiteur, $mois, $idEtat);
            $_SESSION['flash'] = $ok ? 'État mis à jour.' : "Impossible de changer l'état.";
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash'] = "Erreur technique lors du changement d'état.";
        }
        $this->redirect('/index.php/fichefrais');
    }

    public function edit($id, $mois): void
    {
        $this->requireAuth();
        $id = (int)$id;

        try {
            $ficheFrais = FicheFrais::findById($id, (int)$mois);
            if (!$ficheFrais) {
                $_SESSION['flash'] = 'Fiche frais introuvable.';
                $this->redirect('/index.php/fichefrais');
            }
        } catch (\Throwable $e) {
            $_SESSION['flash'] = 'Erreur lors du chargement.';
            $this->redirect('/index.php/fichefrais');
        }

        $old = $_SESSION['old'] ?? [
            'nbrJustificatifs' => $ficheFrais['nbrJustificatifs'],
            'montantValide'    => $ficheFrais['montantValide'],
            'dateModif'        => $ficheFrais['dateModif'],
        ];

        $this->render('fichefrais/edit', [
            'title'      => 'Modifier une fiche frais',
            'ficheFrais' => $ficheFrais,
            'old'        => $old,
            'errors'     => $_SESSION['errors'] ?? [],
            'message'    => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['old'], $_SESSION['errors'], $_SESSION['flash']);
    }

    public function update($idvisiteur, $mois): void
    {
        $this->requireAuth();

        $nbrJustificatifs = trim($_POST['nbrJustificatifs'] ?? '');
        $montantValide    = trim($_POST['montantValide'] ?? '');
        $dateModif        = trim($_POST['dateModif'] ?? '');
        $errors = [];

        if ($nbrJustificatifs === '') $errors['nbrJustificatifs'] = 'Le nombre de justificatifs est obligatoire.';
        if ($montantValide === '')    $errors['montantValide']    = 'Le montant est obligatoire.';

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old']    = compact('nbrJustificatifs', 'montantValide', 'dateModif');
            $_SESSION['flash']  = 'Merci de corriger les erreurs.';
            $this->redirect("/index.php/fichefrais/$idvisiteur/$mois/edit");
        }

        try {
            FicheFrais::update($idvisiteur, $mois, $nbrJustificatifs, $montantValide, $dateModif);
            $_SESSION['flash'] = 'Fiche frais modifiée avec succès.';
            $this->redirect("/index.php/fichefrais/$idvisiteur/$mois");
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash'] = 'Erreur lors de la mise à jour.';
            $this->redirect('/index.php/fichefrais');
        }
    }

    public function delete($idvisiteur, $mois): void
    {
        $this->requireAuth();

        $idvisiteur = (int)$idvisiteur;
        $mois       = (int)$mois;

        if (!$this->isComptable()) {
            if ($idvisiteur !== (int)$_SESSION['uid']) {
                $_SESSION['flash'] = "Action non autorisée.";
                $this->redirect('/index.php/fichefrais');
                return;
            }

            try {
                $fiche = FicheFrais::findById($idvisiteur, $mois);
                if (!$fiche || $fiche['libelleEtat'] !== 'Créé') {
                    $_SESSION['flash'] = "Vous ne pouvez supprimer que les fiches à l'état « Créé ».";
                    $this->redirect('/index.php/fichefrais');
                    return;
                }
            } catch (\Throwable $e) {
                error_log($e->getMessage());
                $_SESSION['flash'] = "Erreur lors de la vérification.";
                $this->redirect('/index.php/fichefrais');
                return;
            }
        }

        try {
            $ok = FicheFrais::delete($idvisiteur, $mois);
            $_SESSION['flash'] = $ok ? 'Fiche frais supprimée.' : 'Impossible de supprimer.';
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash'] = 'Erreur lors de la suppression.';
        }
        $this->redirect('/index.php/fichefrais');
    }

    // ─── Actions de changement d'état (comptable uniquement) ───────────────

    public function validate($idvisiteur, $mois): void
    {
        $this->requireComptable();
        $this->changerEtat($idvisiteur, $mois, 3, 'Validé');
    }

    public function refuse($idvisiteur, $mois): void
    {
        $this->requireComptable();
        $this->changerEtat($idvisiteur, $mois, 10, 'Refusé');
    }

    public function cloture($idvisiteur, $mois): void
    {
        $this->requireComptable();
        $this->changerEtat($idvisiteur, $mois, 2, 'Clôturé');
    }

    public function rembourse($idvisiteur, $mois): void
    {
        $this->requireComptable();
        $this->changerEtat($idvisiteur, $mois, 7, 'Remboursé');
    }

    private function changerEtat(string $idvisiteur, string $mois, int $idEtat, string $libelle): void
    {
        try {
            $ok = FicheFrais::setEtat($idvisiteur, $mois, $idEtat);
            $_SESSION['flash'] = $ok ? "Fiche passée en « $libelle »." : "Impossible de changer l'état.";
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash'] = "Erreur technique lors du changement d'état.";
        }
        $this->redirect('/index.php/fichefrais');
    }
}

/*
final class FicheFraisController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['uid'])) {
            $this->redirect('/index.php/');
        }

        try {
            $ficheFrais = FicheFrais::findAll(); // appel statique aligné avec le modèle
        } catch (\Throwable $e) {
            //Pour déboguer, active temporairement la ligne suivante :
            error_log($e->getMessage());
            $_SESSION['flash'] = 'Impossible de charger les fiches frais.';
            $ficheFrais = [];
        }

        $this->render('fichefrais/index', [
            'title'   => 'Liste des frais forfait',
            'ficheFrais'   => $ficheFrais,
            'message' => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }

 public function show($idvisiteur, $mois): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/index.php/');


    try {
        $ficheFrais = \Models\FicheFrais::findById($idvisiteur,$mois);
        if (!$ficheFrais) {
            $this->redirect('/index.php/fichefrais');
            return;
        }
    } catch (\Throwable $e) {
        error_log($e->getMessage()); // utile en debug
        $_SESSION['flash'] = 'Erreur lors du chargement de la fiche frais.';
        $ficheFrais = null;
    }

    $this->render('fichefrais/show', [
        'title' => 'Détail du frais forfait',
        'ficheFrais'  => $ficheFrais,
        'message' => $_SESSION['flash'] ?? '',
    ]);
    unset($_SESSION['flash']);
}

// l'affichage du formulaire.

public function create(): void
    {
        if (empty($_SESSION['uid'])) $this->redirect('/index.php/');// redirect, render= fichier index qui redirige

        $this->render('fichefrais/create', [ // va afficher la vue
            'title'   => 'Créer un frais',
            'message' => $_SESSION['flash'] ?? '', // flash= erreur
            'old'     => $_SESSION['old'] ?? ['libelle' => ''],
            'errors'  => $_SESSION['errors'] ?? [],
        ]);

        unset($_SESSION['flash'], $_SESSION['old'], $_SESSION['errors']); //unset= est ce que vide
    }
// envoyer a la base de donné.

    public function store(): void

{
    if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

    $visiteur = trim($_POST['visiteur'] ?? '');
    $mois = $_POST['mois'] ?? '';
    $nbrJustificatifs = trim($_POST['nbrJustificatifs'] ?? '');
    $montantValide = $_POST['montantValide'] ?? '';
    $dateModif = trim($_POST['dateModif'] ?? '');
    $fraisHorsForfait = $_POST['fraishorsforfait'] ?? '';
    $etat = trim($_POST['etat'] ?? '');
    
    if ($libelle === '') {
        $errors['libelle'] = 'Le libellé est obligatoire.';
    } elseif (mb_strlen($libelle) > 100) {
        $errors['libelle'] = 'Le libellé ne doit pas dépasser 100 caractères.';
    }

     if ($montant === '') {
        $errors['montant'] = 'Le montant est obligatoire.';
    } elseif ( $montant <= 0) {
        $errors['montant'] = 'Le montant ne doit pas etre negatif.';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old']    = ['libelle' => $libelle,'montant'=> $montant];
        $_SESSION['flash']  = 'Merci de corriger les erreurs du formulaire.';
        $this->redirect('/index.php/fichefrais/create');
    }

    try { // si ca marche
        $id = \Models\FicheFrais::create($libelle,$montant); 
        $_SESSION['flash'] = 'Frais créé avec succès.';
        $this->redirect('/index.php/fichefrais/' . $id);
    } catch (\Throwable $e) { // si ca marche pas
        $_SESSION['flash'] = 'Impossible de créer le frais.';
        $this->redirect('/index.php/fichefrais');
    }
}

  // ---------- EDIT (GET) ----------
public function edit($id,$mois): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

    $id = (int)$id;

    try {
        $ficheFrais = \Models\FicheFrais::findById($id,$mois);
        if (!$ficheFrais) {
            $_SESSION['flash'] = "Frais forfait introuvable.";
            $this->redirect('/index.php/fichefrais');
        }
    } catch (\Throwable $e) {
        $_SESSION['flash'] = "Erreur lors du chargement du frais forfait.";
        $this->redirect('/index.php/fichefrais');
    }

    // remplissage auto
    $old = $_SESSION['old'] ?? [
        'nbrJustificatifs' => $ficheFrais['nbrJustificatifs'],
        'montantValide'    => $ficheFrais['montantValide'],
        'dateModif'        => $ficheFrais['dateModif'],
        ];


    $this->render('fichefrais/edit', [
        'title'   => 'Modifier un frais forfait',
        'ficheFrais'  => $ficheFrais,
        'old'     => $old,
        'errors'  => $_SESSION['errors'] ?? [],
        'message' => $_SESSION['flash'] ?? ''
    ]);



    unset($_SESSION['old'], $_SESSION['errors'], $_SESSION['flash']);
}

// ---------- UPDATE (POST) ----------
public function update($idvisiteur, $mois): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

    $nbrJustificatifs = trim($_POST['nbrJustificatifs'] ?? '');
    $montantValide    = trim($_POST['montantValide'] ?? '');
    $dateModif        = trim($_POST['dateModif'] ?? '');

    $errors = [];

    if ($nbrJustificatifs === '') {
        $errors['nbrJustificatifs'] = 'Le nombre de justificatifs est obligatoire.';
    }
    if ($montantValide === '') {
        $errors['montantValide'] = 'Le montant est obligatoire.';
    }

    if ($errors) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old']    = compact('nbrJustificatifs', 'montantValide', 'dateModif');
        $_SESSION['flash']  = "Merci de corriger les erreurs.";
        $this->redirect("/fichefrais/$idvisiteur/$mois/edit");
    }

    try {
        \Models\FicheFrais::update($idvisiteur, $mois, $nbrJustificatifs, $montantValide, $dateModif);
        $_SESSION['flash'] = "Fiche frais modifiée avec succès.";
        $this->redirect("/fichefrais/$idvisiteur/$mois");
    } catch (\Throwable $e) {
        error_log($e->getMessage());
        $_SESSION['flash'] = "Erreur lors de la mise à jour.";
        $this->redirect("/fichefrais");
    }
}



    public function delete($id): void
{
    if (empty($_SESSION['uid'])) {
        $this->redirect('/index.php/');
    }

    $id = (int)$id;

    try {
        $ok = \Models\FicheFrais::delete($id);

        if ($ok) {
            $_SESSION['flash'] = " Frais forfait supprimé avec succès.";
        } else {
            $_SESSION['flash'] = "Impossible de supprimer ce Frais forfait.";
        }
    } catch (\Throwable $e) {
        // error_log($e->getMessage());
        $_SESSION['flash'] = "Erreur lors de la suppression du Frais forfait.";
    }

    $this->redirect('/index.php/fichefrais');
}

public function validate($idvisiteur, $mois): void
{
    if (empty($_SESSION['uid'])) $this->redirect('/index.php/');

    try {
        // On appelle le modèle pour changer l'état (souvent 'VA' pour Validée dans GSB)
        $ok = \Models\FicheFrais::validate($idvisiteur, $mois);

        if ($ok) {
            $_SESSION['flash'] = "fiche frais validée!";
        } else {
            $_SESSION['flash'] = "Erreur lors de la validation.";
        }
    } catch (\Throwable $e) {
        error_log($e->getMessage());
        $_SESSION['flash'] = "Erreur technique lors de la validation.";
    }

    // Redirection vers la liste
    $this->redirect('/index.php/fichefrais');
}

}
*\