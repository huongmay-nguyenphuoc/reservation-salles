<?php
require_once('config.php');

class User
{
    private $login;
    private $password;
    private $passwordcheck;
    private $id;
    private $status;
    private $bdd;

    function __destruct() {
        $this->bdd = NULL;
    }

    function register($login, $password, $passwordcheck)
    {
        try {
            $this->bdd = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                DB_USER, DB_PASSWORD);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        $this->login = htmlspecialchars($login);
        $this->password = htmlspecialchars($password);
        $this->passwordcheck = htmlspecialchars($passwordcheck);
        $stmt = $this->bdd->prepare('SELECT login FROM utilisateurs WHERE login= ?');
        $stmt->execute([$this->login]);
        $userExists = $stmt->rowCount();

        if ($userExists == 0) {

            if (!preg_match("#[0-9]+#", $this->password)) {
                $this->status = "strength";
                return 0;
            } elseif ($this->password != $this->passwordcheck) {
                $this->status = "notsame";
                return 0;
            } else {
                $hashed_password = password_hash($this->password, PASSWORD_BCRYPT, ["cost" => 10]);
            }

            $stmt = $this->bdd->prepare('INSERT INTO utilisateurs (login, password) VALUES (?,?)');
            $stmt->execute(array($this->login, $hashed_password));
            $this->status = "Enregistrement réussi.";
            return 1;

        } else {
            $this->status = "unavailable";
            return 0;
        }
    }


    function connect($login, $password)
    {
        try {
            $this->bdd = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                DB_USER, DB_PASSWORD);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        $this->login = htmlspecialchars($login);
        $this->password = htmlspecialchars($password);
        $stmt = $this->bdd->prepare('SELECT * FROM utilisateurs WHERE login= ?');
        $stmt->execute([$this->login]);

        $userExists = $stmt->rowCount();
        $user = $stmt->fetch();

        if ($userExists == 1) {
            $auth = password_verify($this->password, $user['password']);
            if ($auth == 1) {
                $this->status = "connecte";
                $this->id = $user['id'];
                return 1;
            } else {
                $this->status = "password";
                return 0;
            }
        } else {
            $this->status = "login";
            return 0;
        }
    }


    function update($login, $password, $passwordcheck)
    {
        try {
            $this->bdd = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                DB_USER, DB_PASSWORD);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        $login = htmlspecialchars($login);
        $this->password = htmlspecialchars($password);
        $this->passwordcheck = htmlspecialchars($passwordcheck);
        $stmt = $this->bdd->prepare('SELECT * FROM utilisateurs WHERE login= ?');
        $stmt->execute([$login]);
        $userExists = $stmt->rowCount();
        $userfetch = $stmt->fetch();

        if ($userExists == 0 or $this->id == $userfetch['id']) {

            if (!preg_match("#[0-9]+#", $this->password)) {
                $this->status = "strength";
                return 0;
            } elseif ($this->password != $this->passwordcheck) {
                $this->status = "notsame";
                return 0;
            } else {
                $hashed_password = password_hash($this->password, PASSWORD_BCRYPT, ["cost" => 10]);
                $this->login = $login;
            }

            $stmt = $this->bdd->prepare('UPDATE utilisateurs SET login = ?, password = ? WHERE id = ?');
            $stmt->execute([$this->login, $hashed_password, $this->id]);
            $this->status = "update";
            return 1;
        } else {
            $this->status = "login";
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
}