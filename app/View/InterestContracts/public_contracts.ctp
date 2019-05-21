


   <?php 

         $adminUser = AuthComponent::user();  


         $adminUser = $this->requestAction(array('controller'=>'Users','action'=>'getUserById', 421));

         $adminUser = $adminUser['User'];


         $fullBaseUrl = Router::url('/', true);

    ?>

    <!--barra negra friends-->

      <?php //echo $this->element('barra_friends') ?>

    <!--fin barra friends-->
 

<?php if(isset($numConstancia)): ?> 

  
  <input type="hidden" value="<?php echo $numConstancia; ?>" id="numConstancia"></input>


<?php endif; ?>


<div class="st-content">
    <div class="st-content-inner scrollable_content">    
        <div class="container-fluid "> <!--container-fluid-->

          </div> 

            <div class="col-md-6 no-display heading-main-pub">
            <h4>
              Estas aqu&iacute; porque te enteraste de la siguiente novedad:
            </h4>
            </div>
          

          <!-- Publicacion principal -->
          <div id="mainPublication"></div>
        </div>
    </div>


<div class="st-content">
    <div class="st-content-inner scrollable_content">    



<!--         <div class="container-fluid "> 

        </div> -->


                 <div >
                    
                    <h4>&nbsp;&nbsp;&nbsp;&nbsp;En esta sección encontrarás los las oportunidades que te interesaron de la sección Buscador de negocios</h4>

                </div> 
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



<!-- Modal compartir -->

<div class="modal fade register-new-modal " tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-margin-top">

      <div class="modal-header modal-pub-header">
          
        <!-- User profile picture -->
        <a href="">
          <div class="post-user-picture modal-profile-img" style="background-image:url(<?php echo $adminUser['profilePic']; ?>)">
          </div>
        </a>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Registrate</h4>
      
      </div>
    
      <div class="modal-body edit-form-container">
        
        <div class="row">
  
          <div class="col-md-12">
                      
              <h4 class="text-center">
                ¡Regístrate o inicia sesión en RedKastella para acceder a todas las funcionalidades!
              </h4>

              <br />

              <div class="text-center">
                <a href="<?php echo $fullBaseUrl; ?>" class="btn btn-primary ">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                    Registrarme
                </a>
                <a href="<?php echo $fullBaseUrl; ?>"  class="btn btn-primary ">
                    <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                    Iniciar Sesión
                </a>
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
     * Arreglo con los scripts necesarios
     * @var Array
     */
    $scripts = Array(

        /**
         * Clipboard Plugin
         */
        'clipboard.min',


        /**
         * Script de animación de botones
         */
        'moment/moment-with-locales',

        /**
         * Accounting
         */
        'accounting.min',

        /**
         * Script de animación de botones
         */
        'buttonAnimations/buttonAnimations',

        /**
         * Scripts slider de selección
         */
        'masonry/masonry',
        
        /**
         * Scripts de contratos
         */
        'interestContracts/public_interest_contracts',
        
    
    
    );
 
   
    /**
     * Imprimimos los scripts
     */
    echo $this->Html->script($scripts, array('block' => 'scriptBottom')); 
?>
