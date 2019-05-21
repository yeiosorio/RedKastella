

<div class="st-content">
    <div class="st-content-inner scrollable_content">    
        <div class="container-fluid "> 

        	

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
        

 	
 	);

    /**
     * Imprimimos los scripts
     */
    echo $this->Html->script($scripts, array('block' => 'scriptBottom')); 
 


?>