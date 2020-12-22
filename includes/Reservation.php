<?php
require_once('config.php');

class Reservation
{
    private $title;
    private $description;
    private $start;
    private $end;
    private $status;
    private $bdd;

    function __destruct()
    {
        $this->bdd = NULL;
    }

    function checkinput($title, $description, $date, $starthour, $endhour)
    {
        $this->title = htmlspecialchars($title);
        $this->description = htmlspecialchars($description);
        $this->start = $date . " " . $starthour;
        $this->end = $date . " " . $endhour;

        date_default_timezone_set('Europe/Paris');
        $today = date('Y-m-d');
        $isweekend = date('N', strtotime($date));

        if ($date <= $today) {
            $this->status = "toolate";
            return 0;
        } elseif ($isweekend >= 6) {
            $this->status = "weekend";
            return 0;
        } elseif ((strtotime($endhour) - strtotime($starthour)) > 3600) {
            $this->status = "toolong";
            return 0;
        } elseif ($starthour >= $endhour) {
            $this->status = "endtime";
            return 0;
        } else {
            return 1;
        }
    }

    function checkavaibility()
    {
        try {
            $this->bdd = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                DB_USER, DB_PASSWORD);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        $stmt = $this->bdd->prepare('SELECT debut, fin FROM reservations WHERE debut = ? OR fin = ?');
        $stmt->execute(array($this->start, $this->end));
        $resaExists = $stmt->rowCount();

        if ($resaExists >= 1) {
            $this->status = "unavailable";
            return 0;
        } else {
            return 1;
        }
    }

    function booking($id)
    {
        try {
            $this->bdd = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                DB_USER, DB_PASSWORD);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        $stmt = $this->bdd->prepare('INSERT INTO reservations (titre, description, debut, fin, id_utilisateur) VALUES (?,?,?,?,?)');
        $stmt->execute(array($this->title, $this->description, $this->start, $this->end, $id));
        $this->status = "booking";
        return 1;
    }


    function select($id)
    {
        try {
            $this->bdd = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                DB_USER, DB_PASSWORD);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        $id = htmlspecialchars($id);
        $stmt = $this->bdd->prepare('SELECT titre, description, debut, fin, login FROM reservations INNER JOIN utilisateurs ON reservations.id_utilisateur = utilisateurs.id WHERE reservations.id= ?');
        $stmt->execute([$id]);
        $infos = $stmt->fetch(pdo::FETCH_ASSOC);

        $tableau = []; //reformatage infos
        $tableau['Titre'] = $infos['titre'];
        $tableau['Date'] = date('d m Y', strtotime($infos['debut']));
        $tableau['Heure de début'] = date('H', strtotime($infos['debut'])) . 'H';
        $tableau['Heure de fin'] = date('H', strtotime($infos['fin'])) . 'H';
        $tableau['Organisateur.ice'] = $infos['login'];
        $tableau['Description'] = $infos['description'];
        return $tableau;
    }


    function selectAll() //récupère les réservations de la semaine
    {
        try {
            $this->bdd = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                DB_USER, DB_PASSWORD);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        $jour = date("w");
        $startweek = date("Y-m-d h:i:s", mktime(0, 0, 0, date("n"), date("d") - $jour + 1, date("y")));
        $endweek = date("Y-m-d h:i:s", mktime(0, 0, 0, date("n"), date("d") - $jour + 7, date("y")));
        $stmt = $this->bdd->prepare("SELECT titre, debut, fin, login, reservations.id FROM reservations INNER JOIN utilisateurs ON reservations.id_utilisateur = utilisateurs.id WHERE debut >='$startweek' AND fin <='$endweek'");
        $stmt->execute();
        $infos = $stmt->fetchAll(pdo::FETCH_ASSOC);

        for ($i = 0; $i < count($infos); $i++) //reformate infos
        {
            $infos[$i]['day'] = date('w', strtotime($infos[$i]['debut']));
            $infos[$i]['debut'] = date('H', strtotime($infos[$i]['debut']));
            $infos[$i]['fin'] = date('H', strtotime($infos[$i]['fin']));
        }
        return $infos;
    }

    function getStatus()
    {
        return $this->status;
    }

}