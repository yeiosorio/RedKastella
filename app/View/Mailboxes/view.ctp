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
                            <li class="active">Mensajería | Leer correo</li>
                    </ol>
                    <br /><br />
			
				 
				<?php
					echo "<b>".$this->Form->input('sender', array('class' => 'form-control', 'label' => 'Remitente: ', 'value' => $email['Mailbox']['sender'], 'readonly' => 'readonly'))."</b>";
                    echo "<br />";
					echo $this->Form->input('username', array('class' => 'form-control', 'label' => 'Destinatario: ', 'value' => $email['Mailbox']['username'], 'readonly' => 'readonly'));
                    echo "<br />";
					//echo $this->Form->input('state', array('class' => 'form-control', 'type' => 'hidden', 'value' => 'unread'));
					echo $this->Form->input('description', array('class' => 'form-control', 'label' => 'Descripción: ', 'value' => $email['Mailbox']['description'], 'readonly' => 'readonly'));
                    echo "<br />";
					echo $this->Form->input('', array('class' => 'form-control', 'label' => 'Categoría: ', 'value' => $email['CategoryMail']['title'], 'readonly' => 'readonly'));
                    echo "<br />";
                    echo "<b>Mensaje: </b>";
                    echo $this->Form->textarea('message', array('value' => ereg_replace("<br />","\r\n",$email['Mailbox']['message']), 'rows' => '5','class'=>'form-control', 'readonly' => 'readonly'));
				?>
                    <div class="panel-body buttons-spacing-vertical">
                        <p style="padding-left: 26%">
                        <?php /*
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                  <?php echo __('Actions'); ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                  <li><?php echo $this->Html->link(__('List Mailbox'), array('action' => 'index')); ?></li>
                                  <li class="divider"></li>
                                  <li><?php echo $this->Html->link(__('List CategoryMails'), array('controller' => 'CategoryMails', 'action' => 'index')); ?></li>
                                  <li><?php echo $this->Html->link(__('New Mesero'), array('controller' => 'CategoryMails', 'action' => 'add')); ?></li>
                                </ul>
                            </div>*/
                        ?>
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