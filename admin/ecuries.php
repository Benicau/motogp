
<?php
  session_start();
  if(!isset($_SESSION['username']))
  {
      header("LOCATION:index.php");
  }
  require "../connexion.php";
  $active=3;
  $pilotepass='non';
  if(isset($_GET['delete']))
  {
    $id= htmlspecialchars($_GET['delete']);
      $reqMoto = $bdd->prepare("SELECT * FROM moto WHERE id=?");
      $reqMoto->execute([$id]);
      if(!$donMoto = $reqMoto->fetch())
      {
          $reqMoto->closeCursor();
          header("LOCATION:pilotes.php");
      }
      $reqMoto->closeCursor();
      $pilote = $bdd->query("SELECT * FROM pilote");
      while($donpil = $pilote->fetch())
          {
          if($id==$donpil['id_moto']){
            $pilotepass='oui';
            header("LOCATION:ecuries.php?deletepilote=".$id);
          }   
          }
        $pilote->closeCursor();
        if($pilotepass=='non')
        {
        unlink("../images/".$donMoto['image']);
        $delete = $bdd->prepare("DELETE FROM moto WHERE id=?");
        $delete->execute([$id]);
        $delete->closeCursor();
        $delete2 = $bdd->prepare("DELETE FROM color WHERE id_moto=?");
        $delete2->execute([$id]);
        $delete2->closeCursor();
        header("LOCATION:ecuries.php?deletesuccess=".$id);
        } 
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
    <title>Tableau de bord: écurie</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        include("partials/header.php");
    ?>
    <main>
        <div class="container">
            <h1>Ecuries</h1>
            <?php
                  if(isset($_GET['add']))
                  {
                      echo "<div class='alert alert-success'>Vous avez bien ajouté une nouvelle écurie</div>";
                  }
                  if(isset($_GET['update']) && isset($_GET['id']))
                  {
                      echo "<div class='alert alert-warning'>Vous avez bien modifié l'écurie n°".$_GET['id']."</div>";
                  }
                  if(isset($_GET['deletesuccess']))
                  {
                      echo "<div class='alert alert-danger'>Vous avez bien supprimé l'écurie n°".$_GET['deletesuccess']."</div>";
                  }
                  if(isset($_GET['deletepilote']))
                  {
                      echo "<div class='alert alert-danger'>Suppression impossible, encore des pilotes dans l'écurie ".$_GET['deletepilote']."</div>";
                  }

                  ?>       
            <a href="ecurieAdd.php" class='btn btn-success'>Ajouter</a>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ecurie</th>
                        <th>Marque</th>
                        <th>Type</th>
                        <th>Image</th>
                        <th>Code couleur</th>
                        <th></th>
                    </tr>
                </thead>         
                <tbody>
                    <?php
                        $ecuries = $bdd->query("SELECT moto.id AS id ,`moto`.`nom` AS ecurie, `moto`.`marque` AS marque, `moto`.`type` AS typeMoto, `color`.`codecolor` AS color,`moto`.`nom` AS ecurie, `moto`.`image` AS photo FROM `moto`INNER JOIN `color` ON `color`.`id_moto`=`moto`.`id` ORDER BY id");
                        while($donecu = $ecuries->fetch())
                        {
                            echo "<tr>";
                                echo "<td>".$donecu['id']."</td>";
                                echo "<td>".$donecu['ecurie']."</td>";  
                                echo "<td>".$donecu['marque']."</td>";  
                                echo "<td>".$donecu['typeMoto']."</td>"; 
                                echo "<td> <img style='height:50px;'src=../images/".$donecu['photo']."></td>"; 
                                echo "<td style= 'background-color:".$donecu['color'].";'>".$donecu['color']."</td>";
                               
                                echo "<td>";
                                    echo "<a href='ecurieUpdate.php?id=".$donecu['id']."' class='btn btn-warning mx-2'>Modifier</a>";
                                    echo "<a href='ecuries.php?delete=".$donecu['id']."' class='btn btn-danger mx-2'>Supprimer</a>";
                                echo "</td>";
                            echo "</tr>";    
                        }
                        $ecuries->closeCursor();
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
