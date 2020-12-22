<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">CONF/ilo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav  me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link" href="template.php?page=accueil">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="template.php?page=planning">CONF/s de la semaine</a>
                    </li>
                    <li class="nav-item"> <!-- Onglet réservation désactivé si utilisateur déconnecté-->
                        <a class="nav-link <?php if (!isset($_SESSION['user'])){echo 'disabled';}?>" href="template.php?page=reservation-form">Réserver une CONF/</a>
                    </li>

                    <?php if (!isset($_SESSION['user'])) : ?> <!-- dropdown Utilisateur déconnecté -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                Espace membre
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="template.php?page=inscription">Inscription</a></li>
                                <li><a class="dropdown-item" href="template.php?page=connexion">Connexion</a></li>
                            </ul>
                        </li>

                    <?php else: ?> <!-- dropdown utilisateur connecté-->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                Votre espace
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="template.php?page=profil">Profil</a></li>
                                <li><a class="dropdown-item" href="logout.php">Déconnexion</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                </ul>
                <span class="navbar-text">
                    CONF/iance, CONF/idence
                </span>
            </div>
        </div>
    </nav>
</header>