
<?php 

    $baseUrl = Router::url('/', true);
?>

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

								<!-- Título  -->
								<th width="15%"><?php echo $this->Paginator->sort('Post.id','Título'); ?></th>
								
								<!-- Descripción -->
								<th width="30%"><?php echo $this->Paginator->sort('content','Descripcion'); ?></th>
							
								<!-- Fecha -->
								<th width="15%"><?php echo $this->Paginator->sort('modified','Fecha'); ?></th>
								
								<!-- Tipo de Post -->
								<th width="15%"><?php echo $this->Paginator->sort('PostType.id','De'); ?></th>
								
								<!-- Archivos -->
								<th><?php echo 'Archivos'; ?></th>
							</tr>
						</thead>
						<tbody>

						<!-- Recorrido por los resultados -->
						<?php foreach ($myFolder as $folder) : ?>
							<tr>

								<!-- Título  -->
								<td><?php echo $folder['Post']['title']; ?></td>

								<!-- Descripción -->	
								<td style="text-align: justify;"><?php echo $this->StringUtil->limitWords($folder['Post']['content'],35).'...'; ?></td>
								
								<!-- Fecha -->
								<td><?php echo $this->Time->nice($folder['Post']['modified']); ?></td>
								
								<!-- Tipo de Post -->
								<td><?php echo $folder['PostType']['visible_name']; ?></td>
								
								<!-- Archivos -->
								<td>
									<!-- Si hay rescursos -->
									<?php if(count($folder['Resource'])): ?>

										<!-- Recorrido de los recurusos -->
										<?php foreach ($folder['Resource'] as $resource) : ?>
											
											<!-- Obtenemos el icono dependiendo del tipo de archivo usando el helper Icon -->
											<?php echo $this->Icon->icon($resource['ResourceExtension']['fileType']); ?> 
														
											<!-- Ruta del Archivo dentro de un link -->
											<a href ="<?php echo $resource['filePath']; ?>">
												<!-- //nomre del archivo -->
												<?php echo $resource['fileName']; ?>
											</a>

											<!-- formato del archivo -->
											(<?php echo $resource['size_format']; ?>)	

											<!-- Salto  -->
											<br/>  

										<?php endforeach;  ?>
										<!-- Fin Recorrido de Recursos -->

										<!-- Si no hay Recursos -->
										<?php else: ?>
										
											<div class="crying"></div>

									<?php endif;?>		
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
     * Arreglo con los estilos necesarios
     * @var Array
     */
    $styles = Array(
   

        // emogify library
        'emojify',

    );



    /**
     * Arreglo con los scripts necesarios
     * @var Array
     */
    $scripts = Array(
    
        // emogify library
        'emojify',


        'documents/myfolder'

    );


    /**
     * imprimimos los estilos
     */
    echo $this->Html->css($styles, null, array('block' => 'css'));   


    /**
     * Imprimimos los scripts
     */
    echo $this->Html->script($scripts, array('block' => 'scriptBottom')); 


    ?>

