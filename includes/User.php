<?php
require_once('config.php');

class User
{
    private $login;
    private $password;
    private $passwordcheck;
    private $id;
    private $status;

    function register($login, $password, $passwordcheck)
    {
        $bdd = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
            DB_USER, DB_PASSWORD);
        $this->login = htmlspecialchars($login);
        $this->password = htmlspecialchars($password);
        $this->passwordcheck = htmlspecialchars($passwordcheck);
        $stmt = $bdd->prepare('SELECT login FROM utilisateurs WHERE login= ?');
        $stmt->execute([$this->login]);
        $userExists = $stmt->rowCount();

        if ($userExists == 0) {

            if (!preg_match("#[0-9]+#", $this->password)) {
                $this->status = "Le mot de passe doit contenir au moins un chiffre.";
                return 0;
            } elseif ($this->password != $this->passwordcheck) {
                $this->status = "Les mots de passes ne correspondent pas.";
                return 0;
            } else {
                $hashed_password = password_hash($this->password, PASSWORD_BCRYPT, ["cost" => 10]);
            }

            $stmt = $bdd->prepare('INSERT INTO utilisateurs (login, password) VALUES (?,?)');
            $stmt->execute(array($this->login, $hashed_password));
            $bdd = NULL;
            $this->status = "Enregistrement réussi.";
            return 1;

        } else {
            $this->status = "Login déjà pris.";
            return 0;
        }
    }


    function connect($login, $password)
    {
        $bdd = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
            DB_USER, DB_PASSWORD);
        $this->login = htmlspecialchars($login);
        $this->password = htmlspecialchars($password);
        $stmt = $bdd->prepare('SELECT * FROM utilisateurs WHERE login= ?');
        $stmt->execute([$this->login]);
        $bdd = NULL;

        $userExists = $stmt->rowCount();
        $user = $stmt->fetch();

        if ($userExists == 1) {
            $auth = password_verify($this->password, $user['password']);
            if ($auth == 1) {
                $this->status = "Connexion réussie. Bienvenue @".$this->login.'<br>
                <a href="template.php?page=profil">Visiter votre profil</a>
                <a href="template.php?page=reservation">Réserver une conf</a>';
                $this->id = $user['id'];
                return 1;
            } else {
                $this->status = "Vérifiez votre mot de passe";
                return 0;
            }
        } else {
            $this->status = "Ce login n'existe pas";
            return 0;
        }
    }


    function update($login, $password, $passwordcheck)
    {
        $bdd = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
            DB_USER, DB_PASSWORD);
        $login = htmlspecialchars($login);
        $this->password = htmlspecialchars($password);
        $this->passwordcheck = htmlspecialchars($passwordcheck);
        $stmt = $bdd->prepare('SELECT * FROM utilisateurs WHERE login= ?');
        $stmt->execute([$login]);
        $userExists = $stmt->rowCount();
        $userfetch = $stmt->fetch();

        if ($userExists == 0 or $this->id == $userfetch['id']) {

            if (!preg_match("#[0-9]+#", $this->password)) {
                $this->status = "Le mot de passe doit contenir au moins un chiffre.";
                return 0;
            } elseif ($this->password != $this->passwordcheck) {
                $this->status = "Les mots de passes ne correspondent pas.";
                return 0;
            } else {
                $hashed_password = password_hash($this->password, PASSWORD_BCRYPT, ["cost" => 10]);
                $this->login = $login;
            }

            $stmt = $bdd->prepare('UPDATE utilisateurs SET login = ?, password = ? WHERE id = ?');
            $stmt->execute([$this->login, $hashed_password, $this->id]);
            $bbd = NULL;
            $this->status = "Modifications enregistrées.";
            return 1;
        } else {
            $this->status = "Ce login est déjà pris.";
            return 0;
        }
    }

    function getStatus()
    {
        return $this->status;
    }

    function getLogin()
    {
        return $this->login;
    }

    function getId()
    {
        return $this->id;
    }

    function getAllInfo()
    {
        $info = array();
        $info['id'] = $this->id;
        $info['login'] = $this->login;
        return $info;
    }

}