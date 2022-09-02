<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Moto Rally gp</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>
</head>
<body>
    <nav>
        <div class="container-fluid">
            <div class="row">
                <div class="col-3 photo">
                    <img src="https://static.motogp.com/riders-front/img/logo_motogp.d0eb95ab.svg" alt="">
                </div>
                <ul class="col-3">
                    <li><a href="index.php">Pilotes</a></li>
                    <li><a href="teams.php">Teams</a></li>
                </ul>
                <div class="col-6 text-end">
                    <?php
                      if(isset($_SESSION['username']))
                       {
                        echo '<a href="./admin/index.php">'.$_SESSION['username'].'</a>';  
                       }
                       else{
                        echo '<a href="./admin/index.php">Se Connecter</a>';
                       }
                    ?>    
                </div>
            </div>
        </div>
    </nav>