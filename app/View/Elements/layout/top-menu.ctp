<?php 

$loggedUser = AuthComponent::user();  
$baseUrl = Router::url('/', true);

?>
<div class="container-fluid menu-interno" style="background-color: #E6E6E6;">
    <nav class="navbar navbar-expand-lg navbar-light /*bg-light*/">
      <a class="navbar-brand" href="<?=$baseUrl?>" style="width:179px;height:37px;margin-bottom:16px;margin-top:12px;"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
              <a class="nav-link" href="<?=$baseUrl?>/Home/searchContracts" style="padding: 6px 32px 6px 30px; "><i class="fas fa-globe"></i>&nbsp;Buscador Global <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item border rounded" role="presentation" style="background-color: rgb(255,102,0);border: none !important; margin-left: 45px; margin-right: 17px;">
                <a onclick="showModalPlans();" class="nav-link btnGoPremium" href="#" style="color: white; padding: 6px 32px 6px 32px; ">
                  <img style="width: 24px;" src="<?=$baseUrl?>/img/subscription.svg"> &nbsp; Planes
                </a>
            </li>
        </ul>
            <a href="#" onclick="goToFavorites();" class="favorito"><span><img src="<?=$baseUrl?>img/estrella-favoritos.svg"></span>Favoritos</a>
            <a href="#"><span><img src="<?=$baseUrl?>/img/mensaje.svg"></span></a><a href="#"><span><img src="<?=$baseUrl?>/img/notificaciones.svg"></span></a>
            <span class="navbar-text">
            <div class="dropdown">
            <?php if ($loggedUser) { ?>
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button"><?= $loggedUser['username'] ?></button>
            <?php }else{ ?>
                <a data-toggle="modal" data-target="#modalLogin" class="nav-link btnLogin btnGoPremium" href="#">INGRESAR</a>
            <?php } ?>          
              
              <div class="dropdown-menu" role="menu" style="padding: 16px; ">
                <label role="presentation" href="index.html" style="color: #ff6600;;">Plan Platinium</label>
                <hr>
              <a class="dropdown-item" role="presentation" href="#">Grupos</a>
              <a class="dropdown-item" role="presentation" href="#">Mensajería</a>
              <a class="dropdown-item" role="presentation" href="#">Perfil</a>
              <hr><a class="dropdown-item" role="presentation" href="<?=$baseUrl?>/users/logout">Salir</a></div>
            </div>
        </span>
      </div>
    </nav>
</div>