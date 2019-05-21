


<div class="st-content">
    <div class="st-content-inner scrollable_content">    
        <div class="container-fluid "> 

        	<div class="col-md-8 col-lg-8 ">
			<div class="panel panel-default">
            
<?php echo $this->Form->create('InterestContract', array('class'=>'', 'style'=>'padding:15px;')); ?>

	<?php
		
		echo $this->Form->input('author',
			array(
					'class'=>'form-control',
					'label'=>'Email'
				)
		);

		echo $this->Form->input('category', 
			array(
					'class'=>'form-control',
					'label'=>'Departamento'
				)
			);

		echo $this->Form->input('ciudad', 
			array(
					'class'=>'form-control',

				)
			);

		echo $this->Form->input('contenido', 
				array('class'=>'form-control')
			);


		echo $this->Form->input('link', 
				array('class'=>'form-control')
			);

		echo $this->Form->input('nombre', 
				array('class'=>'form-control')
			);

		echo $this->Form->input('title', 
				array('class'=>'form-control')
			);

		echo $this->Form->input('valor', 
				array('class'=>'form-control')
			);

		echo $this->Form->input('num_constancia', array('class'=>'form-control')
		);

		
	?>

	<button class="btn btn-primary btn-block"><i class="fa fa-save"></i> Guardar</button>

<?php echo $this->Form->end(); ?>


			</div>
        </div>
        </div>
    </div>
</div>
