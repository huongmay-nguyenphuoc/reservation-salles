<?php
    if (!isset($_SESSION['user'])) {
        header("location: template.php?page=connexion");
    } else {
        $user = $_SESSION['user'];
    }

    if (!empty($_GET)) { //préremplir le form depuis le planning

        if (isset($_GET['debut']) and $_GET['debut'] > 8 and $_GET['debut'] < 19) {
            $starthour = htmlspecialchars($_GET['debut']); //heure début
            if ($starthour < 10) {
                $starthour = "0" . $starthour;
            }
            $endhour = $starthour + 1; //heure fin
            $starthour = $starthour . ":00";
            $endhour = $endhour . ":00";
        }

        if (isset($_GET['day'])) { //date
            $month = date('Y-m');
            $date = htmlspecialchars($_GET['day']);
            $date = $month."-".$date;
        }
    }

    if (isset($_POST['submit'])) { //envoi formulaire
        $resa = new Reservation();
        $correct = $resa->checkinput($_POST['title'], $_POST['description'], $_POST['date'], $_POST['starthour'], $_POST['endhour']);
        $available = $resa->checkavaibility();

        if ($correct == 1 and $available == 1) { //envoi bdd
            $resa->booking($user->getId());
        }

        $status = $resa->getStatus(); //gestion messages erreur
        if ($status == "toolate") {
            $alert = "Pas de réservation dans le passé ni le jour-même";
        } elseif ($status == "weekend") {
            $alert = "Réservation possible seulement en semaine.";
        } elseif ($status == "toolong") {
            $alert = "Vous ne pouvez réserver qu\'une heure à la fois";
        } elseif ($status == "endtime") {
            $alert = "L'heure de fin doit être postérieure à l\'heure de début";
        } elseif ($status == "unavailable") {
            $alert = "Ce créneau n'est pas disponible.";
        } elseif ($status == "booking") {
            $alert = "Réservation confirmée.";
        }


    }

?>

<main>
    <div>
        <h1>RESERVATION</h1>
        <h5></h5>
    </div>

    <section class="container m-3">
        <div class="row align-items-center">

            <article class="col-sm">
                <form action="template.php?page=reservation-form" method="post">
                    <?php if (isset($alert)) : ?> <!-- Alerte erreur-->
                        <div class="alert alert-dark alert-dismissible fade show" role="alert">
                            <?php echo $alert; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-3">
                        <label for="title">Titre</label>
                        <input id="title" name="title" type="text" class="form-control" maxlength="50"
                               placeholder="50 caractères maximum" required>
                    </div>

                    <div class="row mb-3">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" maxlength="300"
                                  placeholder="300 caractères maximum"required></textarea>
                    </div>

                    <div class="row mb-3">
                        <label for="date">Date</label>
                        <input id="date" name="date" type="date" class="form-control" value="<?php if (isset($date)) {
                            echo $date;
                        } ?>"required>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="starthour">Heure de début</label>
                            <input class="form-control" id="starthour" name="starthour" type="time" min="08:00"
                                   max="18:00" step="3600" value="<?php if (isset($starthour)) {
                                echo $starthour;
                            } ?>" required>
                        </div>
                        <div class="col">
                            <label for="endhour">Heure de fin</label>
                            <input class="form-control" id="endhour" name="endhour" type="time" min="09:00" max="19:00"
                                   step="3600" value="<?php if (isset($endhour)) {
                                echo $endhour;
                            } ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <button class="btn btn-outline-secondary" type="submit" name="submit">RESERVER</button>
                    </div>
                </form>
            </article>

            <!-- Encart droite -->
            <article class="col-md my-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Un espace libre, un espace de CONF/iance</h5>
                        <p class="card-text">Toute CONF/ ne respectant pas la charte de notre association
                            se verra annulée et/ou interrompue.</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Réservation du lundi au vendredi</li>
                        <li class="list-group-item">Réservation la veille au plus tard</li>
                        <li class="list-group-item">De 8h à 19h</li>
                        <li class="list-group-item">Créneau fixe d'une heure</li>
                    </ul>
                    <div class="card-body">
                        <a href="template.php?page=planning" class="card-link">Voir le planning de la semaine</a>
                    </div>
                </div>
            </article>
        </div>
    </section>

</main>