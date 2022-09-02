<?php
  session_start();
  if(!isset($_SESSION['username']))
  {
      header("LOCATION:index.php");
  }
  require "../connexion.php";
  $active=2;
  if(isset($_GET['delete']))
  {
      $id= htmlspecialchars($_GET['delete']);
      $reqMembre = $bdd->prepare("SELECT * FROM membre WHERE id=?");
      $reqMembre->execute([$id]);
      if(!$donMyMembre = $reqMembre->fetch())
      {
          $reqMembre->closeCursor();
          header("LOCATION:users.php");
      }
      $reqMembre->closeCursor();
      $delete = $bdd->prepare("DELETE FROM membre WHERE id=?");
      $delete->execute([$id]);
      $delete->closeCursor();  
      header("LOCATION:users.php?deletesuccess=".$id);
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
    <title>Tableau de bord: users</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        include("partials/header.php");
    ?>
    <main style="min-height: 90vh">
        <div class="container">
            <h1>Users</h1>
            <?php
                  if(isset($_GET['add']))
                  {
                      echo "<div class='alert alert-success'>Vous avez bien ajouté un nouvel utilisateur</div>";
                  }
                  if(isset($_GET['update']) && isset($_GET['id']))
                  {
                      echo "<div class='alert alert-warning'>Vous avez bien modifié l'utilisateur n°".$_GET['id']."</div>";
                  }
                  if(isset($_GET['deletesuccess']))
                  {
                      echo "<div class='alert alert-danger'>Vous avez bien supprimé l'utilisateur n°".$_GET['deletesuccess']."</div>";
                  }
            ?>
            <a href="userAdd.php" class='btn btn-success'>Ajouter</a>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Login</th>
                        <th>Password</th>
                        <th>Mail</th>
                        <th></th>
                    </tr>
                </thead>    
                <tbody>
                    <?php
                        $users = $bdd->query("SELECT * FROM membre");
                        while($donuser = $users->fetch())
                        {
                            echo "<tr>";
                                echo "<td>".$donuser['id']."</td>";
                                echo "<td>".$donuser['login']."</td>";  
                                echo "<td>".$donuser['mdp']."</td>";  
                                echo "<td>".$donuser['mail']."</td>";                            
                                echo "<td>";
                                    echo "<a href='userUpdate.php?id=".$donuser['id']."' class='btn btn-warning mx-2'>Modifier</a>";
                                    echo "<a href='users.php?delete=".$donuser['id']."' class='btn btn-danger mx-2'>Supprimer</a>";
                                echo "</td>";
                            echo "</tr>";    
                        }
                        $users->closeCursor();
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
