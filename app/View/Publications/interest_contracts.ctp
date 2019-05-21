
<div class="st-content">
    <div class="st-content-inner scrollable_content">    
        <div class="container-fluid "> <!--container-fluid-->
            	

        		<div class="col-md-10 display-none" id="load_animation">

        			<i class="fa fa-spinner fa-pulse" style="font-size:30px; float:left;"></i> 
        		
	        		<h4>&nbsp;&nbsp;Kastella está encontrando oportunidades de negocio según tus preferencias...</h4>         			
	      		</div>


                <div class="col-md-10 display-none info-no-preferences">
                        
                    <h4>Cuéntanos qué tipo de negocios te interesan en la sección preferencias y luego regresa a esta sección.</h4>

                    <?php

                        echo $this->Html->link(
                            'Ir a preferencias',
                            '/InterestContracts/contractPreferences/',
                             array('class' => 'btn btn-primary ', 'escape' => false, 'target' => '_self')
                        );

                    ?>

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
         * Scripts de búsqueda global de contratos
         */
        'publications/globalContractSearch',
    );
 
   
    /**
     * Imprimimos los scripts
     */
    echo $this->Html->script($scripts, array('block' => 'scriptBottom')); 
?>

