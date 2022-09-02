<?php
  session_start();
  if(!isset($_SESSION['username']))
  {
      header("LOCATION:index.php");
  }
  if(isset($_POST['nom']))
    {
        $err = 0;
        if(empty($_FILES['cover']['name']))
        {
            $err=7;
            header("LOCATION:piloteAdd.php?error=".$err);
        }
        // test valeur vide
        if(empty($_POST['nom'])){$err=1;} else {$nom = htmlspecialchars($_POST['nom']);}
        if(empty($_POST['prenom'])){$err=2;}else { $prenom = htmlspecialchars($_POST['prenom']);}
        if(empty($_POST['dateNaissance'])){$err=3;}else{$dateNaissance = htmlspecialchars($_POST['dateNaissance']);}
        if(empty($_POST['nationalite'])){$err=4;}else{$nationalite = htmlspecialchars($_POST['nationalite']);}
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
        if(empty($_POST['ecuriePilote'])){$err=8;}else{$ecuriePilote = htmlspecialchars($_POST['ecuriePilote']);}
            //test si le pilote ou le numéro existe déja
            require "../connexion.php";
            $pilotes = $bdd->query("SELECT * FROM pilote");
            while($donPilote = $pilotes->fetch())
                {
                if(($_POST['nom']==$donPilote['nom'])&&($_POST['prenom']==$donPilote['prenom'])){$err=9;}   
                if($_POST['numeroPilote']==$donPilote['numero']){$err=10;} 
                }
                $pilotes->closeCursor();
        // pas d'erreur
        if($err==0)
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
                header("LOCATION:piloteAdd.php?error=".$err);
            }
            if($taille>$taille_maxi) // on teste la taille de notre fichier
            {
                $err=13;
                header("LOCATION:piloteAdd.php?error=".$err);
            }     
            $prenom2 = strtr($prenom,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
            $prenom2 = preg_replace('/([^.a-z0-9]+)/i', '-', $prenom2); 
            $nom2 = strtr($nom,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
            $nom2 = preg_replace('/([^.a-z0-9]+)/i', '-', $nom2); 
            $fichiercplt = $numeroPilote.'-'.$prenom2.'_'.$nom2.'@1x'.$extension;
                if(move_uploaded_file($_FILES['cover']['tmp_name'], $dossier . $fichiercplt)) 
                {
               // tout est ok, insertion dans la bdd
                    require "../connexion.php";
                    $insert = $bdd->prepare("INSERT INTO pilote(nom, prenom, nationalite, date_naissance, numero, photo, id_moto) VALUES( :nom, :prenom, :nationalite, :date_naissance, :numero, :photo, :id_moto)");
                    $insert->execute([
                        ':nom'=>$nom,
                        ':prenom'=>$prenom,
                        ":nationalite"=>$nationalite,
                        ":date_naissance"=>$dateNaissance,
                        ":numero"=>$numeroPilote,
                        ":photo"=>$fichiercplt,
                        ":id_moto"=>$ecuriePilote
                    ]);
                    $insert->closeCursor();
                    header("LOCATION:pilotes.php?add=success");
                }
                else 
                {
                    $err=11;
                    header("LOCATION:piloteAdd.php?error=".$err);
                }  
        }    
        else
            {
                header("LOCATION:piloteAdd.php?error=".$err);
            }   
    }
    else
        {
            header("LOCATION:piloteAdd.php");
        }