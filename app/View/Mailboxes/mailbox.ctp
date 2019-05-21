<?php
    //https://www.youtube.com/watch?v=xwpFkaEi-7k
    $username=$this->session->read('User.username');
?>
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
                        echo $this->Form->create('My_Mail', array('default' => false, 
                                                                  'inputDefaults' => array ('div'=>false)
                                                                 )); //Con la opción “default=>false” indicamos que el botón del formulario no haga el submit, lo haremos nosotros vía ajax.

                        /*echo $this->Form->input('MyMail', array(
                            'type' => 'radio',
                            'options' => array(1 => 'Redactar', 
                                               2 => 'Recibidos', 
                                               3 => 'Enviados'),
                            'class' => 'testClass',
                            'selected' => 1,
                            'before' => '<div class="radio_opt"><!-- botones seleccion -->',
                            'after' => '</div>',
                            'hiddenField' => false, // added for non-first elements
                            'onclick' => 'my_mail();',
                            'legend' => false,
                            'separator' => '</div><div class="radio_opt">',
                            'div' => false
                        ));*/

                        echo $this->Form->input('subject', //id
                                                array(
                                                    'options' => $subject,         
                                                    'label' => false,
                                                    'class'=>'a_der',
                                                    'style' => array('display' => 'inline-block', 'float' => 'right')
                                                    )
                                            );

                        // probar comentando el submit de abajo, y enviar por funcion onchange en los radiobutton y select
                        echo $this->Form->submit(__('Publicar',true), array('type' => 'submit', 'class'=>'button_form', 'id'=>'b_new_document', 'style' => array ('style' => 'display:none;') )); 

                        echo $this->Form->end();

                    ?>
            
                        </p>
                    </div>
            
            <hr style="width:100%;">
            <!-- *********************************** Sección Correo ******************************************* -->
            <!-- ********************************************************************************************** -->

            <br>
            <br>

            <div id="mis_correos">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th><?php echo $this->Paginator->sort('Remitente'); ?></th>
                        <th><?php echo $this->Paginator->sort('Enviado el'); ?></th>
                        <th><?php echo $this->Paginator->sort('Descripción'); ?></th>
                        <th><?php echo $this->Paginator->sort('Categoría'); ?></th>                        
                        <th class="actions"><?php echo __('Acciones'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($mailboxes as $mailbox): ?>
                    <?php    
                        if ($mailbox['Mailbox']['state'] == "unread")
                        {
                    ?>
                    <tr class="unread">
                    <?php
                        }
                        else
                        {
                    ?>
                    <tr class="">  
                    <?php
                        }
                    ?>
                        <td><?php echo h($mailbox['Mailbox']['sender']); ?>&nbsp;</td>
                        <td><?php echo h($mailbox['Mailbox']['created']); ?>&nbsp;</td>
                        <td><?php echo h($mailbox['Mailbox']['description']); ?>&nbsp;</td>
                        <td><?php echo h($mailbox['CategoryMail']['title']); ?>&nbsp;</td>
                        <!--<td>
                            <?php //echo $this->Html->link($mailbox['CategoryMail']['title'], array('controller' => 'CategoryMails', 'action' => 'view', $mailbox['CategoryMail']['id'])); ?>
                        </td>-->
                        <td class="actions">
                            <?php echo $this->Html->link(__('Ver'), array('action' => 'view', $mailbox['Mailbox']['id']), array('class' => 'btn btn-sm btn-default')); ?>
                            <?php 
                                if ( $mailbox['Mailbox']['sender'] == 'Kastella' ) 
                                {
                                    //se envia al controlador el nombre del destinatario (mi remitente), la descripcion (asunto para hacer RE: asunto) y el category_id
                                    echo $this->Html->link(__('Responder'), array('action' => 'reply', $mailbox['Mailbox']['sender'], $mailbox['Mailbox']['description'], $mailbox['CategoryMail']['id']), array('class' => 'btn btn-sm btn-default not-active'));
                                }
                                else
                                {
                                    echo $this->Html->link(__('Responder'), array('action' => 'reply', $mailbox['Mailbox']['sender'], $mailbox['Mailbox']['description'], $mailbox['CategoryMail']['id']), array('class' => 'btn btn-sm btn-default')); 
                                }
                            ?>
                            <?php echo $this->Form->postLink(__('Borrar'), array('action' => 'delete', $mailbox['Mailbox']['id']), array('class' => 'btn btn-sm btn-default'), __('¿Está seguro que desea eliminar el mensaje?' /* # %s?'*/, $mailbox['Mailbox']['id'])); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <p>
                <?php
                echo $this->Paginator->counter(array(
                'format' => __('Página {:page} de {:pages}, mostrando {:current} registros de un total de {:count}. {:start} - {:end}')
                ));
                ?>
                </p>
                <nav>
                    <ul class="pagination">
                        <li> <?php echo $this->Paginator->prev('< ' . __('anterior'), array('tag' => false), null, array('class' => 'prev disabled')); ?> </li>
                        <?php echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentTag' => 'a', 'currentClass' => 'active')); ?>
                        <li> <?php echo $this->Paginator->next(__('siguiente') . ' >', array('tag' => false), null, array('class' => 'next disabled')); ?> </li>
                    </ul>
                </nav>

            </div>

                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>