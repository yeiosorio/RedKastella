
<div class="st-content">
    <div class="st-content-inner">    
        <div class="container">

<div class="row">
    <div class="col-md-12 col-lg-12 col-md-offset-1 col-lg-offset-0">
       <div class="panel panel-default" style="border-radius:6px">
            <div class="panel-body">
                <div class="row">
                    <ol class="breadcrumb" style="margin-bottom: -14px;">
                        <li><a href="#" onclick="history.back()">Volver</a></li>
                            <li class="active">Mensajería</li>
                    </ol>
                    <div class="panel-body buttons-spacing-vertical">
                        <p style="padding-left: 26%">
                        <nav>
                            <ul class="pagination">
                                <li><?php echo $this->Html->link(__('Correo Recibido'), array('action' => 'mailbox')); ?></li>
                                <li><?php echo $this->Html->link(__('Correo Enviado'), array('controller' => 'Mailboxes', 'action' => 'sent')); ?></li>
                                <li><?php echo $this->Html->link(__('Redactar'), array('controller' => 'Mailboxes', 'action' => 'add')); ?></li>
                            </ul>
                        </nav>
                    

                       


                    <?php 
                        
                        // echo $this->Form->create('Message'); 

                        



                        // // probar comentando el submit de abajo, y enviar por funcion onchange en los radiobutton y select
                        // echo $this->Form->submit(__('Publicar',true), array('type' => 'submit', 'class'=>'button_form', 'id'=>'b_new_document' )); 

                        // echo $this->Form->end();

                    ?>
            
                        </p>
                    </div>
    



                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>

