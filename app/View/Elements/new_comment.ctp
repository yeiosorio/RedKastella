<?php // Controller Publications ?>
<?php
    // Elemento que corresponde al formulario de un nuevo comentario (uno por publicación)
    $user_id=$this->session->read('User.id');
?>

<?php
    echo $this->Form->create('Comment'.$id, array(/*'id' => 'CommentIndexForm'.$id,*/ 'default' => false));//, 'url'=>array('controller'=>'publications', 'action'=>'index',$this->session->read('User.id'))));
    //echo $this->Form->create('Comment', array('id' => 'f_comment'.$id, 'default' => false)); // Con la opción “default=>false” indicamos que el botón del formulario no haga el submit, lo haremos nosotros vía ajax.
    echo $this->Form->input('state_comment', array('value'=>'visible','type' => 'hidden'));
    echo $this->Form->input('publication_id', array('value'=>$id,'type' => 'hidden'));
    echo $this->Form->input('user_id', array('value'=>$user_id,'type' => 'hidden'));
?>
<!--<li class="comment-form">-->
    <div class="input-group">
    <span class="input-group-btn"> <?php echo $this->html->image($this->session->read('User.avatar'), array("alt" => "img user", 'class'=>'circle','style'=>array("width:40px"))); ?> </span>
    <?php
        echo $this->Form->input('content_comment', array('placeholder'=>'Ingrese su testimonio...', 'label'=>'', 'rows'=>'2', 'maxlength' => '200', 'div'=>false, 'class'=>'form-control', 'style'=>array('width:100%')));
    ?>
    </div>
<!--</li>-->
<?php
        echo $this->Form->submit(__('Comentar',true), array('type' => 'button', 'class'=>'b_new_comment btn btn-primary', 'id'=>'b_new_comment'.$id));//, 'onclick'=>'nuevo_comentarioo('.$id.');')); 
        echo $this->Form->end();
        /*echo $this->Js->submit('Comentar', array( 
              'update'=>'#t_comment'.$id, 
              'url' => array('controller' => 'Publication', 'action' => 'newCo'), 
            )); */
        //echo $this->Js->submit('Comentar', array('url'=> array('controller'=>'Publication', 'action'=>'edit'), 'update' => 'post'));
?>
<br>
