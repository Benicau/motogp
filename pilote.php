<?php
if(!isset($_GET['id']))
{
    header("LOCATION:index.php");
}
 include("partials/header.php");
?>
<section>
        <?php   require "./connexion.php"; 
        $id = htmlspecialchars($_GET['id']);
         $pilote = $bdd->prepare("SELECT `moto`.`nom` AS ecurie, `moto`.`id` AS motoId, `moto`.`marque` AS marque, `moto`.`type` AS type, `color`.`codecolor` AS color, `moto`.`image` AS imgmoto,  `pilote`.`nom` AS nom, `pilote`.`prenom` AS prenom, `pilote`.`nationalite` AS nation, `pilote`.`date_naissance` AS dateNaissance, `pilote`.`numero` AS numero, `pilote`.`photo` as photo  FROM `moto` INNER JOIN `pilote` ON `moto`.`id`=`pilote`.`id_moto` INNER JOIN `color` ON `color`.`id_moto`=`moto`.`id` WHERE `pilote`.`id`=?");
         $pilote->execute([$id]);
         if(!$don = $pilote->fetch())
         {
             $pilote->closeCursor();
             header("LOCATION:index.php");
         }
         echo '<div class="container"><div class="row"><div
         ><h1>'.$don['nom'].' '.$don['prenom'].'</h1></div><div class="onepilot" style="border: 5px outset '.$don["color"].'">';
         echo '<div class="photoPilote"><img src="./images/'.$don['photo'].'" alt=""></div>';
         echo '<div class="ecu gros"><a href="team.php?id='.$don['motoId'].'" style="color: '.$don['color'].'">'.$don['ecurie'].'</a></div>';
         echo '<div class="numero gras">'.$don['numero'].'</div>';
         echo '<div class="marque">'.$don['marque'].'</div>';
         echo '<div class="imgMoto"><img src="./images/'.$don['imgmoto'].'" alt=""></div>';
         echo '<div class="type"><p>'.$don['type'].'</p></div>';
         $naissance=$don['dateNaissance'];
         $aujourdhui = date("Y-m-d");
         $diff = date_diff(date_create($naissance),date_create($aujourdhui));
         $dateBonFormat=date_create($naissance);
         echo '<div class="content"><p>Nom: '.$don['nom'].'</p><p>Prenom: '.$don['prenom'].'</p><p>Nationalité: '.$don['nation'].'</p><p>Date de naissance: '.$dateBonFormat->format("j/m/y").'</p><p>Age: '.$diff->format('%y').' ans</p></div>';
         echo '</div>';
         echo '</div>';
         echo '</div>';
         $pilote->closeCursor();
        ?>
<?php
 include("partials/footer.php");
?>
