<?php
    session_start();
    if(!isset($_SESSION['username']))
    {
        header("LOCATION:index.php");
    }
    if(isset($_GET['id']))
    {
        $id=htmlspecialchars($_GET['id']);
    }else{
     header("LOCATION:users.php");
    }
    require "../connexion.php";
    $req = $bdd->prepare("SELECT * FROM membre WHERE id=?");
    $req->execute([$id]);  
    if(!$don = $req->fetch())
    {
        $req->closeCursor();
        header("LOCATION:users.php");
    }
    $req->closeCursor();
    $active=2;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title><?php echo $don['login']?></title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        include("partials/header.php");   
    ?>
    <main style="min-height: 90vh">
        <div class="container">
            <h1>Modifier utilisateur</h1>
            <form action="treatmentUserUpdate.php" method="POST">
            <input type="hidden" name="id" id="id" value="<?php echo $don['id'];?>">
                <div class='my-3'>
                    <label for="login">Login: </label>
                    <input type="text" name="login" id="login" value="<?php echo $don['login']; ?>" class="form-control">
                </div>
                <div class='my-3'>
                    <label for="mdp">Mot de passe <span style="font-size: 11px ">(entre 8 et 15 caractères au moins 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial ($@%*+-_!) aucun autre caractère spécial possible)<span>:</label>
                    <input type="password" name="mdp" id="mdp" value="" class="form-control">
                </div>
                <div class='my-3'>
                    <label for="mdp2">Répéter le mot de passe:  </label>
                    <input type="password" name="mdp2" id="mdp2" value="" class="form-control">
                </div>
                <div class='my-3'>
                    <label for="mail">Email: </label>
                    <input type="mail" name="mail" id="mail" value="<?php echo $don['mail']; ?>" class="form-control">
                </div>
                <div class="my-3">
                    <input type="submit" value="Modifier" class="btn btn-primary">
                    <a href="users.php" class="btn btn-secondary">Retour</a>
                </div>
            </form>
            <?php
             if(isset($_GET['error']))
                {
                    if($_GET['error']==1){$message="Login vide";}
                    if($_GET['error']==2){$message="Mot de passe vide ou le format de votre mot de passe ne correspond pas";}
                    if($_GET['error']==3){$message="Vérification de mot passe vide";}
                    if($_GET['error']==4){$message="Mail vide";}
                    if($_GET['error']==5){$message="Login déja utilisé, choisir un autre login";}
                    if($_GET['error']==6){$message="Mail déja utilisé, choisir un autre mail";}
                    if($_GET['error']==7){$message="Votre mot de passe ne correspond pas";}
                    echo "<div class='alert alert-danger'>Une erreur est survenue (code error: ".$_GET['error']." ): ".$message."</div>";
                }
            ?>
        </div>
    </main>
    <?php
        include("partials/footer.php");
    ?>
</body>
</html>
