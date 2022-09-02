<?php
  session_start();
  if(!isset($_SESSION['username']))
  {
      header("LOCATION:index.php");
  }
  if(isset($_POST['login']))
  {
    $err = 0;
    if(empty($_POST['login']))
    {
        $err=1;
    }else{
        $login = htmlspecialchars($_POST['login']);
    }

    if(empty($_POST['mdp']))
    {
        $err=2;
    }else{
        $mdp= htmlspecialchars($_POST['mdp']);
        if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,15}$/', $mdp)){$err=2;}

        $mdp= password_hash($mdp, PASSWORD_BCRYPT);
    }
    if(empty($_POST['mdp2']))
    {
        $err=3;
    }else{
        $mdp2= htmlspecialchars($_POST['mdp2']);
    }
    if($_POST['mdp']!=$_POST['mdp2']){$err=7;}
    if(empty($_POST['mail']))
    {
        $err=4;
    }else{
        $mail = htmlspecialchars($_POST['mail']);
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){$err=8;}
        
    }
    require "../connexion.php";
    $users = $bdd->query("SELECT * FROM membre");
    while($donuser = $users->fetch())
    {
       if($_POST['login']==$donuser['login']){$err=5;}   
       if($_POST['mail']==$donuser['mail']){$err=6;} 
    }
    $users->closeCursor();

    if($err==0)
    {
        require "../connexion.php";
        $insert = $bdd->prepare("INSERT INTO membre(login, mdp, mail) VALUES(:login,:mdp,:mail)");
        $insert->execute([
            ':login'=>$login,
            ':mdp'=>$mdp,
            ":mail"=>$mail
        ]);
        $insert->closeCursor();
        header("LOCATION:users.php?add=success");  
    }else{
        header("LOCATION:userAdd.php?error=".$err);
    }
  }else{
      header("LOCATION:userAdd.php");
  }