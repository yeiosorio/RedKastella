
<!-- userPostLikeComplete -->

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
								<!-- nombre -->
								<th><?php echo $this->Paginator->sort('User.name','Nombre'); ?></th>
							</tr>
						</thead>
						<tbody>
						
						<!-- Recorrido por los resultados -->
						<?php foreach ($userPostLikeComplete as $userPostLike) : ?>
							<tr>
								<td>		
									<a href="<?php echo Router::url('/', true).'Users/profile/'.$userPostLike['User']['username']; ?>" style="float:left;">
			                          <div class="squared-image-top-friend" style="background-image:url('<?php echo $userPostLike['User']['profilePic']; ?>')"></div>
		                        	</a>	
		                        	<p>&nbsp;&nbsp;<?php echo $userPostLike['User']['username']; ?></p>
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
