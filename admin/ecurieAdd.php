<?php
    session_start();
    if(!isset($_SESSION['username']))
    {
        header("LOCATION:index.php");
    }
    $active=3;
    $message='';
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
    <title>Ajouter écurie</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        include("partials/header.php");
        
    ?>
    <main style="min-height: 90vh">
        <div class="container">
            <h1>Ajouter écurie</h1>
            <form action="treatmentEcurieAdd.php" method="POST" enctype="multipart/form-data">
                <div class='my-3'>
                    <label for="nom">Nom: </label>
                    <input type="text" name="nom" id="nom" value="" class="form-control">
                </div>
                <div class='my-3'>
                    <label for="marque">Marque: </label>
                    <input type="text" name="marque" id="marque" value="" class="form-control">
                </div>
                
                <div class='my-3'>
                    <label for="type">Type:  </label>
                    <select name="type" id="type">
                        <option value="Factory">Factory</option>
                        <option value="Indépendant">Indépendant</option>
                    </select>
                </div>
                <div class='my-3'>
                    <label for="color">Couleur de l'écurie: </label>
                    <input style="width : 70px" type="color" name="color" id="color" value="" class="form-control">
                </div>
                <div class='my-3'>
                    <label for="cover">Image de l'écurie:: </label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="200000">
                    <input type="file" name="cover" id="cover" class="form-control">  
                </div>
                <div class="my-3">
                    <input type="submit" value="Ajouter" class="btn btn-primary">
                    <a href="ecuries.php" class="btn btn-secondary">Retour</a>
                </div>
            </form>
            <?php
                if(isset($_GET['error']))
                {
                    if($_GET['error']==1){$message="Le nom de l'écurie est vide";}
                    if($_GET['error']==2){$message="La marque est vide";}
                    if($_GET['error']==3){$message="Le type est vide";}
                    if($_GET['error']==4){$message="La couleur est vide";}
                    if($_GET['error']==5){$message="Le fichier image est vide";}
                    if($_GET['error']==6){$message="Ajout impossible car l'image n'as pas la bonne extension";}
                    if($_GET['error']==7){$message="Ajout impossible car l'image dépasse le volume autorisé";}
                    if($_GET['error']==8){$message="Problème avec le fichier image";}
                    if($_GET['error']==9){$message="Le nom d'ecurie est déjà prise";}
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