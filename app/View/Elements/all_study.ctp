<?php // Controller MarketResearchs ?>
      
        <?php if(!empty($estudios)) { ?>
        <?php foreach ($estudios as $estudio): ?>
        <div class="col-xs-12 col-md-6 col-lg-4 item">
            <div class="timeline-block">
            <div class="panel panel-default">
            
                <div class="panel-heading">
                <div class="media">
                    <div class="media-left">
                        <?php
                            echo $this->html->image($estudio['MarketResearch']['avatar'], array("alt" => "img user", 'class'=>"picture_user"));
                        ?>
                    </div>
                    <!--botones de eliminar y editar-->
                    <div class="media-body"> 
                    <?php 
                        if ( $estudio['MarketResearch']['username'] == $username )
                        {
                            echo "<div class='pull-right text-muted'>";
                            
                            echo $this->Form->postLink("<i class=\"fa fa-fw fa-remove\"></i>", //le image
                                                        array('action' => 'delete', $estudio['MarketResearch']['id']), //le url
                                                        array('escape' => false), //le escape
                                __('Està seguro que desea eliminar el estudio #%s?', $estudio['MarketResearch']['id']) //le confirm
                            );
                            
                            echo "<a class=\"b_editar\" id=\"edit".$estudio['MarketResearch']['id']."\" href=\"#\" onclick=\"edit_study(".$estudio['MarketResearch']['id'].");return false;\"><i class=\"fa fa-fw fa-edit\"></i></a>";
                            
                            echo "</div>";
                        }
                    ?>
                   
                    <!--FIN botones de eliminar y editar-->
                 <!-- <a href><?php //echo $estudio['MarketResearch']['username']; ?></a>-->
                 <a href><?php echo $this->Html->link(__($estudio['MarketResearch']['username']), array('controller' => 'users','action' => 'view', $estudio['MarketResearch']['user_id'])); ?></a>
                 <span><small><?php echo $estudio['MarketResearch']['Organization_name']; ?><br>
                 <?php echo $this->time->niceShort($estudio['MarketResearch']['created']); ?></small></span>
                </div> <!-- cierra media body -->
                </div> <!-- cierra media -->
                </div> <!-- cierra panel-heading -->
                <!-- comienza cuerpo mensaje-->
                <p class="content_publication" id="<?php echo "study_box".$estudio['MarketResearch']['id']; ?>" style="text-align:justify;">
                    <b class="h4 margin-none"><?php echo "ESTUDIO DE MERCADO #".$estudio['MarketResearch']['id']; ?></b><br>
                    <?php echo $estudio['MarketResearch']['content_research']; ?> <br><br> 
                        
                        <?php
                            //evalua si hay documentos adjuntos
                            if(isset($estudio['MarketResearch']['n_docs']))
                            {
                                for ($i=1;$i<=$estudio['MarketResearch']['n_docs'];$i++)
                                {
                                    echo $this->html->link($estudio['MarketResearch']['documento'.$i]['name'], '../app/webroot/'.$estudio['MarketResearch']['path_folder'].'/'.$estudio['MarketResearch']['documento'.$i]['name']);
                                    echo "<small>(".($estudio['MarketResearch']['documento'.$i]['size']/1000)."KB)</small>";
                                    echo "<br>";
                                }
                            }
                        ?>
                </p>
                <!-- FIN  cuerpo mensaje-->
            <ul class="comments" id="<?php echo "t_estimate".$estudio['MarketResearch']['id']; ?>">
                <?php 
                        
                        if(!empty($cotizaciones[$estudio['MarketResearch']['id']]))
                        {
                            echo $this->element('all_estimates',  
                            array(  'id' => $estudio['MarketResearch']['id'],
                                    'mr_user' => $estudio['MarketResearch']['user_id'],
                                    'cotizaciones' => $cotizaciones[$estudio['MarketResearch']['id']],
                                    'path_folder_study' => $estudio['MarketResearch']['path_folder']
                                    ));
                        }
                        
                ?>
            </ul>
                <?php 
                    echo "<ul class=\"comments\">";
                        echo "<li class=\"media\">"; 
                            //cada cotizacion pertenece a una única publicación, así que pasamos como parámetro el id
                            echo $this->element('new_estimate', array('id' => $estudio['MarketResearch']['id']));
                        echo "</li>";
                    echo "</ul>";
                ?>
            
            
            </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php } ?>
 