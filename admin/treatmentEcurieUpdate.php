<?php
// Test si personne connecté
session_start();
    if(!isset($_SESSION['username']))
    {
        header("LOCATION:index.php");
    }
// Test si une id existe
    if(isset($_POST['id']))
    {
                $err = 0;
                $id=$_POST['id'];
                if(empty($_POST['nom'])){$err=1;} else {$nom = htmlspecialchars($_POST['nom']);}
                if(empty($_POST['marque'])){$err=2;} else {$marque = htmlspecialchars($_POST['marque']);}
                if(empty($_POST['type'])){$err=3;} else {$type = htmlspecialchars($_POST['type']);}
                if(empty($_POST['color'])){$err=4;} else {$color = htmlspecialchars($_POST['color']);}
                 
                require "../connexion.php";
                $motos = $bdd->query("SELECT * FROM moto");
                while($donmoto = $motos->fetch())
                    {
                        if($id!=$donmoto['id'])
                        {
                            if($nom==$donmoto['nom']){$err=9;}
                        }          
                    }
                $motos->closeCursor();         
                if($err==0)
                    {
                      
                        if(empty($_FILES['cover']['name']))
                            {
                                    $insert = $bdd->prepare("UPDATE moto SET nom=:nom, marque=:marque, type=:type WHERE id=:myid");
                                    $insert->execute([
                                    ':nom'=>$nom,
                                    ':marque'=>$marque,
                                    ":type"=>$type,
                                    ":myid"=>$id
                                    ]);
                                    $insert->closeCursor();
                                    $insert = $bdd->prepare("UPDATE color SET codecolor=:codecolor WHERE id_moto=:myid");
                                    $insert->execute([
                                    ':codecolor'=>$color,
                                    ":myid"=>$id
                                    ]);
                                    $insert->closeCursor();
                                    header("LOCATION:ecuries.php?update=success&id=".$id);            
                            }
                        else
                            {             
                                    $dossier = '../images/';
                                    $fichier = basename($_FILES['cover']['name']);
                                    $taille_maxi = 200000;
                                    $taille = filesize($_FILES['cover']['tmp_name']);
                                    $extensions = ['.png', '.gif', '.jpg', '.jpeg'];
                                    $extension = strrchr($_FILES['cover']['name'], '.');
                                    if(!in_array($extension, $extensions)) 
                                        {
                                            $err=6;
                                            header("LOCATION:ecurieUpdate.php?error=".$err);
                                        }       
                                    if($taille>$taille_maxi) 
                                        {
                                            $err=7;
                                            header("LOCATION:ecurieUpdate.php?error=".$err);
                                        }
                                    $nom2 = strtr($nom,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                                    $nom2 = preg_replace('/([^.a-z0-9]+)/i', '-', $nom2); 
                                    $fichiercplt = $nom2.'@1x'.$extension;
                                    if(move_uploaded_file($_FILES['cover']['tmp_name'], $dossier . $fichiercplt)) 
                                        {
                                            $reqPilote = $bdd->prepare("SELECT * FROM moto WHERE id=?");
                                            $reqPilote->execute([$id]);
                                            if(!$donpilote = $reqPilote->fetch())
                                                    {
                                                        $reqMembre->closeCursor();
                                                        header("LOCATION:ecuries.php");
                                                    }
                                            $reqPilote->closeCursor();
                                            unlink("../images/".$donpilote['image']);
                                            $insert = $bdd->prepare("UPDATE moto SET nom=:nom, marque=:marque, image=:image, type=:type WHERE id=:myid");
                                            $insert->execute([
                                            ':nom'=>$nom,
                                            ':marque'=>$marque,
                                            ":type"=>$type,
                                            ":myid"=>$id,
                                            ":image"=>$fichiercplt
                                            ]);
                                            $insert->closeCursor();
                                            $insert = $bdd->prepare("UPDATE color SET codecolor=:codecolor WHERE id_moto=:myid");
                                             $insert->execute([
                                            ':codecolor'=>$color,
                                            ":myid"=>$id
                                            ]);
                                            $insert->closeCursor();
                                            header("LOCATION:ecuries.php?update=success&id=".$id);
                                         }
                                    else 
                                        {
                                            $err=8;
                                            header("LOCATION:ecurieUpdate.php?error=".$err);
                                        }
                            } 
                    }    
                    else{
                   header("LOCATION:ecurieUpdate.php?id=".$id."&error=".$err);
                        }
    }
    else
    {
    header("LOCATION:ecurieUpdate.php");
    }
                    