<?php
if (isset($_SESSION['user'])) {header("location: template.php?page=profil"); }
if (isset($_POST['submit'])) {
    $user = new User;
    $succes = $user->connect($_POST['login'], $_POST['password']);
    $status = $user->getStatus();

    if ($succes == 1) {
      $_SESSION['user'] = $user;
    }
}
?>


<main role="main" class="container">

    <div>
        <h1>CONN/exion</h1>
    </div>

    <form action="template.php?page=connexion" method="post">
        <?php if (isset($status)) : ?> <!-- Alerte erreur-->
            <div class="alert alert-dark alert-dismissible fade show" role="alert">
                <?php echo $status; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-3">
            <label for="login" class="form-label">Login</label>
            <input id="login" class="form-control" name="login" type="text" required>
        </div>

        <div class="row mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input id="password" class="form-control" name="password" type="password" required>
        </div>

        <div class="row mb-3">
            <button class="btn btn-outline-secondary" type="submit" name="submit">CONNEXION</button>
            <p>Vous n'avez pas encore de compte ? <a href="template.php?page=inscription">Inscrivez-vous.</a></p>
        </div>
    </form>

</main>
