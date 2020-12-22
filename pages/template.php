<?php
require_once '../includes/User.php';
require_once '../includes/Reservation.php';
session_start();
$pages = ['accueil', 'connexion', 'inscription', 'planning', 'profil', 'reservation', 'reservation-form', 'index'];
if (!empty($_GET['page']) AND in_array($_GET['page'], $pages)) { //on récupère le nom de la page à afficher
    $thispage = $_GET["page"];
} else {
    $thispage = 'accueil';
}

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link href="../css/style.css" rel="stylesheet" media="screen">
    <title><?php echo $thispage; ?> CONF/ilo</title>
</head>

<body>
    <?php
        require_once '../includes/header.php';
        require_once $thispage . '.php'; //affichage de la page demandée
        require_once '../includes/footer.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
            crossorigin="anonymous">
    </script>
</body>
</html>
