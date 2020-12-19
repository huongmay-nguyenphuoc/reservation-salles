<main>
    <h1>CONF/ilo</h1>
    <h2>Une salle de conf libre.</h2>

    <?php if (isset($_SESSION['user'])) : ?>
    <a href='template.php?page=reservation-form' class='btn btn-lg btn-outline-secondary'>Réserver</a>
    <?php else :?>
    <a href='template.php?page=connexion' class='btn btn-lg btn-outline-secondary'>Se connecter</a>
    <?php endif;?>
</main>