
    <!--barra negra friends-->

      <?php //echo $this->element('barra_friends') ?>

    <!--fin barra friends-->

   <?php 

         $loggedUser = AuthComponent::user();  

         $fullBaseUrl = Router::url('/', true);


         $adminUser = $this->requestAction(array('controller'=>'Users','action'=>'getUserById', 421));

         $adminUser = $adminUser['User'];
    ?>

<style type="text/css">
  
  .link-user-likes{

    cursor: pointer;

  }

  .post-link-user-likes{

    cursor: pointer;

  }
 
</style>




<?php if(isset($sharedPostId)): ?> 

  
  <input type="hidden" value="<?php echo $sharedPostId; ?>" id="sharedPostId"></input>


<?php endif; ?>


<div class="st-content">
    <div class="scrollable_content">    
        <div class="container-fluid "> <!--container-fluid-->

          <div class="col-md-12">
            <h2 class="text-center">
                BIENVENIDO A KASTELLA
            </h2>

              <h4 class="text-center">
                LA RED KASTELLA ES LA PRIMERA COMUNIDAD
                DE NEGOCIOS CON EL ESTADO COLOMBIANO

              </h4>

            </div>

            <div class="col-md-6 no-display heading-main-pub">
            <h4>
              Estas aqu&iacute; porque te enteraste de la siguiente novedad:
            </h4>
            </div>

          </div> 

          

          <!-- Publicacion principal -->
                <div id="mainPublication"></div>
        </div>
    </div>



<?php if(isset($sharedPostId)): ?> 

<div class="st-content">
    <div class="st-content-inner scrollable_content">    
        <div class="container-fluid "> <!--container-fluid-->

            <div class="col-md-4 ">
              <h4>Otras novedades que te pueden interesar:</h4>
            </div>

        </div>
    </div>
</div>

<?php endif; ?>


<div class="st-content" >
    <div class="">    
        <div class="container-fluid"> <!--container-fluid-->

                <!-- Inicio de los post de publicaciones -->
                <div id="publications"></div>
                <!-- Fin posts de publicaciones -->

                <!-- Botón que carga mas posts -->
                <div class="row">
                  <div class="col-md-12">
                    <div style="margin-left:15px;">
                    <!-- no-display -->
                      <button type="button" id="morePosts" class="btn btn-primary " >
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
        <h4 class="modal-title" id="myModalLabel">Nueva Publicaci&oacute;n</h4>
      
      </div>
    
      <div class="modal-body">
       

          <?php
                    /**
                     * Llamamos el elementos de agregar nuevas Publicaciones
                     */
                   echo $this->element('publications/addModalPublication');
          ?>

       </div>

    </div>
  </div>
</div>

<!-- Fin modal nueva publicacion  -->



<!-- Modal editar publicación -->

<div class="modal fade edit-publication-modal " tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
    
      <div class="modal-body edit-form-container">
       


       </div>

    </div>
  </div>
</div>

<!-- Fin modal editar publicación -->



<!-- Modal compartir -->

<div class="modal fade share-modal " tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-margin-top">

      <div class="modal-header modal-pub-header">
          
        <!-- User profile picture -->
        <a href="">
          <div class="post-user-picture modal-profile-img" style="background-image:url(<?php echo $adminUser['profilePic']; ?>)"></div>
        </a>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Compartir Novedad</h4>
      
      </div>
    
      <div class="modal-body edit-form-container">
        
        <div class="row">
  
          <div class="col-md-12">
            
        
              <h4 class="text-center">¡Comparte esta novedad en cualquiera de tus redes sociales!</h4>

            </div>   

          <div class="col-md-12 text-center">



              <a href="" target="_blank" class="share-on-fb-link">
                <i class="fa fa-3x fa-facebook-official" aria-hidden="true"></i>
              </a>

              <a href="" target="_blank" class="share-on-tw-link">
                <i class="fa fa-3x fa-twitter-square"  aria-hidden="true"></i>
              </a>

              <a href="" class="share-on-wp-link">
                <i class="fa fa-3x fa-whatsapp" aria-hidden="true"></i>
              </a>

          
          </div>


          <div class="col-md-12 text-center" style="margin-top: 5px;">

          <button data-id='' class="btn btn-primary justCopyLink">
              <i class="fa fa-globe" aria-hidden="true"></i>
              Copiar Link
          </button>


          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Fin modal compartir -->


<!-- Modal compartir -->

<div class="modal fade just-link " tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-margin-top">

      <div class="modal-header modal-pub-header">
          
        <!-- User profile picture -->
        <a href="">
          <div class="post-user-picture modal-profile-img" style="background-image:url(<?php echo $adminUser['profilePic']; ?>)"></div>
        </a>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Compartir link</h4>
      
      </div>
    
      <div class="modal-body edit-form-container">
        
        <div class="row">
  
          <div class="col-md-12">
            
        
              <h4 class="text-center">
                ¡Ya tienes el link de es esta novedad, ahora puedes compartirla en cualquier lugar en la internet!
              </h4>

            </div>   


          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Fin modal compartir -->

<?php 


    /**
     * Arreglo con los estilos necesarios
     * @var Array
     */
    $styles = Array(
        

      'tooltipster',

    );


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
         * tooltipster
         */
        'jquery.tooltipster',

        /**
         * Clipboard Plugin
         */
        'clipboard.min',

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
        'publications/publications',
        
        
    
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

