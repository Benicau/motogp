<?php
    session_start();
    if(!isset($_SESSION['username']))
    {
        header("LOCATION:index.php");
    }
    $active=4;
    $message='';  
    if(isset($_GET['id']))
    {
        $id=htmlspecialchars($_GET['id']);
    }else{
     header("LOCATION:pilotes.php");
    }
   require "../connexion.php";
    $req = $bdd->prepare("SELECT * FROM pilote WHERE id=?");
    $req->execute([$id]);
    
    if(!$don = $req->fetch())
    {
        $req->closeCursor();
        header("LOCATION:pilotes.php");
    }
    $req->closeCursor();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Modifier <?php echo $don['prenom'];?> <?php echo $don['nom'];?></title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        include("partials/header.php");   
    ?>
    <main style="min-height: 90vh">
        <div class="container">
            <h1>Modifier <?php echo $don['prenom'];?> <?php echo $don['nom'];?></h1>
            <form action="treatmentPiloteUpdate.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="<?php echo $don['id'];?>">
                <div class='my-3'>
                    <label for="nom">Nom: </label>
                    <input type="text" name="nom" id="nom" value="<?php echo $don['nom'];?>" class="form-control">
                </div>
                <div class='my-3'>
                    <label for="prenom">Prénom: </label>
                    <input type="text" name="prenom" id="prenom" value="<?php echo $don['prenom'];?>" class="form-control">
                </div>
                
                <div class='my-3'>
                    <label for="dateNaissance">Date de naissance:  </label>
                    <input type="date" name="dateNaissance" id="dateNaissance" value="<?php echo $don['date_naissance'];?>" class="form-control">
                </div>
                <div class='my-3'>
                    <label for="Nationalite">Pays:  </label>
                    <input type="text" name="nationalite" id="nationalite" value="<?php echo $don['nationalite'];?>" class="form-control">
                </div>
                <div class='my-3'>
                    <label for="numeroPilote">Numéro du pilote: </label>
                    <input type="number" name="numeroPilote" id="numeroPilote" value="<?php echo $don['numero'];?>" class="form-control">
                </div>
                <div class='my-3'>
                    <label for="ecuriePilote">Ecurie du pilote: </label>
                    <select name="ecuriePilote" id="ecuriePilote" class="form-control">
                        <?php
                            $moto = $bdd->query("SELECT `moto`.`id` AS id, `moto`.`nom` AS nom, `moto`.`marque` AS marque, `color`.`codecolor` AS color FROM moto INNER JOIN `color` ON `color`.`id_moto`=`moto`.`id` ");
                            while($donMoto = $moto->fetch())
                            {
                                if($don['id_moto']==$donMoto['id'])
                                {
                                    echo '<option class="text-center" style=background-color:'.$donMoto['color'].' value="'.$donMoto['id'].'" selected>'.$donMoto['nom'].'</option>';
                                }
                                else
                                {
                                    echo '<option class="text-center" style=background-color:'.$donMoto['color'].' value="'.$donMoto['id'].'">'.$donMoto['nom'].'</option>';
                                }             
                            }
                            $moto->closeCursor();
                        ?>
                    </select>
                </div>
                <div class='my-3'>
                    <div class="col-3">
                        <img src="../images/<?= $don['photo'] ?>" alt="<?= $don['nom'] ?> <?= $don['prenom'] ?>" class="img-fluid">
                    </div>
                    <label for="cover">Image du pilote: </label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="200000">
                    <input type="file" name="cover" id="cover" class="form-control">  
                </div>
                <div class="my-3">
                    <input type="submit" value="Modifier" class="btn btn-primary">
                    <a href="pilotes.php" class="btn btn-secondary">Retour</a>
                </div>
            </form>
            <?php
                if(isset($_GET['error']))
                {
                    if($_GET['error']==1){$message="Le nom est vide";}
                    if($_GET['error']==2){$message="Le prénom est vide";}
                    if($_GET['error']==3){$message="La date de naissance est vide";}
                    if($_GET['error']==4){$message="La nationalité est vide";}
                    if($_GET['error']==5){$message="Le numéro de pilote est vide ou n'est pas valide";}
                    if($_GET['error']==6){$message="Le format de la date n'est pas valide";}
                    if($_GET['error']==7){$message="Pas de fichier photo";}
                    if($_GET['error']==8){$message="L'ecurie du pilote est vide";}
                    if($_GET['error']==9){$message="Ce pilote existe déja";}
                    if($_GET['error']==10){$message="Ce numéro de pilote est déjà pris";}
                    if($_GET['error']==11){$message="Problème avec le fichier image";}
                    if($_GET['error']==12){$message="Ajout impossible car l'image n'as pas la bonne extension";}
                    if($_GET['error']==13){$message="Ajout impossible car l'image dépasse le volume autorisé";}

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