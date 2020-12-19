<?php
if (!isset($_SESSION['user'])) {header("location: template.php?page=connexion"); }
$user = $_SESSION['user'];

if (isset($_POST['submit'])) {
    $succes = $user->update($_POST['login'], $_POST['password'], $_POST['passwordcheck']);
    $status = $user->getStatus();

    if ($succes == 1) {
       $_SESSION['user'] = $user;
    }
}
?>


<main role="main" class="container">
    <div>
        <h1>PROFIL DE @<?php echo $user->getLogin() ?></h1>
    </div>
    <form action="template.php?page=profil" method="post">
        <?php if (isset($status)) : ?> <!-- Alerte erreur-->
            <div class="alert alert-dark alert-dismissible fade show" role="alert">
                <?php echo $status; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif;?>

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
            <button class="btn btn-outline-secondary" type="submit" name="submit">MODIFIER</button>
        </div>
    </form>
</main>
