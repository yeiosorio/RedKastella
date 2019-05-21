<?php 
    $loggedUser = AuthComponent::user();
?>   

<div class="st-content-inner">    
    <div class="container-fluid">
        <div class="news" stlyle="width:60%;float:left;">
            <ol class="breadcrumb" style="margin-bottom: -14px;">
                <li>
                    <a href="#" onclick="history.back()">Volver</a>
                </li>
                <li class="active">Miembros</li>
            </ol> 
        </div>
                    
        <br />

        <div class="col-md-12 ">

    <?php 
    
  

         // <!-- role filter -->
       if ($loggedUser['role_id'] == 3 ) {

        /**
         * Si el usuario no ha creado una entidad
         */ 
        if (empty($userOrganization)) {
                
            /**
             * Elemento que tiene el formulario de creación de entidad
             */
            echo $this->element('organization/new');
        
        }else{ ?>




                    <div class="tabbable">


        



                    <!-- Tabs -->
                    <ul class="nav nav-tabs" tabindex="1" style="overflow: hidden; outline: none;">
                      <li class="active"><a href="#home" data-toggle="tab" aria-expanded="false"><i class="fa fa-fw fa-users"></i> Miembros</a></li>
                      <li class=""><a href="#profile" data-toggle="tab" aria-expanded="true"><i class="fa fa-fw fa fa-user-plus"></i> Solicitudes</a></li>
        
                    </ul>
                    <!-- // END Tabs -->

                    <!-- Panes -->

                    <div class="tab-content">
                    

                    <!-- Panel de miembros de la entidad -->
                <div id="home" class="tab-pane active">
                <div class=" panel panel-default widget-user-1 text-center">
                <div class="panel-body ">
     	

                <div class="avatar ">
                    
                    <div style="background-image:url('<?php echo $userOrganization['User']['profilePic']; ?>')"  class="profile-circled-img" ></div>
                    
                    <h3><?php echo $userOrganization['Organization']['name']; ?></h3>
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
                              <div class="col-sm-8"><?php echo $userOrganization['Organization']['nit']; ?></div>
                            </div>
                          </li>
                          <li class="padding-v-5">
                            <div class="row">
                              <div class="col-sm-4"><span class="text-muted">Nombre</span></div>
                              <div class="col-sm-8"><?php echo $userOrganization['Organization']['name']; ?></div>
                            </div>
                          </li>
                          <li class="padding-v-5">
                            <div class="row">
                              <div class="col-sm-4"><span class="text-muted">Slogan</span></div>
                              <div class="col-sm-8"><?php echo $userOrganization['Organization']['slogan']; ?></div>
                            </div>
                          </li>
                          <li class="padding-v-5">
                            <div class="row">
                              <div class="col-sm-4"><span class="text-muted">Desde</span></div>
                              <div class="col-sm-8"><?php echo $userOrganization['Organization']['created']; ?></div>
                            </div>
                          </li>

                          <li class="padding-v-5">
                            <div class="row">
                              <div class="col-sm-4"><span class="text-muted">Ubicacion 
                                </span></div>
                              <div class="col-sm-8"><?php echo $userOrganization['Municipality']['municipality'].', '.$userOrganization['Municipality']['Department']['name']; ?></div>
                            </div>
                          </li>
              
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>

            </div>







			        	<?php 
				            /**
				             * Elemento que administra los miembros de una entidad 
				             */
				            echo $this->element('organization/members');

			            ?>


                      </div>

                      <!-- Panel de solicitudes -->
                      <div id="profile" class="tab-pane ">



                      <div class="table-responsive">
                                        <table class="table v-middle">
                                            <thead>
                                                <tr>
                                                 	
                                                 	<!-- Nombre de usuario -->
                                                    <th>Nombre de Usuario</th>
 
                                                    <!-- Nombre  -->
                                                    <th>Nombre</th>	

                                                    <!-- Ubicación -->
                                                    <th>Ubicaci&oacute;n</th>
   														
   													          <th>Fecha</th>
                                                  
                                                    <!-- Encabezado Vacío -->
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                              
                                                <!-- Recorrido por los resultados -->
                                                <?php foreach ($groupRequests as $groupRequest) : ?>
                                                <tr>
                                                   

                                                	<!-- Nombre de usuario -->
													                         <td><?php echo $groupRequest['User']['username']; ?></td>

                                                    <!-- Nombre  -->
                                                    <td><?php echo $groupRequest['User']['name'].' '.$groupRequest['User']['surname']; ?></td>
                                                    

                                                    <!-- Ubicación -->
                          													<td>
                          														<?php echo $groupRequest['User']['Municipality']['municipality'].', '.$groupRequest['User']['Municipality']['Department']['name']; ?>
                          													</td>

                          													<!-- Fecha -->
                          													<td>
                                                    	<?php echo $this->Time->nice($groupRequest['OrganizationRequest']['created']); ?>
                                                    </td>
										

                                                   <td>

                                                   		<button type="button" class="btn btn-success accept-user" data-user-id="<?php echo $groupRequest['User']['id']; ?>" data-request-id="<?php echo $groupRequest['OrganizationRequest']['id']; ?>">
                                                   			<i class="fa fa-user-plus"></i> Aceptar
                                                   		</button>	

                                                   		<button type="button" class="btn btn-danger cancel-user-request" data-request-id="<?php echo $groupRequest['OrganizationRequest']['id']; ?>" >
                                                   			<i class="fa fa-remove"></i> Eliminar
                                                   		</button>	

                                                   </td>
                                                </tr>
                                      
                                                <?php endforeach; ?>
                                                <!-- Fin Recorrido de los resultados -->
                                            </tbody>
                                        </table>
                                    </div>

                      	</div>
                     
                    </div>
                    <!-- // END Panes -->

                  </div>




       <?php }

    }   
             
    ?> 

    </div>
           
</div>


<?php 


  /**
     * Arreglo con los estilos necesarios
     * @var Array
     */
    $styles = Array(
        
        /**
         * Estilos de autocompletado
         */
        'magicsuggest-min',   

    );


    /**
     * Arreglo con los scripts necesarios
     * @var Array
     */
    $scripts = Array(

        /**
         * Scripts necesarios para las funcionalidades de autocompletar
         */
        'magicsuggest-min', 

        /**
         * Animación de botones
         */
        'buttonAnimations/buttonAnimations',

        /**
         * Scripts de Organizaciones
         */
        'users/myOrganization',

        /**
         * scripts de peticiones a grupos
         */
        'groups/usersRequests'

    );

    /**
     * imprimimos los estilos
     */
    echo $this->Html->css($styles, null, array('block' => 'css'));   
    
    /**
     * Imprimimos los scripts
     */
    echo $this->Html->script($scripts, array('block' => 'scriptBottom')); 
 
?>