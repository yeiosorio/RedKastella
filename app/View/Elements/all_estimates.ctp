<?php // Controller Publications ?>
<?php
//echo "<table id=\"t_comment".$id."\">";
    $my_rol = $this->session->read('User.rol_id');
    $my_id = $this->session->read('User.id');
    $my_org = $this->session->read('User.org_id');
    if(!empty($cotizaciones))
    {
        foreach ($cotizaciones as $cotizacion)
        {
    
            echo "<li class=\"media\" style=\"text-align:justify;\" >";
                echo "<div class=\"media-left\">";
                    echo $this->html->image($cotizacion['avatar'], array("alt" => "img user comment", 'class'=>"picture_user_comment"));
                echo "</div>";
                echo "<div class=\"media-body\" id=\"comment".$cotizacion['id']."\">"; 
                    
                
                // El admin de la entidad que hizo la solicitud de estudio de mercado, o quien hizo dicha solicitud, puede moderar una cotizacion
                if ( ($cotizacion['user_id'] == $my_id) || (($my_rol==3)&&($cotizacion['organization_id']==$my_org)) || (($mr_user==$my_id)&&($cotizacion['organization_id']==$my_org)) )
                {
                    echo "<div class=\"pull-right dropdown\" data-show-hover=\"li\" style=\"display: none\">";
                        echo "<a href=\"#\" data-toggle=\"dropdown\" class=\"toggle-button\" aria-expanded=\"true\">";
                        echo    "<i class=\"fa fa-pencil\" style=\"color: grey\"></i>";
                        echo "</a>";
                        echo "<ul class=\"dropdown-menu\" role=\"menu\">";

                            //solo el dueño de la cotizacion puede editarla o borrarla
                            if ( $cotizacion['user_id'] == $my_id )
                            {
                                echo "<li>";
                                echo "<a class=\"b_editar_estimate\" id=\"edit_estimate".$cotizacion['id']."\" href=\"#\" onclick=\"edit_coti(".$cotizacion['id'].");return false;\">Editar</a>";
                                echo "</li>";
                            
                                echo "<li>";
                                echo $this->Form->postLink("Borrar", //le image
                                                    array('action' => 'delete_estimate', $cotizacion['id']), //le url
                                                    array('escape' => false), //le escape
                                    __('Està seguro que desea eliminar la cotizacion #%s?', $cotizacion['id']) //le confirm
                                ); 
                                echo "</li>";
                            }
                            if ((( ($my_rol==3)&&($cotizacion['organization_id']==$my_org) )||( ($mr_user==$my_id)&&($cotizacion['organization_id']==$my_org) ))&&($cotizacion['user_id'] != $my_id ))
                            {
                            echo "<li>";
                                echo $this->Form->postLink("Moderar", //le image
                                                array('action' => 'moderate_estimate', $cotizacion['id'], $cotizacion['user_id'],$my_id, $id), //le url
                                                array('escape' => false), //le escape
                                __('Està seguro que desea moderar la cotizacion #%s?', $cotizacion['id']) //le confirm
                                ); 
                            echo "</li>";
                            }
                        echo "</ul>";
                    echo "</div>";
                        
                }
                    echo "<a href class=\"comment-author pull-left>\">".$cotizacion['username']."</a>";
                    echo "<div class=\"comment-date\">".$cotizacion['created']."</div>";
                    echo "<div id=\"cotizacion".$cotizacion['id']."\" class=\"respuesta_estudio\" >";
                        
                    if(!$cotizacion['moderated'])
                    {
                        //Si es entidad, imprima:
                        if ( $my_rol==3 || $my_rol==4 || $my_rol==5 || $cotizacion['user_id'] == $my_id )
                        {
                            echo "<span><p id=\"content-wrap\">".$cotizacion['content_estimate']."</p></span>";
                            if(isset($cotizacion['n_docs']))
                            {
                               for ($i=1;$i<=$cotizacion['n_docs'];$i++)
                                {
                                    echo $this->html->link(
                                        $cotizacion['documento'.$i]['name'],
                                        '../app/webroot/'.$path_folder_study
                                        .'/'.$cotizacion['path_folder']
                                        .'/'.$cotizacion['documento'.$i]['name']);
                                    echo "<small>(".($cotizacion['documento'.$i]['size']/1000)."KB)</small>";
                                    echo "<br>";
                                }
                            }
                        }
                        // Si es proveedor, imprima:
                        else
                        {
                            echo "<span style=\"color:#CDCDCD;\">".$cotizacion['username']." ha compartido una cotización.</span>";
                        }
                    }
                    else
                    {
                        echo "<span style=\"color:#CDCDCD;\">Esta participación ha sido moderada.</span>";
                    }
                    echo "</div>";
                echo "</div>"; //cierra media body
            echo "</li>";
        }
    }

?>