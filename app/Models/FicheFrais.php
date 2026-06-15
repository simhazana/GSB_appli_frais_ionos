<?php
namespace Models;

use Config\Database;

final class FicheFrais
{
    // Toutes les fiches (comptable)
    public static function findAll(): array
    {
        $pdo = Database::get();
        $st  = $pdo->query('
            SELECT
                f.IDvisiteur,
                f.mois,
                f.nbrJustificatifs,
                f.montantValide,
                f.dateModif,
                CONCAT(v.NOM, \' \', v.PRENOM) AS nomVisiteur,
                fhf.libelle AS libelleHorsForfait,
                e.libelle   AS libelleEtat
            FROM fichefrais f
            JOIN visiteur        v   ON f.IDvisiteur             = v.ID
            JOIN fraishorsforfait fhf ON f.idLigneFraisHorsForfait = fhf.ID
            JOIN etat            e   ON f.idEtat                 = e.ID
        ');
        return $st->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Fiches d'un visiteur spécifique
    public static function findByVisiteur(int $idVisiteur): array
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('
            SELECT
                CONCAT(v.NOM, \' \', v.PRENOM) AS nomVisiteur,
                f.IDvisiteur,
                f.mois,
                f.nbrJustificatifs,
                f.montantValide,
                f.dateModif,
                lff.quantite,
                ff.libelle   AS libelleForfait,
                ff.montant   AS montantForfait,
                fhf.libelle  AS libelleHorsForfait,
                e.libelle    AS libelleEtat
            FROM fichefrais f
            JOIN visiteur         v   ON f.IDvisiteur             = v.ID
            JOIN etat             e   ON f.idEtat                 = e.ID
            JOIN fraishorsforfait  fhf ON f.idLigneFraisHorsForfait = fhf.ID
            JOIN lignefraisforfait lff ON f.IDvisiteur = lff.IDvisiteur
                                      AND f.mois       = lff.mois
            JOIN fraisforfait      ff  ON lff.IDfraisforfait       = ff.ID
            WHERE f.IDvisiteur = ?
        ');
        $st->execute([$idVisiteur]);
        return $st->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Une fiche par visiteur + mois
  public static function findById(int $idvisiteur, int $mois): ?array
{
    $pdo = Database::get();

    // Infos générales de la fiche
    $st = $pdo->prepare('
        SELECT
            f.IDvisiteur,
            f.mois,
            f.nbrJustificatifs,
            f.montantValide,
            f.dateModif,
            CONCAT(v.NOM, \' \', v.PRENOM) AS nomVisiteur,
            fhf.libelle  AS libelleHorsForfait,
            fhf.montant  AS montantHorsForfait,
            e.libelle    AS libelleEtat
        FROM fichefrais f
        JOIN visiteur        v   ON f.IDvisiteur             = v.ID
        JOIN fraishorsforfait fhf ON f.idLigneFraisHorsForfait = fhf.ID
        JOIN etat            e   ON f.idEtat                 = e.ID
        WHERE f.IDvisiteur = :idV AND f.mois = :mois
    ');
    $st->execute(['idV' => $idvisiteur, 'mois' => $mois]);
    $fiche = $st->fetch(\PDO::FETCH_ASSOC);
    if (!$fiche) return null;

    // Lignes forfait liées
    $st2 = $pdo->prepare('
        SELECT
            ff.libelle  AS libelleForfait,
            ff.montant  AS montantForfait,
            lff.quantite
        FROM lignefraisforfait lff
        JOIN fraisforfait ff ON lff.IDfraisforfait = ff.ID
        WHERE lff.IDvisiteur = :idV AND lff.mois = :mois
    ');
    $st2->execute(['idV' => $idvisiteur, 'mois' => $mois]);
    $fiche['lignesForfait'] = $st2->fetchAll(\PDO::FETCH_ASSOC);

    return $fiche;
}
    // Création complète
    public static function createFull(
        int    $idVisiteur,
        int    $idFraisForfait,
        int    $quantite,
        int    $idLigneFraisHorsForfait,
        string $dateModif,
        int    $nbrJustificatifs,
        int    $idEtat
    ): void {
        $pdo  = Database::get();
        $mois = (int)date('Ym');

        // Montant frais forfait
        $st0 = $pdo->prepare('SELECT montant FROM fraisforfait WHERE ID = ?');
        $st0->execute([$idFraisForfait]);
        $montantForfait = $st0->fetchColumn();

        // Montant frais hors forfait
        $st1 = $pdo->prepare('SELECT montant FROM fraishorsforfait WHERE ID = ?');
        $st1->execute([$idLigneFraisHorsForfait]);
        $montantHorsForfait = $st1->fetchColumn();

        // Calcul
        $montantValide = ($montantForfait * $quantite) + $montantHorsForfait;

         $st = $pdo->prepare('
            INSERT INTO fichefrais
                (IDvisiteur, idLigneFraisHorsForfait, dateModif,  nbrJustificatifs, idEtat, montantValide, mois)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $st->execute([$idVisiteur, $idLigneFraisHorsForfait, $dateModif, $nbrJustificatifs, 8, $montantValide, $mois]);

        // 2. Insérer la ligne forfait liée
        $st2 = $pdo->prepare('
            INSERT INTO lignefraisforfait (IDvisiteur, IDfraisforfait, quantite,mois)
            VALUES (?, ?, ?, ?)
        ');
        $st2->execute([$idVisiteur, $idFraisForfait, $quantite, $mois]);
        }

    public static function update(string $idvisiteur, string $mois, string $nbrJustificatifs, string $montantValide, string $dateModif): bool
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('
            UPDATE fichefrais
            SET nbrJustificatifs = ?, montantValide = ?, dateModif = ?
            WHERE IDvisiteur = ? AND mois = ?
        ');
        return $st->execute([$nbrJustificatifs, $montantValide, $dateModif, $idvisiteur, $mois]);
    }

    // Suppression par clé composite (IDvisiteur + mois)
    public static function delete(int $idvisiteur, int $mois): bool
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('DELETE FROM fichefrais WHERE IDvisiteur = ? AND mois = ?');
        return $st->execute([$idvisiteur, $mois]);
    }

    public static function setEtat(string $idvisiteur, string $mois, int $idEtat): bool
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('UPDATE fichefrais SET idEtat = ?, dateModif = NOW() WHERE IDvisiteur = ? AND mois = ?');
        return $st->execute([$idEtat, $idvisiteur, $mois]);
    }
}