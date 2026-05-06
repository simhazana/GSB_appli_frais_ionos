<?php
namespace Models;

use Config\Database;

final class Etat
{
    // Méthode statique, simple et fiable
    // findall permet de lister
    public static function findAll(): array
    {
        $pdo = Database::get();
        $st  = $pdo->query('SELECT id, libelle FROM etat ORDER BY id');
        return $st->fetchAll(); // FETCH_ASSOC déjà par défaut via Database
    }
    public static function findById(int $id): ?array
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('SELECT id, libelle FROM etat WHERE id = :id');
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }

     public static function create(string $libelle): int
    {
        $pdo = Database::get();
        $st  = $pdo->prepare('INSERT INTO etat (libelle) VALUES (?)');
        $st->execute([$libelle]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, string $libelle): bool
{
    $pdo = Database::get();
    $st  = $pdo->prepare('UPDATE etat SET libelle = ? WHERE id = ?');
    return $st->execute([$libelle, $id]);
}

public static function delete(int $id): bool
{
    $pdo = Database::get();
    $st  = $pdo->prepare('DELETE FROM etat WHERE id = ?');
    return $st->execute([$id]);
}


}
