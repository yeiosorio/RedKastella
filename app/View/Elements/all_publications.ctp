<?php // Controller Publications ?>
    
        <?php if(!empty($posts)) { ?>
        <?php foreach ($posts as $post): ?>
        <div class="col-xs-12 col-md-6 col-lg-4 item" style="" id="<?php echo "publication".$post['Publication']['id']; ?>">
        <!--div class="" style="width:30%; display:inline-block; float:left !important" id="<?php //echo "publication".$post['Publication']['id']; ?>"-->
            <div class="timeline-block">
            <div class="panel panel-default">
            
                <div class="panel-heading">
                <div class="media">
                    <div class="media-left">

                    <!-- <img src="/kastella/img/avatar/avatar.jpg" alt="img user" class="picture_user"> -->
                        <?php
                            echo $this->html->image($post['Publication']['avatar'], array("alt" => "img user", 'class'=>"picture_user"));
                        ?>
                    </div>
                    <!--botones de eliminar y editar-->
                    <div class="media-body"> 
                   
                    <!-- si el usuario que se recorre es el usuario logueado -->
                        
                    <?php if ( $post['Publication']['username'] == $username ) : ?>
                        

                            <div class='pull-right text-muted'>
                            
                        <?php   

                            echo $this->Form->postLink("<i class='fa fa-fw fa-remove'></i>", //le image
                                                        Array('action' => 'delete', $post['Publication']['id']), //le url
                                                        Array('escape' => false), //le escape
                                __('Está seguro que desea eliminar la publicación #%s?', $post['Publication']['id'])); //le confirm

                                ?>
                    


                         <?php    echo "<a class='b_editar' id='edit".$post['Publication']['id']."' href='#' onclick='edit_pub(".$post['Publication']['id'].");return false;'><i class='fa fa-fw fa-edit'></i></a>";
                            
                            echo "</div>";

                        ?>

                        <?php endif; ?>

                   
                    <!--FIN botones de eliminar y editar-->

                            <!-- link a perfil de usuario -->
                        <?php 
                                echo $this->Html->link(__($post['Publication']['username']), 
                                                       array('controller' => 'users','action' => 'view', $post['Publication']['user_id'])); 
                        ?>
                            <!-- fin link a perfil de usuario -->



                <!-- fecha de creación del post -->
                <span>
                    <?php echo $this->time->niceShort($post['Publication']['created']); ?>
                </span>
                
                </div> <!-- cierra media body -->
                </div> <!-- cierra media -->
                </div> <!-- cierra panel-heading -->
                


                <!-- Si hay una imgen adjunta, mostramos la primera -->
                <?php   if(!empty($post['Publication']['path_thumbnail'])) : ?>

                        <img src="http://localhost/kastella/resourcesFolder/1447080033251319885253906/144717355317870306968689.jpg" class="img-responsive" style="width:100%" alt="img publication">
                    
                <?php endif; ?>
                <!-- Fin imagen del contenedor -->

                <!-- FIN imagen del contenedor-->
                
                <!-- comienza cuerpo mensaje-->
                <p class="content_publication" id="<?php echo "publication_box".$post['Publication']['id']; ?>" style="text-align:justify;">
                    <b><?php //echo $post['Publication']['id']."."; ?></b>
                    <b class="h4 margin-none"><?php echo $post['Publication']['title_publication']; ?></b><br>
                        
                    <?php echo $post['Publication']['content_publication']; ?> <br><br> 
                        
                        <!-- documentos de la publicación -->
                        <?php if(isset($post['Publication']['n_docs'])) : ?>
                            
                            
                            <?php for ($i=1;$i<=$post['Publication']['n_docs'];$i++): ?>
                                    
                                    <!-- impersion de los archivos con la definición de su peso en Kb -->
                                  <?php echo $this->html->link($post['Publication']['documento'.$i]['name'], '../app/webroot/'.$post['Publication']['path_folder'].'/'.$post['Publication']['documento'.$i]['name']); ?>
                                  
                                    <small><?php echo "(". (($post['Publication']['documento'.$i]['size']) / 1000)." KB)"; ?></small>
                                    <br/>
                                
                            <?php endfor; ?>

                        <?php endif; ?>
                        <!-- Fin Documentos de la publicación -->
                </p>

            <!-- FIN  cuerpo mensaje-->
            
            <!-- comentarios del post -->
            <ul class="comments" id="<?php echo "t_comment".$post['Publication']['id']; ?>">
                <?php
                    if(!empty($comentarios[$post['Publication']['id']]))
                {
                    echo $this->element('all_comments',  array(  'id' => $post['Publication']['id'],
                                                            'comentarios' => $comentarios[$post['Publication']['id']]
                                                            ));
                }
                ?>
            </ul>    
            <!-- Fin de comentarios -->


            <!-- Agregar un nuevo comentario -->
                
                <!-- cada comentario pertenece a una única publicación, así que pasamos como parámetro el id -->
                    
                    <ul class='comments'>
                       <li class='media'>           
                        <?php  echo $this->element('new_comment', array('id' => $post['Publication']['id'])); ?>
                        </li>
                    </ul>

            <!-- Agregar un nuevo comentario -->
            
            </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php } ?>
    
