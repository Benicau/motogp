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
            $err=5;
            header("LOCATION:ecurieAdd.php?error=".$err);
        }
        if(empty($_POST['nom'])){$err=1;} else {$nom = htmlspecialchars($_POST['nom']);}
        if(empty($_POST['marque'])){$err=2;} else {$marque = htmlspecialchars($_POST['marque']);}
        if(empty($_POST['type'])){$err=3;} else {$type = htmlspecialchars($_POST['type']);}
        if(empty($_POST['color'])){$err=4;} else {$color = htmlspecialchars($_POST['color']);}
        require "../connexion.php";
            $motos = $bdd->query("SELECT * FROM moto");
            while($donmoto = $motos->fetch())
                {
                if($_POST['nom']==$donmoto['nom']){$err=9;}   
                }
                $motos->closeCursor();
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
                    $err=6;
                    header("LOCATION:ecurieAdd.php?error=".$err);
                    }
                if($taille>$taille_maxi) // on teste la taille de notre fichier
                    {
                    $err=7;
                    header("LOCATION:ecurieAdd.php?error=".$err);
                    }
                $nom2 = strtr($nom,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                $nom2 = preg_replace('/([^.a-z0-9]+)/i', '-', $nom2); 
                $fichiercplt = $nom2.'@1x'.$extension;
                if(move_uploaded_file($_FILES['cover']['tmp_name'], $dossier . $fichiercplt)) 
                    {               
                        $insert = $bdd->prepare("INSERT INTO moto(nom, marque, type, image) VALUES( :nom, :marque, :type, :image )");
                        $insert->execute([
                            ':nom'=>$nom,
                            ':marque'=>$marque,
                            ":type"=>$type,
                            ":image"=>$fichiercplt
                        ]);
                        $insert->closeCursor();
                        $idresult = $bdd->query("SELECT * FROM moto");
                        while($donresult = $idresult->fetch())
                        {
                            if($donresult['nom']==$nom)
                            {
                                $id=$donresult['id'];
                            }
                        }
                        $insert = $bdd->prepare("INSERT INTO color(codecolor, id_moto) VALUES( :codecolor, :id_moto )");
                        $insert->execute([
                            ':codecolor'=>$color,
                            ':id_moto'=>$id,
                        ]);
                        $insert->closeCursor();
                        header("LOCATION:ecuries.php?add=success");
                    }
                    else 
                    {
                        $err=8;
                        header("LOCATION:ecurieAdd.php?error=".$err);
                    }
            }
            else
            {
                header("LOCATION:ecurieAdd.php?error=".$err);
            }
    }
    else
    {
        header("LOCATION:ecurieAdd.php");
    }      
?>