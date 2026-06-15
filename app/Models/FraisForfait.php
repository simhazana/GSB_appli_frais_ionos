<?php
namespace Models;

use Config\Database;

final class FraisForfait
{
    // Méthode statique, simple et fiable
    public static function findAll(): array
    {
        $pdo = Database::get();
        $st  = $pdo->query('SELECT id, libelle, montant FROM fraisForfait ORDER BY id');
        return $st->fetchAll(); // FETCH_ASSOC déjà par défaut via Database
    }
    public static function findById(int $id): ?array
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('SELECT id, libelle, montant FROM fraisForfait WHERE id = :id');
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public static function create(string $libelle, string $montant): int
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('INSERT INTO fraisForfait (libelle, montant) VALUES (?,?)');
        $st->execute([$libelle,$montant]);
        return (int)$pdo->lastInsertId(); // ajouter un id +1

    } 

   public static function update(int $id, string $libelle,string $montant): bool
   {

        $pdo = Database::get();
        $st  = $pdo->prepare('UPDATE fraisForfait SET libelle = ?, montant= ? WHERE id = ?');
        return $st->execute([$libelle,  $montant, $id]);
}

    public static function delete(int $id): bool
{
    $pdo = Database::get();
    $st  = $pdo->prepare('DELETE FROM fraisForfait WHERE id = ?');
    return $st->execute([$id]);
}


}  

