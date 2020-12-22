<?php

if (isset($_POST['submit'])) {
    $user = new User;
    $succes = $user->register($_POST['login'], $_POST['password'], $_POST['passwordcheck']);

    $status = $user->getStatus(); //gestion messages erreur
    if ($status == "strength") {
        $alert = "Le mot de passe doit contenir au moins un chiffre.";
    } elseif ($status == "notsame") {
        $alert = "Les mots de passes ne correspondent pas.";
    } elseif ($status == 'unavailable') {
        $alert = "Login déjà pris.";
    }

    if ($succes == 1) {
        header("location: template.php?page=connexion");
    }
}
?>

<main role="main" class="container">

    <div>
        <h1>INS/cription</h1>
    </div>

    <form action="template.php?page=inscription" method="post">
            <?php if (isset($alert)) : ?> <!-- Alerte erreur-->
            <div class="alert alert-dark alert-dismissible fade show" role="alert">
                <?php echo $alert; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

        <div class="row mb-3">
            <label for="login" class="form-label">Login</label>
            <input type="text" class="form-control" id="login" name="login" required>
        </div>

        <div class="row mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password"  required>
            <div id="PasswordHelp" class="form-text">Votre mot de passe doit contenir au moins un chiffre.</div>
        </div>

        <div class="row mb-3">
            <input type="password" class="form-control" id="passwordcheck" name="passwordcheck" placeholder="Confirmez le mot de passe"required>
        </div>

        <div class="row mb-3">
            <button class="btn btn-outline-secondary" type="submit" name="submit">INSCRIPTION</button>
            <p>Vous avez déjà un compte ? <a href="template.php?page=connexion">Connectez-vous.</a></p>
        </div>
    </form>

</main>
