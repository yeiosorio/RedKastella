

<?php

     $loggedUser = AuthComponent::user();  


?>  

<div class="st-content-inner">    
    <div class="container-fluid">

        <!-- Invitación de usuarios -->
        <div class="col-md-12 col-lg-12 ">

          <?php 




          ?>

            <div class=" panel panel-default widget-user-1 text-center">
                <div class="panel-body ">

                <div class="avatar profile-heading" data-user-id="<?php echo $User['User']['id']; ?>" >
                    
                    <div style="background-image:url('<?php echo $User['User']['profilePic']; ?>')"  class="profile-circled-img" ></div>
                    
                    <!-- Nombre de usuario -->
                    <h3><?php echo $User['User']['name'] ." ".$User['User']['surname']; ?></h3>


                    <!-- Si el usuario que el usuario ve es diferente de el mismo -->
                    <?php if($loggedUser['id'] != $User['User']['id']):?>


                      <?php if(!empty($foundFriend)): ?>


                      <!-- Indicador de amigos -->
                        <button class="btn btn-success <?php  ?> ">
                                  <i class="fa fa-check-circle"></i> Amigos
                        </button>     


                    <?php else: ?>


                      <?php if(!empty($foundRequest)): ?>

                          <!-- Aceptar Solicitud -->
                          <button class="btn btn-success accept-friend-request <?php if(!empty($foundFriend)){ echo "no-display"; } ?>"  data-user-id="<?php echo $foundRequest['FriendRequest']['request_user_id']; ?> ">
                              <i class="fa fa-user-plus fa-fw"></i> Aceptar Solicitud
                          </button>          

                          <!-- Indicador de amigos -->
                          <button class="btn btn-success accepted-btn no-display ">
                              <i class="fa fa-check-circle"></i> Amigos
                          </button>          

                      <?php else: ?>  

                        <!-- Agregar a amigos -->
                        <button class="btn btn-success <?php if(!empty($foundRequestByMe)){ echo "no-display"; } ?> friend-request" data-user-id="<?php echo $User['User']['id']; ?>">
                          <i class="fa fa-user-plus fa-fw"></i> Agregar a mis amigos
                        </button>

                        <!-- Cancelar Solicitud -->
                        <button class="btn btn-warning <?php if(empty($foundRequestByMe)){ echo "no-display"; } ?> <?php if(!empty($foundFriend)){ echo "no-display"; } ?> cancel-friend-request" data-user-id="<?php echo $User['User']['id']; ?>">
                          <i class="fa fa-remove fa-fw"></i> Cancelar Solicitud
                        </button>
                      
                    <?php endif; ?>  



                    <?php endif; ?>

  

 

                    <?php endif; ?>  
                    
                    </div>

                  <div class="profile-icons margin-none">
                    <span><i class="fa fa-users"></i> <span class="total-friends"> <?php echo $totalFriends; ?></span></span>
                    <!-- <span><i class="fa fa-photo"></i> 43</span> -->
                  <!--   <span><i class="fa fa-video-camera"></i> 3</span> -->
                  </div>
         
                  <br />
                
                    <div class="col-md-6">
                    <div class="panel panel-default text-left ">
                      <div class="panel-heading panel-heading-gray">

                        <div class="pull-right">

                          <!-- Si el usuario que el usuario ve es diferente de el mismo -->
                          <?php if($loggedUser['id'] == $User['User']['id']):?>

                              <a href="<?php echo Router::url('/', true).'Users/edit'; ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>


                          <?php endif; ?>

                        
                        </div>
                        <i class="fa fa-fw fa-info-circle"></i> Información


                      </div>
                      <div class="panel-body">
                        <ul class="list-unstyled profile-about margin-none">
                          <li class="padding-v-5">
                            <div class="row">
                              <div class="col-sm-4"><span class="text-muted">Nombre de Usuario:</span></div>
                              <div class="col-sm-8"><?php echo $User['User']['username']; ?></div>
                            </div>
                          </li>
                          <li class="padding-v-5">
                            <div class="row">
                              <div class="col-sm-4"><span class="text-muted">Nombre</span></div>
                              <div class="col-sm-8"><?php echo $User['User']['name'].' '.$User['User']['surname']; ?></div>
                            </div>
                          </li>
                          <li class="padding-v-5">
                            <div class="row">
                              <div class="col-sm-4"><span class="text-muted">Email</span></div>
                              <div class="col-sm-8"><?php echo $User['User']['email']; ?></div>
                            </div>
                          </li>


                       

                          <li class="padding-v-5">
                            <div class="row">
                              <div class="col-sm-4"><span class="text-muted">Ubicaci&oacute;n 
                                </span></div>
                              <div class="col-sm-8">

                                 <?php if(isset($User['Municipality']['id'])): ?>

                                     <?php echo $User['Municipality']['municipality'].', '.$User['Municipality']['Department']['name']; ?>

                                 <?php else: ?>    
                                    
                                  El usuario no ha especificado una ubicaci&oacute;n

                                 <?php endif; ?>    

                              </div>
                            </div>
                          </li>


                        </ul>
                      </div>
                    </div>
                  </div>


              <!-- Sección de solicitudes de amistad -->
                <?php if(isset($friendRequests) && $friendRequests != null ): ?>

                    <div class="col-md-6">
                    <div class="panel panel-default">
                      <div class="panel-heading panel-heading-gray">
                        <div class="pull-right">
                          <a href="<?php echo Router::url('/', true).'Users/friendsRequests/'.$User['User']['username']; ?>" class="btn btn-primary btn-xs">Ver Todas <i class="fa fa-users"></i></a>
                        </div>
                        <i class="fa fa-exclamation-circle" style="color: #F0AD4E;"></i> Solicitudes de Amistad
                      </div>
                      <div class="panel-body">
                        <ul class="img-grid">
                
                          <?php foreach ($friendRequests as $friendRequest) : ?>
                            
                            <li>
                              <a href="<?php echo Router::url('/', true).'Users/profile/'.$friendRequest['User']['username']; ?>">
                                <div class="squared-image-top-friend" style="background-image:url('<?php echo $friendRequest['User']['profilePic']; ?>')"></div>
                              </a>
                            </li>
                  
                          <?php endforeach; ?>
                         
                        </ul>
                      </div>
                    </div>
                  </div>
              <?php endif; ?>
              <!-- Fin sección de solicitudes de amistad -->
    


              <!-- Sección de amigos -->
                <div class="col-md-6">
                <div class="panel panel-default">
                  <div class="panel-heading panel-heading-gray">
                    <div class="pull-right">
                      <a href="<?php echo Router::url('/', true).'Users/friendsFrom/'.$User['User']['username']; ?>" class="btn btn-primary btn-xs">Ver Amigos <i class="fa fa-users"></i></a>
                    </div>
                    <i class="icon-user-1"></i> Amigos
                  </div>
                  <div class="panel-body">
                    <ul class="img-grid">
                      

                    <?php foreach ($topFriends as $topFriend) : ?>
                      
                      <li>
                        <a href="<?php echo Router::url('/', true).'Users/profile/'.$topFriend['User']['username']; ?>">
                          <div class="squared-image-top-friend" style="background-image:url('<?php echo $topFriend['User']['profilePic']; ?>')"></div>
                        </a>
                      </li>
            
                    <?php endforeach; ?>
          

                   
                    </ul>
                  </div>
                </div>
              </div>
              <!-- Fin sección de amigos -->



             </div>
      
            </div>
        </div>
    </div>



</div>
                    
    
</div>

<?php 


    /**
     * Arreglo con los scripts necesarios
     * @var Array
     */
    $scripts = Array(

        /**
         * Jquery ui
         */
        'users/requestFriends'

    );
    
    /**
     * Imprimimos los scripts
     */
    echo $this->Html->script($scripts, array('block' => 'scriptBottom')); 

?>






