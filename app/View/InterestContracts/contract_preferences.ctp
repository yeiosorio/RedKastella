
<style type="text/css">
    
    .heading-h3{

        border: 1px solid #A8A8A8; 
        background: #F8F8F8; 
        font-weight: bold; 
        color: #484848;

    }

</style>

<div class="st-content" style="overflow:scroll">
<div class="page-section" style="overflow:scroll">



<div class="col-md-12  ">

        <div id="acc">

            <!-- Sección de contratos de interes -->
            <!-- Encabezado -->
            <h3 class="heading-h3">
                Configura las oportunidades de negocio que quieres recibir        
            </h3>

            <div>
                <p>
                <!-- traemos el elemento de contratos de interes con sus funcionalidades -->
                    <?php
                        echo $this->element('contractsPreferences/contractsPreferences');
                    ?>
                </p>
            </div>
            <!-- Fin sección de contratos de interes  -->

            <!-- Sección de contratos de interes -->
            <!-- Encabezado -->
            <h3 class="heading-h3">
                Busqueda directa de contratos
            </h3>
            <div>
                <p>
                <!-- traemos el elemento de contratos de interes con sus funcionalidades -->
                    <?php
                        echo $this->element('interestContracts/interestContracts');
                    ?>
                </p>
            </div>
            <!-- Fin sección de contratos de interes  -->
            
          
            </div>

        </div>

</div>
</div>


<!-- Modal -->
<div class="modal fade" id="modal-confirm-saved-pref" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">&Eacute;xito</h4>
      </div>
      <div class="modal-body">
                
            ¡Tus preferecias se han guardado con éxito!

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>


<?php 

    /**
     * Arreglo con los estilos necesarios
     * @var Array
     */
    $styles = Array(
        
        /**
         * Jquery iu styles
         */
        'jquery-ui/jquery-ui.min',
        
        /**
         * Jquery ui tema por defecto
         */
        'jquery-ui/jquery-ui.theme.min',

        /**
         * Estilos del slider de rango de valores
         */
        'slider/css/slider'
    );


    /**
     * Arreglo con los scripts necesarios
     * @var Array
     */
    $scripts = Array(

        /**
         * Jquery ui
         */
        'jquery-ui/jquery-ui',

        /**
         * Scripts slider de selección
         */
        'slider/js/bootstrap-slider',
        
        /**
         * Scripts para el formateo de los valores de tipo dinero
         */
        'jquery.formatCurrency-1.4.0',

        /**
         * Accounting
         */
        'accounting.min',
        
        /**
         * Scripts de departamentos
         */
        'localization/departments',
        
        /**
         * Scripts de ciudades
         */
        'localization/municipalities',

    
        /**
         * Scripts de contractos de interes
         */
        'interestContracts/interestContracts',
       
        /**
         * Scripts de preferencias de busqueda de contratos
         */
        'contractsPreferences/contractsPreferences',
        


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