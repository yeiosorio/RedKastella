
<div class="st-content-inner">    
    <div class="container-fluid"> 

     <div class="col-md-12 ">           

            <!-- Panel de miembros de la entidad -->
            
                <div class=" panel panel-default widget-user-1 text-center">
                <div class="panel-body ">
      

                <div class="avatar ">
                    
                    <div style="background-image:url('<?php echo $organization['User']['profilePic']; ?>')"  class="profile-circled-img" ></div>
                    
                    <h3><?php echo $organization['Organization']['name']; ?></h3>
                    <!-- <a href="#" class="btn btn-success"><i class="fa fa-user-plus fa-fw"></i> Pertenecer</a> -->
                  </div>
                  <div class="profile-icons margin-none">
                    <span><i class="fa fa-users"></i> <?php  echo $numberOfMembers; ?></span>
                    <!-- <span><i class="fa fa-photo"></i> 43</span> -->
                  <!--   <span><i class="fa fa-video-camera"></i> 3</span> -->
                  </div>
         
                  <br />
                
                    <div class="col-md-6">
                    <div class="panel panel-default text-left ">
                      <div class="panel-heading panel-heading-gray">
                        <!-- <a href="#" class="btn btn-white btn-xs pull-right"><i class="fa fa-pencil"></i></a> -->
                        <i class="fa fa-fw fa-info-circle"></i> Información
                      </div>
                      <div class="panel-body">
                        <ul class="list-unstyled profile-about margin-none">
                          <li class="padding-v-5">
                            <div class="row">
                              <div class="col-sm-4"><span class="text-muted">Nit:</span></div>
                              <div class="col-sm-8"><?php echo $organization['Organization']['nit']; ?></div>
                            </div>
                          </li>
                          <li class="padding-v-5">
                            <div class="row">
                              <div class="col-sm-4"><span class="text-muted">Nombre</span></div>
                              <div class="col-sm-8"><?php echo $organization['Organization']['name']; ?></div>
                            </div>
                          </li>
                          <li class="padding-v-5">
                            <div class="row">
                              <div class="col-sm-4"><span class="text-muted">Slogan</span></div>
                              <div class="col-sm-8"><?php echo $organization['Organization']['slogan']; ?></div>
                            </div>
                          </li>
                          <li class="padding-v-5">
                            <div class="row">
                              <div class="col-sm-4"><span class="text-muted">Desde</span></div>
                              <div class="col-sm-8"><?php echo $organization['Organization']['created']; ?></div>
                            </div>
                          </li>

                          <li class="padding-v-5">
                            <div class="row">
                              <div class="col-sm-4"><span class="text-muted">Ubicacion 
                                </span></div>
                              <div class="col-sm-8"><?php echo $organization['Municipality']['municipality'].', '.$organization['Municipality']['Department']['name']; ?></div>
                            </div>
                          </li>
              
                        </ul>
                      </div>
                    </div>
                  </div>


                  <div class="col-md-6">
                  
                      <div class="panel panel-default">
                        <div class="panel-heading panel-heading-gray">
                          <div class="pull-right">
                            <a href="<?php echo Router::url('/', true).'Groups/groupMembers/'.$organization['Organization']['nit']; ?>" class="btn btn-primary btn-xs">Ver Miembros <i class="fa fa-users"></i></a>
                          </div>
                          <i class="icon-user-1"></i> Miembros
                        </div>
                      <div class="panel-body">
                        <ul class="img-grid">
                          

                        <?php foreach ($topMembers as $topMember) : ?>
                          
                          <li>
                            <a href="<?php echo Router::url('/', true).'Users/profile/'.$topMember['User']['username']; ?>">
                              <div class="squared-image-top-friend" style="background-image:url('<?php echo $topMember['User']['profilePic']; ?>')"></div>
                            </a>
                          </li>
                
                        <?php endforeach; ?>
                       
                        </ul>
                      </div>
                    </div>
                  </div>


                </div>
           </div>
      </div>
    </div>
  </div>









