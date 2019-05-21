<?php
     $loggedUser = AuthComponent::user();  
?>


  
     <!-- Fixed navbar -->
    <div class="navbar navbar-main navbar-primary navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header" style="background-color:white">
          <a href="#sidebar-menu" data-effect="st-effect-1" data-toggle="sidebar-menu" class="toggle pull-left visible-xs"><i class="fa fa-ellipsis-v"></i></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="#sidebar-chat" data-toggle="sidebar-menu" data-effect="st-effect-1" class="toggle pull-right visible-xs"><i class="fa fa-comments"></i></a>
          <!-- <a class="navbar-brand" href="index.html">Kastella</a>-->
          <?php //echo $this->Html->link(__("Kastella"), array('controller' => 'publications','action' => 'all'), array('class'=>'navbar-brand')); ?>
          <?php //echo $this->html->image('foot5.jpg', array('alt' => 'Red Kastella')); ?>
          <?php 

            if (isset($loggedUser)) {

                echo $this->Html->link(
                      
                      $this->html->image('logo-superior-kastella.jpg', 
                
                      array('alt' => 'Red Kastella', 'style'=>array('height: 100%;'))), 
                
                      array('controller' => 'Publications','action' => 'allPublications'), 
                      
                      array('class'=>'navbar-brand', 'escape'=>false)
                ); 
            
            }else {
            
                echo $this->html->image('logo-superior-kastella.jpg', 
                      array('alt' => 'Red Kastella', 'class'=>'navbar-brand', 'style'=> 
                      array('height: 100%;'))
                );
            
            }
            ?> 
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="main-nav">
          <?php /*<ul class="nav navbar-nav">
            <li><a href="../../../index.html">Themes</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pages <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li class="dropdown-header">Public User Pages</li>
                <li><a href="index.html">Timeline</a></li>
                <li><a href="user-public-profile.html">About</a></li>
                <li><a href="user-public-users.html">Friends</a></li>
                <li class="dropdown-header">Private User Pages</li>
                <li><a href="user-private-messages.html">Messages</a></li>
                <li><a href="user-private-profile.html">Profile</a></li>
                <li><a href="user-private-timeline.html">Timeline</a></li>
                <li><a href="user-private-users.html">Friends</a></li>
              </ul>
            </li>
            <li><a href="essential-buttons.html">UI Components</a></li>
            <!-- <li class="hidden-sm" data-toggle="tooltip" data-placement="bottom" title="A few Color Examples. Download includes CSS Files for all color examples & the tools to Generate any Color combination. This Color-Switcher is for previewing purposes only.">
              <ul class="skins">

                <li><span data-file="app/app" data-skin="default" style="background: #16ae9f "></span></li>

                <li><span data-file="skin-orange" data-skin="orange" style="background: #e74c3c "></span></li>

                <li><span data-file="skin-blue" data-skin="blue" style="background: #4687ce "></span></li>

                <li><span data-file="skin-purple" data-skin="purple" style="background: #af86b9 "></span></li>

                <li><span data-file="skin-brown" data-skin="brown" style="background: #c3a961 "></span></li>

                <li><span data-file="skin-default-nav-inverse" data-skin="default-nav-inverse" style="background: #242424 "></span></li>

              </ul>
            </li>--
          </ul>*/ ?>

          <ul class="nav navbar-nav navbar-right">

            <li class="hidden-xs no-display add-publication-global" >

              <!-- botón de nueva publicación -->
              <a href="#"  class="btn-add-publication">
                <i class="fa fa-plus"></i>
              </a>
            
            </li>  



            <li class="hidden-xs ">
              
              <!--
                  <a href="#sidebar-chat" data-toggle="sidebar-menu" data-effect="st-effect-1">
                    <i class="fa fa-search"></i>
                  </a>
              --> 

                  <div class="main-search-cont no-display " style="float: left;">
          
                    <!-- Entrada de búsqueda -->
                    <input type="text" style="display: inline; float: left; margin-top: 9px; width: 0px;" placeholder="Buscar..." class="form-control jm-search ">
                    
                    <!-- contenedor de resultados -->
                    <div class="jm-search-suggestions no-display"></div>
                  

                  </div>    

                  <!-- Botón de busqueda -->
                    <a href="#" onclick="return false;" class="global-search-button" style="display: inline; float: left;">
                    <i class="fa fa-search"></i>
                  </a>

        

            </li>

              <li class="dropdown notifications updates hidden-xs hidden-sm ">
              <a href="#" class="dropdown-toggle notification-users-list" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-user"></i>

                
                <span id="g-notifications-friends" class="no-display">
                  <span class="badge floating badge-danger" id="number-noti-friends"></span>
                </span>

              </a>
              <ul class="dropdown-menu" id="content-notifications-friends">

              </ul>
            </li>

            <li class="dropdown notifications updates hidden-xs hidden-sm ">
              <a href="#" class="dropdown-toggle notification-list-items" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell-o"></i>

                
                <span id="g-notifications" class="no-display">
                  <span class="badge floating badge-danger" id="number-noti"></span>
                </span>

              </a>
              <ul class="dropdown-menu" id="content-notifications">



        


                
              </ul>
            </li>


            <?php if (isset($loggedUser)) : ?>
            <!-- User -->  
            <li class="dropdown">
              <a href="#" class="dropdown-toggle user" data-toggle="dropdown">

              <!-- Imagen de perfil -->
              <div class="circled-image edit-pic-prof" style="background-image:url('<?php echo $loggedUser['profilePic']; ?>')"></div>
              
              <?php
                    echo $this->session->read('User.username'); 
              ?> 

              <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <!--<li><a href="user-private-profile.html">Profile</a></li>-->
                    
                <li><?php echo $this->Html->link("Mi perfil", array('controller' => 'users','action' => 'profile',$loggedUser['username'])); ?></li>
                <!--<li><a href="user-private-messages.html">Messages</a></li>-->

                <!--<li><a href="login.html">Logout</a></li>-->
                <li><?php echo $this->Html->link("Salir", array('controller' => 'users','action' => 'logout'), array('class' => 'logout-action')); ?></li>
              </ul>
              
            </li>
            <?php endif; ?>



          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </div>
    </div>