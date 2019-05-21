<?php // Controller MarketResearch ?>
<?php
    // Elemento que corresponde al formulario de un nuevo comentario (uno por publicación)
    $user_id=$this->session->read('User.id');
?>

<?php
    echo $this->Form->create('EstimateResearch'.$id, array(/*'id' => 'EstimateResearchIndexForm'.$id,*/ 'default' => false, 'enctype'=>'multipart/form-data'));//, 'url'=>array('controller'=>'Market_Researches', 'action'=>'index',$this->session->read('User.id'))));
    //echo $this->Form->create('EstimateResearch', array('id' => 'f_estimate'.$id, 'default' => false)); // Con la opción “default=>false” indicamos que el botón del formulario no haga el submit, lo haremos nosotros vía ajax.
    echo $this->Form->input('user_id', array('value'=>$user_id,'type' => 'hidden'));
    echo $this->Form->input('market_researches_id', array('value'=>$id,'type' => 'hidden'));
    //echo $this->Form->input('user_id', array('value'=>$this->session->read('User.id'),'type' => 'hidden'));
?>
<div class="input-group">
    <span class="input-group-btn" style="padding-right: 10px;"> <?php echo $this->html->image($this->session->read('User.avatar'), array("alt" => "img user", 'class'=>'circle','style'=>array("width:40px"))); ?> </span>
    <?php
        echo $this->Form->input('content_estimate', array('placeholder'=>'Ingrese su respuesta...', 'label'=>'', 'maxlength' => '500', 'rows' => '2', 'class'=>'form-control', 'div'=>false, 'required' => 'required', 'style'=>array('width:100%')));
        
    ?>
</div>
<?php
        echo $this->Form->input('documents_estimate',array('type' => 'file', 'multiple' => 'multiple', 'label'=>false));
        echo "<br />";
        echo $this->Form->submit(__('Cotizar',true), array('type' => 'button', 'class'=>'b_new_estimate btn btn-primary', 'id'=>'b_new_estimate'.$id));
        echo $this->Form->end();
?>
    <div id="<?php echo "message_doc".$id; ?>"> </div>
<br>
