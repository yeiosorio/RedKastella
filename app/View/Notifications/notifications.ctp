<div class="st-content">
    <div class="st-content-inner ">    
        <div class="container-fluid "> 
      
        		<h4>Notificaciones de los contratos que te interesan:</h4>	

                <!-- Inicio de los post de publicaciones -->
                <div id="publications" class="scrollable_content"></div>
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


<?php 

    /**
     * Arreglo con los scripts necesarios
     * @var Array
     */
    $scripts = Array(

    	/**
         * Script de animación de botones
         */
        'buttonAnimations/buttonAnimations',
        

    	/**
         * Script de animación de botones
         */
        'contractNotifications/contractNotifications',
               

 	);

    /**
     * Imprimimos los scripts
     */
    echo $this->Html->script($scripts, array('block' => 'scriptBottom')); 
 
?>




