<?php
  session_start();
  if(!isset($_SESSION['username']))
  {
      header("LOCATION:index.php");
  }
  if(isset($_POST['id']))
  {
    $err = 0;
    $id=$_POST['id'];

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

        if($_POST['id']!=$donuser['id'])
        {
        if($_POST['login']==$donuser['login']){$err=5;}   
        if($_POST['mail']==$donuser['mail']){$err=6;} 
        }  
    }
    if($err==0)
    {    
        require "../connexion.php";
        $update = $bdd->prepare("UPDATE membre SET login=:login, mdp=:mdp, mail=:mail WHERE id=:myid");
                   $update->execute([
                    ':login'=>$_POST['login'],
                    ':mdp'=>$mdp,
                    ":mail"=>$_POST['mail'],
                    ":myid"=>$id
                   ]);
                   $update->closeCursor();
                   header("LOCATION:users.php?update=success&id=".$id);
        
    }else{ 
       header('LOCATION:userUpdate.php?error='.$err.'&id='.$id);    
    }
  }
  else{
     header("LOCATION:userUpdate.php"); 
  }