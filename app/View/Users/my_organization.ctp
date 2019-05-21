
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
                    
    


    <?php 
    

       $loggedUser = AuthComponent::user();  

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
        
        }else{

            /**
             * Elemento que administra los miembros de una entidad 
             */
            echo $this->element('organization/members');

        }

    }   
             
    ?> 
           
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
