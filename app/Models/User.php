<?php
namespace Models;
use Config\Database;

final class User {
    // mdp : hash_pwd_(num : id) EX VISITEUR id: 1 mdp= hash_pwd_1
    // EX VISITEUR id: 2 mdp= hash_pwd_2
    // EX VISITEUR id: 3 mdp= hash_pwd_3
    //EX VISITEUR id: 4 mdp= hash_pwd_4
    //EX VISITEUR id: 5 mdp= hash_pwd_5
    public static function findByUsername(string $u): ?array {
        $st = Database::get()->prepare('SELECT id, login, mdp FROM visiteur WHERE login = :l');
        $st->execute([':l'=>$u]);
        $row = $st->fetch();
        return $row ?: null;
    }

}
