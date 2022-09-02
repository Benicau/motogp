<?php 
 include("partials/header.php");
?>
<section>
    <div class="container">
        <div class="row">
            <h1 class="col-lg-12">Pilotes</h1>
        </div>
        <div id="pilotes">
            <div class="row">
            <?php   require "./connexion.php"; 
                $pilote = $bdd->query("SELECT pilote.id AS id ,`pilote`.`nom` AS nom, `pilote`.`prenom` AS prenom, `pilote`.`nationalite` AS nationalite, `color`.`codecolor` AS color, `pilote`.`date_naissance` AS dateNaissance, `pilote`.`numero` AS numero, `moto`.`id` AS ecurieId, `moto`.`nom` AS ecurie, `pilote`.`photo` AS photo FROM `pilote` INNER JOIN `moto` ON `pilote`.`id_moto`=`moto`.`id` INNER JOIN `color` ON `color`.`id_moto`=`moto`.`id` ORDER BY numero");
                while($donpilote = $pilote->fetch())
                {
                echo '<div class="fond col-lg-3">'; 
                echo'<a href="pilote.php?id='.$donpilote["id"].' " style="border: 5px outset '.$donpilote["color"].'">';
                echo'<div class="pilote" style="background-image: url(./images/'.$donpilote["photo"].')">';    
                // Affichage initial
                $nom=$donpilote['nom'];
                $prenom=$donpilote['prenom'];
                echo '<div class="initial">'.$nom[0].$prenom[0].$donpilote['numero'].'</div><div class="texte">';
                echo '<div class="nom">'.$donpilote["nom"].'</div>'.'<div class="prenom">'.$donpilote["prenom"].'</div>';
                $nation=$donpilote['nationalite'];
                echo '<div class="nation">'.$nation.'</div>';
                //calcul age
                $naissance=$donpilote['dateNaissance'];
                $aujourdhui = date("Y-m-d");
                $diff = date_diff(date_create($naissance),date_create($aujourdhui));
                echo '<div class="age">Age: '.$diff->format('%y').'</div>';
                echo '<div class="numero">'.$donpilote['numero'].'</div>';
                echo'</div>';
               echo'</div>';
               echo '</a>';
               echo '<div class="link text-center"><a href="team.php?id='.$donpilote['ecurieId'].'" style="color: '.$donpilote["color"].'">'.$donpilote['ecurie'].'</a></div>';
               echo '</div>';
                }
                $pilote->closeCursor();
  ?>
         </div>
    </div>
    </div>
<?php 
 include("partials/footer.php");
?>