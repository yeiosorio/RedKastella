<?php // Controller Documents ?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-md-offset-1 col-lg-offset-0">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <ol class="breadcrumb" style="margin-bottom: -14px;">
                        <li><a href="#" onclick="history.back()">Volver</a></li>
                            <li class="active">Contratos</li>
                    </ol>
<?php
echo $this->Form->create('Document', array('default' => false, 'enctype'=>'multipart/form-data')); //Con la opción “default=>false” indicamos que el botón del formulario no haga el submit, lo haremos nosotros vía ajax.
echo $this->Form->input('user_id', array('value'=>$user_id,'type' => 'hidden'));
echo $this->Form->input('title_document', array('placeholder'=>'Ingrese un título para su publicación', 'label'=>'', 'maxlength' => '100', 'class'=>'form-control','type' => 'text', 'style'=>array('background-color:white')));
echo $this->Form->textarea('content_document', array('placeholder'=>'Ingrese su texto...', 'label'=>'', 'rows' => '4', 'maxlength' => '200','class'=>'form-control', 'style'=>array('background-color:white')));
echo $this->Form->input('link_secop', array('placeholder'=>'Ingrese el link que corresponde al contrato en SECOP', 'label'=>'', 'maxlength' => '200', 'class'=>'form-control','type' => 'text', 'style'=>array('background-color:white')));
?>
<div class="panel-body buttons-spacing-vertical">
    <p style="padding-left: 26%">
<?php
    //echo $this->Form->input('documents',array('type' => 'file', 'multiple' => 'multiple', 'required' => 'required'));
    echo $this->Form->input('',array('type' => 'file', 'id' => 'DocumentDocuments', 'div' => false, 'multiple' => 'multiple', 'style'=>array('display: none;'), 'required' => 'false'));
    echo $this->Form->button('Adjuntar Documentos',array('type' => 'button', 'label' => false, 'div' => false, 'onclick'=>"document.getElementById('DocumentDocuments').click();",'style'=>array('height:33px')));
?>
<?php
        
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
    echo $this->Form->submit(__('Publicar',true), array('type' => 'submit', 'div' => false, 'class'=>'btn btn-primary', 'id'=>'b_new_document')); 
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