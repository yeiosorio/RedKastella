   
    <!--barra negra friends-->
    <?php //echo $this->element('barra_friends') ?>
    <!--fin barra friends-->


   <?php 

         $loggedUser = AuthComponent::user();  

         $fullBaseUrl = Router::url('/', true);

    ?>

<div class="st-content">
    <div class="st-content-inner scrollable_content">    
        <div class="container-fluid "> <!--container-fluid-->

    
          
             <div >
                    
                    <h4>&nbsp;&nbsp;&nbsp;&nbsp;Comparte y descarga estudios de mercado con toda la comunidad o con las personas que tu quieras</h4>

                </div> 

     


                <!-- Búsqueda estudios de mercado -->
<!--                 <div class="row">
                  <div class="col-md-4 col-lg-4 col-md-offset-1 col-lg-offset-0">
                    <div class="panel panel-default" style="border-radius:6px; margin-left:15px;">
                      <div class="panel-body">
                        <div class="row">
                            <div class="form-group">
                                <label for="touch-spin-1">Búsqueda:</label>
                                  <div class="input-group bootstrap-touchspin">
                         
                                      <input type="text" class="form-control smrTerm" >
                                      <span class="input-group-btn">

                                        <button class="btn btn-primary searchMarketResearch" type="button" >
                                            <i class="fa fa-search"></i> Buscar
                                        </button>
                                      </span>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- Fin Búsqueda estudios de mercado -->

                <!-- Inicio de los post de publicaciones -->
                <div id="publications"></div>
                <!-- Fin posts de publicaciones -->

                <!-- Botón que carga mas posts -->
                <div class="row">
                  <div class="col-md-12">
                    <div style="margin-left:15px;">
                      <button type="button" id="morePosts" class="btn btn-primary no-display" >
                          <i class='gsl-load-more-posts fa fa-cloud-download'></i> Cargar m&aacute;s
                      </button>
                    </div>
                  </div>
                </div>
                <!-- find cards -->

        </div>
    </div>
</div>


<!-- Modal confirmación eliminar post -->

<div class="modal fade" id="confrimDropPostModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirmar Eliminaci&oacute;n</h4>
      </div>
      <div class="modal-body">
        <h5>¿Esta seguro que desea eliminar la publicación seleccionada?</h5>
       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary" id="confirmDelete">Sí</button>
      </div>
    </div>
  </div>
</div>

<!-- Fin modal de confirmación  -->



<!-- Modal confirmación eliminar post -->

<div class="modal fade" id="confirmDropCommentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirmar Eliminaci&oacute;n</h4>
      </div>
      <div class="modal-body">
        <h5>¿Esta seguro que desea eliminar el comentario seleccionada?</h5>
       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary" id="confirmDeleteComment">Sí</button>
      </div>
    </div>
  </div>
</div>

<!-- Fin modal de confirmación  -->




<!-- Modal nueva publicacion -->

<div class="modal fade new-publication-modal " tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-margin-top">

      <div class="modal-header modal-pub-header">
          
        <!-- User profile picture -->
        <a href="<?php echo $fullBaseUrl.'users/profile/'.$loggedUser['username']; ?>">
          <div class="post-user-picture modal-profile-img" style="background-image:url(<?php echo $loggedUser['profilePic']; ?>)"></div>
        </a>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Nuevo estudio de mercado</h4>
      
      </div>
    
      <div class="modal-body">
       

          <?php

                    /**
                     * Llamamos el elementos de agregar nuevas Publicaciones
                     */
                    echo $this->element('marketResearches/modalAddMarketResearch');
          ?>

       </div>

    </div>
  </div>
</div>

<!-- Fin modal nueva publicacion  -->


<!-- Modal editar estudio de mercado -->

<div class="modal fade edit-market-research-modal " tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-margin-top">

      <div class="modal-header modal-pub-header">
          
        <!-- User profile picture -->
        <a href="<?php echo $fullBaseUrl.'users/profile/'.$loggedUser['username']; ?>">
          <div class="post-user-picture modal-profile-img" style="background-image:url(<?php echo $loggedUser['profilePic']; ?>)"></div>
        </a>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Editar Publicaci&oacute;n</h4>
      
      </div>
    
      <div class="modal-body edit-form-mr-container">
       


       </div>
    </div>
  </div>
</div>

<!-- Fin Modal editar estudio de mercado -->

<!-- edit-form-mr-container -->


<?php 

    /**
     * Arreglo con los scripts necesarios
     * @var Array
     */
    $scripts = Array(

        /**
         * Script de animación de botones
         */
        'moment/moment-with-locales',


        /**
         * Script de animación de botones
         */
        'buttonAnimations/buttonAnimations',

        /**
         * Scripts slider de selección
         */
        'masonry/masonry',
        
        /**
         * Scripts para el formateo de los valores de tipo dinero
         */
        'marketResearches/marketResearches',
        
    );
 
   
    /**
     * Imprimimos los scripts
     */
    echo $this->Html->script($scripts, array('block' => 'scriptBottom')); 
?>
































