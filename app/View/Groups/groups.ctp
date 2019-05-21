
<div class="st-content">
    <div class="st-content-inner scrollable_content">    
        <div class="container-fluid "> 

        	<?php 

        		   $loggedUser = AuthComponent::user();  

        	?>
        
        	<div class="col-md-12 col-lg-12 ">
			<div class="panel panel-default">

				<!-- Inicio Tabla de datos -->
                <div class="table-responsive">	
					<table class="table v-middle">
						<thead>
							<tr>
								<!-- nit  -->
								<th><?php echo $this->Paginator->sort('nit','Nit'); ?></th>
								
								<!-- nombre -->
								<th><?php echo $this->Paginator->sort('name','Nombre'); ?></th>
							
								<!-- slogan -->
								<th><?php echo $this->Paginator->sort('slogan','Slogan'); ?></th>
									
								<!-- Ubicación -->
								<th><?php echo $this->Paginator->sort('department_id','Ubicación'); ?> </th>

								<th></th>

							</tr>
						</thead>
						<tbody>
						

						<!-- Recorrido por los resultados -->
						<?php foreach ($Organizations as $Organization) : ?>
							<tr>
								<!-- nit  -->
								<td><?php echo $Organization['Organization']['nit']; ?></td>

								<!-- nombre -->	
								<td><?php echo $Organization['Organization']['name']; ?></td>
								
								<!-- slogan -->
								<td><?php echo $Organization['Organization']['slogan']; ?></td>
								
								<!-- Ubicación -->
								<td><?php echo $Organization['Municipality']['municipality'].', '.$Organization['Municipality']['Department']['name']; ?></td>
									
								<td>

									<!-- Si el usuario logueado no es el creador de la entidad -->
									<?php if ($loggedUser['id'] != $Organization['User']['id']): ?>

										<?php if ($Organization['Organization']['current_user_request'] == 0) : ?>
										
										<!-- Pertenecer  -->
										<button type="button" class="btn btn-primary belong-to-group" data-is-in="0" data-is-in-id="0" data-group-id="<?php echo $Organization['Organization']['id']; ?>">
											<i class="fa fa-user-plus btn-belongs-to-gls"></i> <span>Pertenecer</span>
										</button>

										<?php else: ?>

										<!-- Cancelar -->
										<button type="button" class="btn btn-warning belong-to-group" data-is-in="1" data-is-in-id="<?php echo $Organization['Organization']['current_user_request']; ?>" data-group-id="<?php echo $Organization['Organization']['id']; ?>">
											<i class="fa fa-user-times btn-belongs-to-gls"></i> <span>Cancelar Solicitud</span>
										</button>


										<?php endif; ?>		
										 	
									<?php else: ?> 


										<button class="btn btn-success"><i class="fa fa-user"></i> Creada por mi</button>

									<?php endif; ?>		

								</td>
								
							</tr>

						<?php endforeach; ?>
						<!-- Fin Recorrido de los resultados -->
						</tbody>
					</table>
					</div>
					<!-- Fin Tabla de datos -->


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


<?php 

    /**
     * Arreglo con los scripts necesarios
     * @var Array
     */
    $scripts = Array(

    	/**
         * Script de animación de botones
         */
        'buttonAnimations/buttonAnimations',
        
        /**
         * Jquery ui
         */
        'groups/groups',
 	
 	);

    /**
     * Imprimimos los scripts
     */
    echo $this->Html->script($scripts, array('block' => 'scriptBottom')); 
 


?>