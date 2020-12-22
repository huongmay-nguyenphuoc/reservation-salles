<?php
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

date_default_timezone_set('Europe/Paris');
$days = ["", "LUNDI", "MARDI", "MERCREDI", "JEUDI", "VENDREDI"]; //array semaine
$today = date("w"); //représentation numérique du jour (Lundi = 1)

for ($i = 0; $i < 6; $i++) { //Récupération du numéro des jours de la semaine en cours
    $thisweek[$i] = date("d", mktime(0, 0, 0, date("n"), date("d") - $today + $i, date("y")));
}

$resa = new Reservation;
$infos = $resa->selectAll(); //récupère réservations de la semaine en cours
?>

<main role="main">

    <div>
        <h1>CONF/s de la semaine</h1>
    </div>


    <div class="container my-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center table-borderless">

                <?php $thead = 1; // THEAD
                if ($thead == 1) {
                    echo '<thead  class="table-light"><tr>';
                    foreach ($days as $key => $day) { //boucle entete jours
                        if ($key == 0) {
                            echo '<th></th>';
                        } else {
                            echo '<th>' . $day . ' ' . $thisweek[$key] . '</th>'; //affichage jour+numéro
                        }
                    }
                    echo '</tr></thead>';
                    $thead = 0;
                }

                echo "<tbody>"; //TBODY

                for ($row = 8; $row < 19; $row++) { // boucle lignes du tableau
                    echo '<tr>';

                    foreach ($days as $key => $day) { //boucle cellules

                        if ($key > 0 and $key <= $today) {
                            $dispo = 0; // on stocke les créneaux des jours précédents indisponibles
                        } else {
                            $dispo = 1; //on stocke les créneaux disponibles
                        }

                        echo '<td>';
                        if ($key == 0) { //première colonne, affichage des horaires
                            echo $row . '-' . ($row + 1) . 'h';
                        } else { // affichage des créneaux réservés
                            for ($i = 0; $i < count($infos); $i++) { //boucle sur les réservations de la semaine
                                if ($infos[$i]['debut'] == $row and $infos[$i]['day'] == $key) {
                                    echo '<em>'.$infos[$i]['titre'] . '</em><br>
                                    par : ' . $infos[$i]['login'] . '<br>
                                    <a href="template.php?page=reservation&id=' . $infos[$i]['id'] . '">Voir</a>';
                                    $dispo = 2;
                                }
                            }
                        }

                        if ($dispo == 1 and $key > 0) { // affichage d'un + pour les créneaux disponibles
                            echo '<a class="text-decoration-none" href="template.php?page=reservation-form&debut=' . $row . '&day=' . $thisweek[$key] . '">+</a>';
                        } elseif ($dispo == 0) {
                            echo '-'; //affichage d'un - pour les créneaux indisponibles
                        }
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                echo '</tbody>';
                ?>
            </table>
        </div>
    </div>
</main>