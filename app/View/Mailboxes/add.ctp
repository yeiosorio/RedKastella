<div class="st-content">
    <div class="st-content-inner">    
        <div class="container">
<!-- formulario pruebas ingreso nuevo email -->
<div class="row">
    <div class="col-md-12 col-lg-12 col-md-offset-1 col-lg-offset-0">
       <div class="panel panel-default" style="border-radius:6px">
            <div class="panel-body">
                <div class="row">
                    <ol class="breadcrumb" style="margin-bottom: -14px;">
                        <li><a href="#" onclick="history.back()">Volver</a></li>
                            <li class="active">Mensajería | Enviar correo</li>
                    </ol>
                    <br /><br />
			<?php echo $this->Form->create('Mailbox', array('role' => 'form')); ?>
				
				<?php
					echo $this->Form->input('sender', array('class' => 'form-control', 'label' => '', 'value' => $username, 'readonly' => 'readonly'));
					echo $this->Form->input('username', array('class' => 'form-control', 'label' => '', 'placeholder' => 'Ingrese el nombre de usuario del destinatario'));
					echo $this->Form->input('state', array('class' => 'form-control', 'type' => 'hidden', 'value' => 'unread'));
					echo $this->Form->input('description', array('class' => 'form-control', 'label' => '', 'placeholder' => 'Ingrese aquí una descripción breve', 'maxlength' => '100'));
					echo $this->Form->input('category_mail_id', array('class' => 'form-control', 'label' => '',
                                                                      'options' => $subject, 'selected' => '11'));
					echo "<br />";
                    echo $this->Form->textarea('message', array('placeholder'=>'Ingrese su mensaje...', 'label'=>'', 'rows' => '5','class'=>'form-control', 'maxlength' => '2000'));
				?>
                    <div class="panel-body buttons-spacing-vertical">
                        <p style="padding-left: 26%">
                        
                    <?php echo $this->Form->end(array('label' => 'Enviar Mail', 'class' =>'btn btn-success')); ?>
                             
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- fin formulario de ingreso -->    
        </div>
    </div>
</div>