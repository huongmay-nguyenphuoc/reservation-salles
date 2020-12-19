<?php
if (!isset($_SESSION['user']) OR empty($_GET['id'])) {header("location: template.php?page=connexion"); }

$resa = new Reservation;
$infos = $resa->select($_GET['id']);

?>
<main role="main">
    <div>
        <h1><?php echo $infos['titre'];?></h1>
    </div>
    <div class="container">


<div class="table-responsive">
<table class="table">
    <thead>
    <tr><th>Détails</th></tr>
    </thead>
    <tbody>
    <?php foreach ($infos as $key => $info):?>
        <tr><td><?php echo $key?></td><td><?php echo $info?></td></tr>
    <?php endforeach;?>
    </tbody>
</table>
</div>
    </div>
</main>