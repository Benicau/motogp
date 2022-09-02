<?php
  require "../connexion.php";
  session_start();
  if(!isset($_SESSION['username']))
  {
      header("LOCATION:index.php");
  }
  $active=4;

  if(isset($_GET['delete']))
  {
      $id= htmlspecialchars($_GET['delete']);
      $reqPilote = $bdd->prepare("SELECT * FROM pilote WHERE id=?");
      $reqPilote->execute([$id]);
      if(!$donpilote = $reqPilote->fetch())
      {
          $reqMembre->closeCursor();
          header("LOCATION:pilotes.php");
      }
      $reqPilote->closeCursor();
      unlink("../images/".$donpilote['photo']);
      $delete = $bdd->prepare("DELETE FROM pilote WHERE id=?");
      $delete->execute([$id]);
      $delete->closeCursor();
      
      header("LOCATION:pilotes.php?deletesuccess=".$id);
  }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Tableau de bord: pilotes</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        include("partials/header.php");
    ?>
    <main>
        <div class="container">
            <h1>Pilotes</h1>
            <?php
                  if(isset($_GET['add']))
                  {
                      echo "<div class='alert alert-success'>Vous avez bien ajouté un nouveau pilote</div>";
                  }
                  if(isset($_GET['update']) && isset($_GET['id']))
                  {
                      echo "<div class='alert alert-warning'>Vous avez bien modifié le pilote n°".$_GET['id']."</div>";
                  }
                  if(isset($_GET['deletesuccess']))
                  {
                      echo "<div class='alert alert-danger'>Vous avez bien supprimé le pilote n°".$_GET['deletesuccess']."</div>";
                  }
            ?>      
            <a href="piloteAdd.php" class='btn btn-success'>Ajouter</a>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pilote</th>
                        <th>Nationalité</th>
                        <th>Date de naissance</th>
                        <th>Numero</th>
                        <th>Ecurie</th>
                        <th>Image</th>
                        <th></th>
                    </tr>
                </thead>            
                <tbody>
                    <?php
                        $pilotes = $bdd->query("SELECT pilote.id AS id ,`pilote`.`nom` AS nom, `pilote`.`prenom` AS prenom, `pilote`.`nationalite` AS nationalite, `color`.`codecolor` AS color, `pilote`.`date_naissance` AS dateNaissance, `pilote`.`numero` AS numero, `moto`.`id` AS ecurieId, `moto`.`nom` AS ecurie, `pilote`.`photo` AS photo FROM `pilote` INNER JOIN `moto` ON `pilote`.`id_moto`=`moto`.`id` INNER JOIN `color` ON `color`.`id_moto`=`moto`.`id` ORDER BY id");
                        while($donpilote = $pilotes->fetch())
                        {
                            echo "<tr>";
                                echo "<td>".$donpilote['id']."</td>";
                                echo "<td>".$donpilote['nom']." ".$donpilote['prenom']."</td>";  
                                echo "<td>".$donpilote['nationalite']."</td>";   
                                echo "<td>".$donpilote['dateNaissance']."</td>";
                                 echo "<td>".$donpilote['numero']."</td>"; 
                                 echo "<td style= 'background-color:".$donpilote['color'].";'>".$donpilote['ecurie']."</td>";
                                 echo "<td> <img style='height:50px;'src=../images/".$donpilote['photo']."></td>"; 
                                echo "<td>";
                                    echo "<a href='piloteUpdate.php?id=".$donpilote['id']."' class='btn btn-warning mx-2'>Modifier</a>";
                                    echo "<a href='pilotes.php?delete=".$donpilote['id']."' class='btn btn-danger mx-2'>Supprimer</a>";
                                echo "</td>";
                            echo "</tr>";    
                        }
                        $pilotes->closeCursor();
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php
        include("partials/footer.php");
    ?>
</body>
</html>
