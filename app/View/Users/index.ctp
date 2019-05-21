<div class="st-content-inner">
    <!--barra negra friends-->
    <?php echo $this->element('barra_friends') ?>
    <!--fin barra friends-->
            
    <div class="cover overlay cover-image-full height-300-lg">
        <?php echo $this->html->image("images/profile-cover.jpg", array("alt" => "img user")); ?>
              
        <!--<div class="overlay overlay-full">
            <div class="v-top">
                <a href="#" class="btn btn-cover"><i class="fa fa-pencil"></i></a>
            </div>
        </div>-->
    </div>

    <div class="container">

         <div class="row">
            
                <div class="col-md-6"style="font-family:verdana">
                    <h1 style="color:#fc6621"><?php echo $this->Session->flash(); ?></h1> 
                    <?php //echo __('Bienvenido a Kastella'); ?>
                        <h4>
<!--                            Queremos agradecerte por formar parte de la Red Kastella; </br> en este espacio encontrarás las respuestas a las preguntas más frecuentes.
                            <br> <br>
                            Anímate a revisarlas y así conocer más de esta maravillosa iniciativa.

 -->
                            Kastella es la primera Red Social de Contratación.
                            <br />
                            <br/>

                            Encuentra oportunidades de negocio y conéctate con entidades, proveedores y contratistas. Comparte tus historias y promueve el desarrollo económico.

                        </h4>
                    <p><?php echo $this->Html->link('Términos y Condiciones', array('controller' => 'users','action' => 'terminos_y_condiciones'), array('escape' => false, 'target' => '_blank', 'style' => array('color: #FC6520'))); ?></p>
                    <p><?php echo $this->Html->link('Política de Privacidad', array('controller' => 'users','action' => 'politica_de_privacidad'), array('escape' => false, 'target' => '_blank', 'style' => array('color: #FC6520'))); ?></p> 
                </div>

               <div class="col-md-6">
                   <hr style="width:100%;">
                <!--Primer pantalla de Kastella. 
                <br>
                Aquí se mostrará las últimas actualizaciones de sus contactos y entidades.
            -->
                </div>
                
    
        </div>
              
    </div>


</div>