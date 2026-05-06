# PPE — Installation rapide

1. Copie le projet dans un dossier.

2. Va dans `Database.php` et renseigne tes identifiants de base de données.

3. Lance le serveur PHP depuis le dossier public :

      `php -S localhost:8000`


(ou configure Apache/Nginx pour pointer vers public/).

4. Ouvre l’application dans ton navigateur :
http://localhost:8000

### Compte administrateur créé :

Identifiant : `alice.m`

Mot de passe : `hash_pwd_1`

5. Accès à la page /etat selon ton environnement :

Via la ligne de commande PHP :
http://localhost:8000/public/etat

Avec WAMP : place le projet dans le dossier www, puis ouvre
http://localhost/ppe/public/etat

Avec XAMPP : place le projet dans le dossier htdocs, puis ouvre
http://localhost/ppe/public/etat


