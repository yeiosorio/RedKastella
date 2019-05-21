
  <?php 

        $loggedUser = AuthComponent::user();  

        $adminUser = $this->requestAction(array('controller'=>'Users','action'=>'getUserById', 421));

        $adminUser = $adminUser['User'];
   
        $baseUrl = Router::url('/', true);

  ?>

 <!-- Fixed navbar -->
    <div class="navbar navbar-main navbar-primary navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
     
          <a href="#sidebar-menu" data-effect="st-effect-1" data-toggle="sidebar-menu" class="toggle pull-left visible-xs">
          
            <!-- <i class="fa fa-ellipsis-v"></i> -->

            <img src="<?php echo $baseUrl; ?>img/logo-main.png" width="35px;" />
          
          </a>
     
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <a class="navbar-brand" href="" style="background-color: white;">
            <!-- <img class="img-logo-left-menu" src="<?php // echo $baseUrl; ?>img/left-side-logo.jpeg" />  -->
 
            <img class="img-logo-left-menu" src="<?php echo $baseUrl; ?>img/logo_K_S.svg" /> 
        
        <!-- logo_p_w -->

          </a>



      


   

          <!-- no-display -->
          <!-- right-menu-link -->


           <!-- btn-add-publication-resp no-display  -->

   

           
          <!-- <a href="#sidebar-chat" data-toggle="sidebar-menu" data-effect="st-effect-1" class="toggle pull-right visible-xs"><i class="fa fa-comments"></i></a> -->
            
     



         


        
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="main-nav">
          

                 


      <ul class="nav navbar-nav navbar-right">

   

<!--             <li class="hidden-xs">
              <a href="#"  data-effect="st-effect-1" class="getTutorial">
                <i class="fa fa-question " aria-hidden="true"></i>
              </a>
            </li>

            <li class="hidden-xs">
              <a href="#sidebar-chat" data-toggle="sidebar-menu" data-effect="st-effect-1">
                <i class="fa fa-comments"></i>
              </a>
            </li>
 -->
<!--             <li class="no-display add-publication-global" >

              <a href="#"    class="btn-add-publication ">
                <i class="fa fa-plus"></i>
              </a>
            
            </li>  
 -->


<!--             <li class="hidden-xs ">
              
                  <a href="#sidebar-chat" data-toggle="sidebar-menu" data-effect="st-effect-1">
                    <i class="fa fa-search"></i>
                  </a>

                  <div class="main-search-cont no-display " style="float: left;">
          
                    <input type="text" style="display: inline; float: left; margin-top: 9px; width: 0px;" placeholder="Buscar..." class="form-control jm-search ">

                    <div class="jm-search-suggestions no-display"></div>
                  

                  </div>    

                    <a href="#"  class="global-search-button" style="display: inline; float: left;">
                    <i class="fa fa-search"></i>
                  </a>

            </li>
 -->

<!-- 
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
 -->
            <!-- <li class="dropdown notifications updates hidden-xs hidden-sm ">
              <a href="#" class="dropdown-toggle notification-list-items" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell-o"></i>

                
                <span  class="no-display g-notifications">
                  <span class="badge floating badge-danger number-noti">4</span>
                </span>

              </a>
              <ul class="dropdown-menu content-notifications" >



        


                
              </ul>
            </li> -->


            <?php if (isset($adminUser)) : ?>
            <!-- User -->  
            <li class="dropdown">
              <a href="#" class="dropdown-toggle user" data-toggle="dropdown">

              <!-- Imagen de perfil -->
              <div class="circled-image edit-pic-prof" style="background-image:url('<?php echo $adminUser['profilePic']; ?>')"></div>
              
              RedKastella

              <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <!--<li><a href="user-private-profile.html">Profile</a></li>-->
                    
                <!-- <li> -->
                  <?php 
                    // echo $this->Html->link("Mi perfil", array('controller' => 'users','action' => 'profile',$loggedUser['username']));
                  ?>
                <!-- </li> -->
                <!--<li><a href="user-private-messages.html">Messages</a></li>-->

                <!--<li><a href="login.html">Logout</a></li>-->
                <li>
                  
                  <a href="http://redkastella.com/" target="_blank">Registro</a>

            
                </li>
              </ul>
              
            </li>
            <?php endif; ?>



          </ul>

        </div>
        <!-- /.navbar-collapse -->

      </div>
    </div>





<!-- Modal búsqueda -->

<div class="modal fade search-modal " tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-margin-top">

      <div class="modal-header modal-pub-header">
          
        <!-- User profile picture -->
        <a href="<?php echo $baseUrl.'users/profile/'.$loggedUser['username']; ?>">
          <div class="post-user-picture modal-profile-img" style="background-image:url(<?php echo $loggedUser['profilePic']; ?>)"></div>
        </a>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Búsqueda</h4>
      
      </div>
    
      <div class="modal-body">
       
      <div class="panel-body">

        <div class="row">
          
          <div class="col-md-12">
              
            <div class="main-search-cont" style="float: left; width: 100%; height: 350px;">
            
                      <!-- Entrada de búsqueda -->
                      <input type="text" style="display: inline; float: left; margin-top: 9px; width: 100% !important;" placeholder="Buscar..." class="form-control jm-search jm-search-modal" />
                      
                      <!-- contenedor de resultados -->
                      <div class="jm-search-suggestions-modal" style="margin-top: 50px; border: 1px solid #AFAFAF;">
                        
                      </div>
            
            </div>
          </div>

        </div>
       
       </div>

       </div>

    </div>
  </div>
</div>

<!-- Fin modal búsqueda  -->







