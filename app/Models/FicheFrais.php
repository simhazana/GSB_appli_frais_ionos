<?php
namespace Models;

use Config\Database;

final class FicheFrais
{
    // Méthode statique, simple et fiable
    public static function findAll(): array
    {
        $pdo = Database::get();
        $st  = $pdo->query('SELECT 
        FicheFrais.idVisiteur,
        FicheFrais.mois,
        FicheFrais.nbrJustificatifs,
        FicheFrais.montantValide,
        FicheFrais.dateModif,
        Fraishorsforfait.libelle AS libelleHorsForfait,
        etat.libelle AS libelleEtat
        
        FROM 
            FicheFrais 

        Join
            visiteur ON FicheFrais.idVisiteur = visiteur.id
        Join
            fraishorsforfait ON FicheFrais.idLigneFraisHorsForfait= FraisHorsForfait.id
        Join 
            Etat ON FicheFrais.idEtat = Etat.id');

        return $st->fetchAll(); 
    }
    public static function findById(int $idvisiteur,int $mois): ?array
{
    $pdo = Database::get();
    $st  = $pdo->prepare('SELECT 
        FicheFrais.idVisiteur,
        FicheFrais.mois,
        FicheFrais.nbrJustificatifs,
        FicheFrais.montantValide,
        FicheFrais.dateModif,
        FraisHorsForfait.libelle AS libelleHorsForfait,
        Etat.libelle AS libelleEtat
        FROM FicheFrais
        JOIN FraisHorsForfait ON FicheFrais.idLigneFraisHorsForfait = FraisHorsForfait.id
        JOIN Etat ON FicheFrais.idEtat = Etat.id
        WHERE FicheFrais.idVisiteur = :idV AND FicheFrais.mois = :mois');
    
    $st->execute([
        'idV'  => $idvisiteur,
        'mois' => $mois
    ]);

        $row = $st->fetch();
        return $row ?: null;
    }

    public static function create(string $libelle, string $montant): int
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('INSERT INTO ficheFrais (libelle, montant) VALUES (?,?)');
        $st->execute([$libelle,$montant]);
        return (int)$pdo->lastInsertId(); // ajouter un id +1

    } 

   public static function update(string $idvisiteur, string $mois, string $nbrJustificatifs, string $montantValide, string $dateModif): bool
{
    $pdo = Database::get();
    $st  = $pdo->prepare('UPDATE FicheFrais SET nbrJustificatifs = ?, montantValide = ?, dateModif = ? WHERE idVisiteur = ? AND mois = ?');
    return $st->execute([$nbrJustificatifs, $montantValide, $dateModif, $idvisiteur, $mois]);
}

    public static function delete(int $id): bool
{
    $pdo = Database::get();
    $st  = $pdo->prepare('DELETE FROM ficheFrais WHERE id = ?');
    return $st->execute([$id]);
}

public static function validate(string $idvisiteur, string $mois): bool
{
    $pdo = Database::get();
    // On change l'état à 'VA' (Validée). Adapte 'VA' selon les codes de ta table 'Etat'
    $st = $pdo->prepare('UPDATE FicheFrais SET idEtat = ?, dateModif = NOW() WHERE idVisiteur = ? AND mois = ?');
return $st->execute([3, $idvisiteur, $mois]);
}

}  

