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
        // test valeur vide nom
        if(empty($_POST['nom'])){$err=1;} else {$nom = htmlspecialchars($_POST['nom']);}
        // test valeur vide prenom
        if(empty($_POST['prenom'])){$err=2;}else { $prenom = htmlspecialchars($_POST['prenom']);}
        // test valeur vide naissance
        if(empty($_POST['dateNaissance'])){$err=3;}else{$dateNaissance = htmlspecialchars($_POST['dateNaissance']);}
        // test valeur vide Pays
        if(empty($_POST['nationalite'])){$err=4;}else{$nationalite = htmlspecialchars($_POST['nationalite']);}
        //test valeur vide numero pilote
        if(empty($_POST['numeroPilote'])){$err=5;}else{    
            $numeroPilote = htmlspecialchars($_POST['numeroPilote']);
            if(is_numeric($numeroPilote))
            {
                if(($numeroPilote>127)||($numeroPilote<1))
                {
                    $err=5;

                }
            }
            else {
                $err=5;
            }  
        }
        //test valeur vide ecurie
        if(empty($_POST['ecuriePilote'])){$err=8;}else{$ecuriePilote = htmlspecialchars($_POST['ecuriePilote']);}
        //test photo vide
       // if(empty($_FILES['cover']['name'])){$err=7;
         //   header("LOCATION:piloteUpdate.php?error=".$err);
        //}   
            //test si le pilote ou le numéro existe déja
            require "../connexion.php";
            $pilotes = $bdd->query("SELECT * FROM pilote");
            while($donPilote = $pilotes->fetch())
                {
                    if($_POST['id']!=$donPilote['id'])
                    {
                        if(($_POST['nom']==$donPilote['nom'])&&($_POST['prenom']==$donPilote['prenom'])){$err=9;}   
                        if($_POST['numeroPilote']==$donPilote['numero']){$err=10;} 
                    }
                }
                $pilotes->closeCursor();
        // pas d'erreur
        if($err==0)
        { 
            if(empty($_FILES['cover']['name']))
            {
                require "../connexion.php";
                        $insert = $bdd->prepare("UPDATE pilote SET nom=:nom, prenom=:prenom, nationalite=:nationalite, date_naissance=:date_naissance, numero=:numero, id_moto=:id_moto WHERE id=:myid");
                        $insert->execute([
                            ':nom'=>$nom,
                            ':prenom'=>$prenom,
                            ":nationalite"=>$nationalite,
                            ":date_naissance"=>$dateNaissance,
                            ":numero"=>$numeroPilote,
                            ":id_moto"=>$ecuriePilote,
                            ":myid"=>$id
                        ]);
                        $insert->closeCursor();
                        header("LOCATION:pilotes.php?update=success&id=".$id);
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
                    // pas la bonne extension
                    $err=12;
                    header("LOCATION:piloteUpdate.php?error=".$err);
                    }       
                    if($taille>$taille_maxi) // on teste la taille de notre fichier
                    {
                    //pas la bonne taille
                    $err=13;
                    header("LOCATION:piloteUpdate.php?error=".$err);
                    }
                    $prenom2 = strtr($prenom,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                    $prenom2 = preg_replace('/([^.a-z0-9]+)/i', '-', $prenom2); 
                    $nom2 = strtr($nom,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                    $nom2 = preg_replace('/([^.a-z0-9]+)/i', '-', $nom2); 
                    $fichiercplt = $numeroPilote.'-'.$prenom2.'_'.$nom2.'@1x'.$extension;
                    if(move_uploaded_file($_FILES['cover']['tmp_name'], $dossier . $fichiercplt)) 
                    {
                        $reqPilote = $bdd->prepare("SELECT * FROM pilote WHERE id=?");
                        $reqPilote->execute([$id]);
                        if(!$donpilote = $reqPilote->fetch())
                        {
                            $reqMembre->closeCursor();
                            header("LOCATION:pilotes.php");
                        }
                        $reqPilote->closeCursor();
                        unlink("../images/".$donpilote['photo']);
                   // tout est ok, insertion dans la bdd                     
                        $insert = $bdd->prepare("UPDATE pilote SET nom=:nom, prenom=:prenom, nationalite=:nationalite, date_naissance=:date_naissance, numero=:numero, photo=:photo, id_moto=:id_moto WHERE id=:myid");
                        $insert->execute([
                            ':nom'=>$nom,
                            ':prenom'=>$prenom,
                            ":nationalite"=>$nationalite,
                            ":date_naissance"=>$dateNaissance,
                            ":numero"=>$numeroPilote,
                            ":photo"=>$fichiercplt,
                            ":id_moto"=>$ecuriePilote,
                            ":myid"=>$id
                        ]);
                        $insert->closeCursor();
                        header("LOCATION:pilotes.php?update=success&id=".$id);
                    }
                    else 
                    {
                        $err=11;
                        header("LOCATION:piloteUpdate.php?id=".$id."&error=".$err);
                    }
            }              
        }    
        else
            {
                header("LOCATION:piloteUpdate.php?id=".$id."&error=".$err);
            }      
    }
    else
        {
            //renvoi si pas de id
            header("LOCATION:piloteUpdate.php");
        }