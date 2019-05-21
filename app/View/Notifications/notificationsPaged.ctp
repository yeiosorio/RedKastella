			<div class="panel panel-default" style="display: none;">



				<!-- Inicio Tabla de datos -->
                <div class="table-responsive">	
					<table class="table v-middle">
						<!-- <thead>
							<tr>
						 -->		<!-- nombre -->
								<!-- <th> // echo $this->Paginator->sort('User.name','Amigos'); </th> -->
							<!-- </tr> -->
						<!-- </thead> -->
						<tbody>
						
						<!-- Recorrido por los resultados -->
						<?php foreach ($notifications as $notification): ?>
						
							<tr>
								<td>

									<p>

										<b>
											Fecha de Notificación:
										</b>
										<?php echo $notification['NotificationsInterestContract']['created']; ?>
									
									</p>


									<p>
										<b>
											<?php echo $notification['InterestContract']['title']; ?>
										</b>
									</p>

									<p>
										<?php echo $notification['InterestContract']['contenido']; ?>	
									</p>

									<p>	
										<b>Valor: </b>$<?php echo $notification['InterestContract']['valor']; ?>
									</p>	
											
										
									<p>	
										<b>Link: </b>
										<a href="<?php echo $notification['InterestContract']['link']; ?>" target="_blank">
											<?php echo $notification['InterestContract']['link']; ?>
										</a>					
									</p>

									<p>
										<b>
											Ubicación:
										</b>
										<?php echo $notification['InterestContract']['ciudad'].", ".$notification['InterestContract']['departamento']; ?>
									</p>		
									
									<p>
										<b>
											Estado del Proceso:
										</b>
										<a href="<?php echo $notification['InterestContract']['link']; ?>" target="_blank">
											<?php echo $notification['ContractHistorials']['estado_del_proceso']; ?>
										</a>
									</p>
				
									<p>
										<b>
											Fecha de Apertura:
										</b>
										<?php echo $notification['ContractHistorials']['fecha_apertura_proceso']; ?>
									</p>
				

									<p>
										<b>
											Fecha de Cierre:
										</b>
										<?php echo $notification['ContractHistorials']['fecha_cierre_proceso']; ?>
									</p>
									
									<p>
										<b>
											Documentos:
										</b>
										<?php echo $notification['ContractHistorials']['number_of_docs']; ?>
									</p>

							
								
										

									
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

