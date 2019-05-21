<?php // Controller Publications ?>
<?php
//echo "<table id=\"t_comment".$id."\">";
    if(!empty($comentarios))
    {
                foreach ($comentarios as $comentario)
                    {
    
        echo "<li class=\"media\" style=\"text-align:justify;\" >";
                    echo "<div class=\"media-left\">";
            echo $this->html->image($comentario['avatar'], array("alt" => "img user comment", 'class'=>"picture_user_comment"));
                    echo "</div>";
                    echo "<div class=\"media-body\" id=\"comment".$comentario['id']."\">"; 
                    if ( $comentario['user_id'] == $this->session->read('User.id') )
                    {
                        echo "<div class=\"pull-right dropdown\" data-show-hover=\"li\" style=\"display: none\">";
                            echo "<a href=\"#\" data-toggle=\"dropdown\" class=\"toggle-button\" aria-expanded=\"true\">";
                            echo    "<i class=\"fa fa-pencil\" style=\"color: grey\"></i>";
                            echo "</a>";
                            echo "<ul class=\"dropdown-menu\" role=\"menu\">";
                            echo "<li>";
                            echo "<a class=\"b_editar_comment\" id=\"edit_comment".$comentario['id']."\" href=\"#\" onclick=\"edit_comm(".$comentario['id'].");return false;\">Editar</a>";
                            echo "</li>";
        /*echo "<button class=\"b_editar_comment\" id=\"edit_comment".$comentario['id']."\">";
                                            echo $this->Html->image(
                                                            'Pub_edit_icon.jpg', array('alt' => __('Editar'), 
                                                            'width' => 8, 'height' => 10));
                        echo "</button>";*/
                            echo "<li>";
                            echo $this->Form->postLink("Borrar", //le image
                                                array('action' => 'delete_comment', $comentario['id']), //le url
                                                array('escape' => false), //le escape
                                __('Està seguro que desea eliminar el comentario #%s?', $comentario['id']) //le confirm
                        ); 
                            echo "</li>";
                            echo "</ul>";
                        echo "</div>";
                    }
                    
                    echo "<a href class=\"comment-author pull-left>\">".$comentario['username']."</a>";
                    echo "<div class=\"comment-date\">".$comentario['created']."</div>";
                    echo "<span><p id=\"content-wrap\">".$comentario['content_comment']."</p></span>";
                    
                    echo "</div>";
        echo "</li>";
                }
    }

?>