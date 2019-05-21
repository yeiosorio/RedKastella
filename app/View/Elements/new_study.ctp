<?php // Controller MarketResearch ?>
<?php
    // Elemento que corresponde al formulario de nuevo estudio de mercado (único)
?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-md-offset-1 col-lg-offset-0">
        <div class="panel panel-default" style="border-radius:6px">
            <div class="panel-body">
                <div class="row">
                   <ol class="breadcrumb" style="margin-bottom: -14px;">
                        <li><a href="#" onclick="history.back()">Volver</a></li>
                            <li class="active">Estudios de Mercado</li>
                    </ol> 
<?php
echo $this->Form->create('MarketResearch', array('default' => false, 'enctype'=>'multipart/form-data')); //Con la opción “default=>false” indicamos que el botón del formulario no haga el submit, lo haremos nosotros vía ajax.
echo $this->Form->input('user_id', array('value'=>$user_id,'type' => 'hidden'));
echo $this->Form->input('content_research', array('placeholder'=>'Ingrese su texto...', 'label'=>'', 'maxlength' => '1000', 'rows' => '3', 'class'=>'form-control'));
?>
<div class="panel-body buttons-spacing-vertical">
    <p style="padding-left: 26%">
    <?php
        //echo $this->Form->input('documents_research',array('type' => 'file', 'multiple' => 'multiple', 'label'=>''));
        echo $this->Form->input('',array('type' => 'file', 'id' => 'MarketResearchDocumentsResearch', 'div' => false, 'multiple' => 'multiple', 'style'=>array('display: none;'), 'required' => 'false'));
    echo $this->Form->button('Adjuntar Documentos',array('type' => 'button', 'label' => false, 'div' => false, 'onclick'=>"document.getElementById('MarketResearchDocumentsResearch').click();",'style'=>array('height:33px')));
    /*echo $this->Form->input('',array('type' => 'file', 'id' => 'PublicationImages', 'multiple' => 'multiple','div' => false, 'style'=>array('display: none; '), 'required' => 'false'));
    echo $this->Form->button('Adjuntar Imágenes',array('type' => 'button', 'label' => false, 'div' => false, 'onclick'=>"document.getElementById('PublicationImages').click();",'style'=>array('height:33px')));*/

        echo $this->Form->input('privacy_type', //id
                                            array(
                                                'options' => $privacy_options,         
                                                'label' => '',
                                                'class'=>'btn btn-primary dropdown-toggle',
                                                'div' => false,
                                                'data-toggle'=>'dropdown'
                                                )
                                        );
    ?>

<?php
        echo $this->Form->submit(__('Publicar',true), array('type' => 'submit', 'div' => false, 'class'=>'btn btn-primary', 'id'=>'b_new_study')); 
        echo $this->Form->end();
?>
    </p>
        <div id="message_doc"> </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>