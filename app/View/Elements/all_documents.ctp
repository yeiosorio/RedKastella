<?php // Controller Documents ?>

        <?php if(!empty($all_docs)) { ?>
        <?php foreach ($all_docs as $one_doc): ?>
        <div class="col-x-6 item" id="<?php echo "document".$one_doc['Document']['id']; ?>">
            <div class="timeline-block">
            <div class="panel panel-default">
            
                <div class="panel-heading">
                <div class="media">
                    <div class="media-left">
                        <?php
                            echo $this->html->image($one_doc['Document']['avatar'], array("alt" => "img user", 'class'=>"img-circle picture_user"));
                        ?>
                    </div>
                    <!--botones de eliminar y editar-->
                    <div class="media-body"> 
                    <?php 
                        if ( $one_doc['Document']['username'] == $username )
                        {
                            // FALTA ELIMINAR LA CARPETA Y LOS ARCHIVOS CORRESPONDIENTES - OJO
                            echo "<div class='pull-right text-muted'>";
                            
                            echo $this->Form->postLink("<i class=\"fa fa-fw fa-remove\"></i>", //le image
                                                        array('action' => 'delete', $one_doc['Document']['id']), //le url
                                                        array('escape' => false), //le escape
                                __('Està seguro que desea eliminar el documento #%s?', $one_doc['Document']['id']) //le confirm
                        );
                            echo "<a class=\"b_editar_doc\" id=\"edit".$one_doc['Document']['id']."\" href=\"#\" onclick=\"edit_doc(".$one_doc['Document']['id'].");return false;\"><i class=\"fa fa-fw fa-edit\"></i></a>";
                            
                            echo "</div>";
                        }
                    ?>
                    <!--FIN botones de eliminar y editar-->
                    <a href><?php echo $this->Html->link(__($one_doc['Document']['username']), array('controller' => 'users','action' => 'view', $one_doc['Document']['user_id'])); ?></a>
                    <span><?php echo $this->time->niceShort($one_doc['Document']['created']); ?></span>
                    </div>
                    </div> <!-- cierra media -->
                    </div> <!-- cierra panel-heading -->    
                    
                    <!-- comienza cuerpo mensaje-->
                    <p id="<?php echo "document_box".$one_doc['Document']['id']; ?>" style="text-align:justify;margin:1%;">
                            <b><?php //echo $one_doc['Document']['id']."."; ?></b>
                            <b class="h4 margin-none"><?php echo $one_doc['Document']['title_document']; ?></b><br>
                            
                            <?php echo $one_doc['Document']['content_document']; ?> <br>
                            <?php 
                                     if ($one_doc['Document']['link_secop'] != '')
                                     {
                                        echo "<b>Visitar Link</b>: <a href=\"".$one_doc['Document']['link_secop']."\">".$one_doc['Document']['link_secop']."</a>"; 
                                     }
                        
                            ?> <br>
                        
                            <br>
                            
                            <?php
                                //evalua si hay documentos adjuntos
                                if(isset($one_doc['Document']['n_docs']))
                                {
                                    for ($i=1;$i<=$one_doc['Document']['n_docs'];$i++)
                                    {
                                        echo $this->html->link($one_doc['Document']['documento'.$i]['name'], '../app/webroot/'.$one_doc['Document']['path_folder'].'/'.$one_doc['Document']['documento'.$i]['name']);
                                        echo "<small>(".($one_doc['Document']['documento'.$i]['size']/1000)."KB)</small>";
                                        echo "<br>";
                                    }
                                }
                            ?>
                    </p>
                    <!-- FIN  cuerpo mensaje-->
            </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php } ?>
 
