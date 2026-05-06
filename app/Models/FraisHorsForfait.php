<?php
namespace Models;

use Config\Database;

final class FraisHorsForfait
{
    // Méthode statique, simple et fiable
    public static function findAll(): array
    {
        $pdo = Database::get();
        $st  = $pdo->query('SELECT id, libelle, montant, date FROM fraisHorsForfait ORDER BY id');
        return $st->fetchAll(); // FETCH_ASSOC déjà par défaut via Database
    }
    public static function findById(int $id): ?array
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('SELECT id, libelle, montant, date FROM fraisHorsForfait WHERE id = :id');
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public static function create(string $libelle, string $montant, string $date): int
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('INSERT INTO fraisHorsForfait (libelle, montant, date) VALUES (?,?,?)');
        $st->execute([$libelle,$montant,$date]);
        return (int)$pdo->lastInsertId(); // ajouter un id +1
    } 

    
    public static function update(int $id, string $libelle,string $montant,string $date): bool
   {

        $pdo = Database::get();
        $st  = $pdo->prepare('UPDATE fraisHorsForfait SET libelle = ?, montant= ?, date=? WHERE id = ?');
        return $st->execute([$libelle,  $montant, $date, $id]);

   }
    
     public static function delete(int $id): bool
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('DELETE FROM fraisHorsForfait WHERE id = ?');
        return $st->execute([$id]);
    }
}