<?php
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

date_default_timezone_set('Europe/Paris');
$days = ['', 'LUNDI', "MARDI", "MERCREDI", "JEUDI", "VENDREDI"];

$today = date("w"); //représentation numérique du jour
for ($i = 0; $i < 6; $i++) { //Récupération jours de la semaine en cours
    $thisweek[$i] = date("d", mktime(0, 0, 0, date("n"), date("d") - $today + $i, date("y")));
}
$resa = new Reservation;
$infos = $resa->selectAll();
?>

<main role="main">
    <div>
        <h1>CONF/s de la semaine</h1>
    </div>
    <div class="container my-3">
        <div class="table-responsive">
<table class="table">

    <?php $thead = 1;
    if ($thead == 1) { // première ligne, entête jour
        echo '<thead><tr>';
        foreach ($days as $key => $day) {
            if ($key == 0) {
                echo '<th></th>';
            } else {
                echo '<th>' . $day . ' ' . $thisweek[$key] . '</th>';
            }
        }
        echo '</tr></thead>';
        $thead = 0;
    }

    echo "<tbody>";

    for ($row = 8; $row < 19; $row++) { // boucle lignes du tableau
        echo '<tr>';

        foreach ($days as $key => $day) { //boucle cellules

            if ($key > 0 and $key <= $today) {
                $dispo = 0; // on marque les créneaux des jours précédents indisponibles
            } else {
                $dispo = 1; //on marque les créneaux disponibles
            }

            echo '<td>';
            if ($key == 0) { //première colonne, horaires
                echo $row . '-' . ($row + 1) . 'h';
            } else { // affichage des créneaux réservés
                for ($i = 0; $i < count($infos); $i++) {
                    if ($infos[$i]['debut'] == $row and $infos[$i]['day'] == $key) {
                        echo $infos[$i]['titre'] . '<br>
                        organisé par : ' . $infos[$i]['login'] . '<br>
                        <a href="template.php?page=reservation&id=' . $infos[$i]['id'] . '">Voir</a>';
                        $dispo = 2;
                    }
                }
            }
            if ($dispo == 1 and $key > 0) { // affichage des créneaux disponibles
                echo '<a href="template.php?page=reservation-form&debut=' . $row . '&day=' . $key . '">+</a>'; //affichage lien réservation
            } elseif ($dispo == 0) {
                echo '-'; //affichage des créneaux indisponibles
            }
            echo '</td>';
        }
        echo '</tr>';
        echo '</tbody>';
    }
    ?>
</table>
    </div>
    </div>
</main>