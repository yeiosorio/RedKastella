<div class="st-content">
    <div class="st-content-inner scrollable_content">    
        <div class="container-fluid "> 

        	<div class="col-md-12 col-lg-12 ">
			<div class="panel panel-default">
<!-- Inicio Tabla de datos -->
<div class="table-responsive">	
	<table class="table v-middle">

	<thead>
	<tr>

			<th style="width: 12%;"><?php echo $this->Paginator->sort('created','Fecha'); ?></th>

<!-- 			<th><?php // echo $this->Paginator->sort('users_id'); ?></th> -->

			<th><?php echo $this->Paginator->sort('author'); ?></th>

			<!-- <th><?php //echo $this->Paginator->sort('category'); ?></th> -->


			<th style="width: 20%;"><?php echo $this->Paginator->sort('contenido'); ?></th>

			<th><?php echo $this->Paginator->sort('departamento'); ?></th>

			<!-- <th><?php // echo $this->Paginator->sort('link'); ?></th> -->

			<!-- <th><?php //echo $this->Paginator->sort('nombre'); ?></th> -->


			<th><?php echo $this->Paginator->sort('title'); ?></th>

			<th><?php echo $this->Paginator->sort('valor'); ?></th>

			<th><?php echo $this->Paginator->sort('num_constancia'); ?></th>

			<th class="actions"><?php echo __('Actions'); ?></th>

	</tr>
	</thead>
	<tbody>
	<?php foreach ($interestContracts as $interestContract): ?>
	<tr>
 
		<td><?php echo h($interestContract['InterestContract']['created']); ?>&nbsp;</td>
		<!-- <td> -->
			<?php // echo $this->Html->link($interestContract['Users']['name'], array('controller' => 'users', 'action' => 'view', $interestContract['Users']['id'])); ?>
		<!-- </td> -->
		<td><?php echo h($interestContract['InterestContract']['author']); ?>&nbsp;</td>
		 <!-- <td><?php // echo h($interestContract['InterestContract']['category']); ?>&nbsp;</td> -->
		<td>
		
			<?php echo $this->StringUtil->limitWords($interestContract['InterestContract']['contenido'],25).'...'; ?>
		</td>

		<td>

			<?php 
			
				echo $interestContract['InterestContract']['ciudad'] .", ".$interestContract['InterestContract']['departamento']; 
			?>

		</td>


		<!-- <td><?php // echo h($interestContract['InterestContract']['nombre']); ?>&nbsp;</td> -->
		<td>

			<a href="<?php echo $interestContract['InterestContract']['link']; ?>">
				<?php echo h($interestContract['InterestContract']['title']); ?>
			</a>

		</td>
		<td><?php echo h($interestContract['InterestContract']['valor']); ?>&nbsp;</td>
		<td><?php echo h($interestContract['InterestContract']['num_constancia']); ?>&nbsp;</td>
		<td class="actions">

			<?php 
				echo $this->Html->link(__('Ver'), 
						array('action' => 'view', $interestContract['InterestContract']['id']), 
						array('class'=>'btn btn-primary')
					); 
			?>
		
			<?php echo $this->Html->link(__('Editar'), array('action' => 'edit', $interestContract['InterestContract']['id']), 	array('class'=>'btn btn-primary')
					); ?>
		
			<?php echo $this->Form->postLink(__('Eliminar'), array('action' => 'delete', $interestContract['InterestContract']['id']), array('class'=>'btn btn-primary','confirm' => __('Are you sure you want to delete # %s?', $interestContract['InterestContract']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
		</div>

      		<!-- Pie de Página  -->
             	   	<div class="panel-footer padding-none text-center">
               
				    	<p class="pagination-info">
				    		<?php echo $this->Paginator->counter(Array('format' => __('Página {:page} de {:pages}, se muestran {:current} resultados.'))); ?> 
				    	</p>
	                 
		                <ul class="pagination">
	                        <li> 
	                        	<?php echo $this->Paginator->prev('< ' . __('anterior'), array('tag' => false), null, array('class' => 'prev disabled')); ?> 
	                        </li>
	                        
	                        <?php echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentTag' => 'a', 'currentClass' => 'active')); ?>
	                        
	                        <li> 
	                        	<?php echo $this->Paginator->next(__('siguiente') . ' >', array('tag' => false), null, array('class' => 'next disabled')); ?>
	                        </li>
	                    </ul>
                
                	</div>


                	<!-- Fin pie de página -->


            </div>
        </div>
        </div>
    </div>
</div>




<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Interest Contract'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Users'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
