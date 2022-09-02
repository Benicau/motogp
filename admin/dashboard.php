<?php
    session_start();
    if(!isset($_SESSION['username']))
    {
        header("LOCATION:index.php");
    }
    /* déconnexion de l'utilisateur */
    if(isset($_GET['deco']))
    {
        session_destroy();
        header("LOCATION:index.php?decosuccess=ok");
    }
    $active=1;
    require "../connexion.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Tableau de bord: index</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        include("partials/header.php");
    ?>
    <main style="min-height: 90vh">
        <div class="container">
            <h1>Tableau de bord</h1>     
            <div class="row">
                <div class="col-md-3 m-3 bg-primary text-center text-white">
                    <h2>Utilisateurs</h2>
                    <?php
                        $users = $bdd->query("SELECT * FROM membre");
                        $nbusers = $users->rowCount();
                        $users->closeCursor();
                    ?>        
                    <h3><?= $nbusers ?></h3>
                </div>
                <div class="col-md-3 m-3 bg-success text-center text-white">
                    <h2>Ecuries</h2>
                    <?php
                        $ecuries = $bdd->query("SELECT * FROM moto");
                        $nbecu = $ecuries->rowCount();
                        $ecuries->closeCursor();
                    ?>          
                    <h3><?= $nbecu ?></h3>
                </div>
                <div class="col-md-3 m-3 bg-warning text-center text-white">
                    <h2>Pilotes</h2>
                    <?php
                        $pilotes = $bdd->query("SELECT * FROM pilote");
                        $nbpil = $pilotes->rowCount();
                        $pilotes->closeCursor();
                    ?>
                     <h3><?= $nbpil ?></h3>              
                </div>
            </div>
        </div>
    </main>
    <?php
        include("partials/footer.php");
    ?>
</body>
</html>