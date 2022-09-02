<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.php" target="_blank">Moto Gp</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item ">
          <a class="nav-link <?php if($active==1){ echo 'active';}?>" href="dashboard.php">Tableau de bord</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($active==2){ echo 'active';}?>" href="users.php">Utilisateurs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($active==3){ echo 'active';}?>" href="ecuries.php">Ecuries</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($active==4){ echo 'active';}?>" href="pilotes.php">Pilotes</a>
        </li>
      </ul>
      <ul class='navbar-nav ms-auto'>
        <li><a href="dashboard.php?deco=ok" class="nav-link">Déconnexion</a></li>
      </ul>
    </div>
  </div>
  </nav>