<?php // Controller Publications ?>
    <div id="todas_mis_publicaciones">    
        <?php if(!empty($posts)) { ?>
        <?php foreach ($posts as $post): ?>
        <div class="col-xs-12 col-md-6 col-lg-4 item" id="<?php echo "publication".$post['Publication']['id']; ?>">
            <div class="timeline-block">
            <div class="panel panel-default">
            
                <div class="panel-heading">
                <div class="media">
                    <div class="media-left">
                        <?php
                            echo $this->html->image($post['Publication']['avatar'], array("alt" => "img user", 'class'=>"picture_user"));
                        ?>
                    </div>
                    <!--botones de eliminar y editar-->
                    <div class="media-body"> 
                    <?php 
                        if ( $post['Publication']['username'] == $username )
                        {
                            echo "<div class='pull-right text-muted'>";
                            
                            echo $this->Form->postLink("<i class=\"fa fa-fw fa-remove\"></i>", //le image
                                                        array('action' => 'delete', $post['Publication']['id']), //le url
                                                        array('escape' => false), //le escape
                                __('Està seguro que desea eliminar la publicación #%s?', $post['Publication']['id']) //le confirm
                        );
                            echo "<a class=\"b_editar\" id=\"edit".$post['Publication']['id']."\" href=\"#\" onclick=\"edit_pub(".$post['Publication']['id'].");return false;\"><i class=\"fa fa-fw fa-edit\"></i></a>";
                            
                            echo "</div>";
                        }
                        ?>
                   
                    <!--FIN botones de eliminar y editar-->
                <a href><?php echo $this->Html->link(__($post['Publication']['username']), array('controller' => 'users','action' => 'view', $post['Publication']['user_id'])); ?></a>
                <span><?php echo $this->time->niceShort($post['Publication']['created']); ?></span>
                </div>
                </div> <!-- cierra media -->
                </div> <!-- cierra panel-heading -->
                <!-- imagen del contenedor-->
                <?php
                    //incluye app/webroot para modificacion en la nube
                    if(!empty($post['Publication']['path_thumbnail']))
                    {
                        echo $this->html->image('../app/webroot/'.$post['Publication']['path_folder'].'/'.$post['Publication']['path_thumbnail'], array("alt" => "img publication", 'class'=>"img-responsive", 'style' => 'width:100%;'));
                    }
                    
                ?>
                <!-- FIN imagen del contenedor-->
                <!-- comienza cuerpo mensaje-->
                <p class="content_publication" id="<?php echo "publication_box".$post['Publication']['id']; ?>">
                    <b><?php echo $post['Publication']['id']."."; ?></b>
                    <b class="h4 margin-none"><?php echo $post['Publication']['title_publication']; ?></b><br>
                        
                    <?php echo $post['Publication']['content_publication']; ?> <br><br> 
                        
                        <?php
                            //evalua si hay documentos adjuntos
                            if(isset($post['Publication']['n_docs']))
                            {
                                for ($i=1;$i<=$post['Publication']['n_docs'];$i++)
                                {
                                    echo $this->html->link($post['Publication']['documento'.$i]['name'], '../app/webroot/'.$post['Publication']['path_folder'].'/'.$post['Publication']['documento'.$i]['name']);
                                    echo "<small>(".($post['Publication']['documento'.$i]['size']/1000)."KB)</small>";
                                    echo "<br>";
                                }
                            }
                        ?>
                </p>
                <!-- FIN  cuerpo mensaje-->
            <ul class="comments">
                <?php //echo "<li id=\"t_comment".$post['Publication']['id']."\" class=\"media\">"; 
                        echo "<li class=\"media\">"; 
                            echo "<table id=\"t_comment".$post['Publication']['id']."\">";
                if(!empty($comentarios[$post['Publication']['id']]))
                {
                    echo $this->element('all_comments',  array(  'id' => $post['Publication']['id'],
                                                            'comentarios' => $comentarios[$post['Publication']['id']]
                                                            ));
                }
                            echo "</table>";
            echo "</li>";
            ?>        
                <?php 
                    //cada comentario pertenece a una única publicación, así que pasamos como parámetro el id
                    echo $this->element('new_comment', array('id' => $post['Publication']['id']));
                ?>
            
            </ul>
            </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php } ?>
    </div>
