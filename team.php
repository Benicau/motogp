<?php 
 include("partials/header.php");
if(!isset($_GET['id']))
{
    header("LOCATION:index.php");
}
?>
<section>
<?php   require "./connexion.php"; 
$id = htmlspecialchars($_GET['id']);

$ecurie = $bdd->prepare("SELECT `moto`.`nom` AS ecurie,`moto`.`id` AS idEcu ,`color`.`codecolor` AS color, `moto`.`marque` AS marque , `moto`.`type` AS type, `moto`.`image` AS image, `pilote`.`nom` AS nom, `pilote`.`prenom` AS prenom FROM `moto` INNER JOIN `pilote` ON `moto`.`id`=`pilote`.`id_moto` INNER JOIN `color` ON `color`.`id_moto`=`moto`.`id` WHERE `moto`.`id`=?");
$ecurie->execute([$id]);
if(!$donecurie = $ecurie->fetch())
{
    $ecurie->closeCursor();
    header("LOCATION:index.php");
}
echo '<div class="col-lg-4 offset-lg-4" style="margin-top:120px">';
$testecurie=$donecurie["ecurie"];
echo '<div class="ecurie" style="background-image: url(./images/'.$donecurie["image"].')">'; 
echo '<div class="nomecu"><a href="team.php?id='.$donecurie['idEcu'].'" style="color:'.$donecurie['color'].'">'.$donecurie['ecurie'].'</a></div>';
echo '<div class="texte">';
echo '<div class="marque">'.$donecurie['marque'].'</div>';
echo '<div class="type">'.$donecurie['type'].'</div>';
echo '<div class=membres>';
$nom = $bdd->query("SELECT  pilote.id AS id , pilote.nom AS nom, pilote.prenom AS prenom FROM moto INNER JOIN pilote ON pilote.id_moto = moto.id WHERE moto.nom LIKE '".$donecurie['ecurie']."'");
while($donnom = $nom->fetch())
{
    echo '<div class="membre"><a href="pilote.php?id='.$donnom["id"].'">'.$donnom["nom"].' '.$donnom["prenom"].'</a></div>';
}
echo'</div></div></div></div>';
$ecurie->closeCursor();
?>
<?php 
 include("partials/footer.php");
?>