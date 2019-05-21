
   
<!--barra negra friends-->
<?php //echo $this->element('barra_friends') ?>
<!--fin barra friends-->

<div class="st-content">
    <div class="st-content-inner scrollable_content">    
        <div class="container-fluid "> <!--container-fluid-->
                <?php
                    /**
                     * Llamamos el elementos de agregar nuevas Publicaciones
                     */
                    echo $this->element('contracts/addContract');
                ?>
            
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
        'contracts/contracts',
        
    
    
    );
 
   
    /**
     * Imprimimos los scripts
     */
    echo $this->Html->script($scripts, array('block' => 'scriptBottom')); 
?>
