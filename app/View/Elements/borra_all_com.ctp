<?php // Controller Publications ?>
<?php
//echo "<table id=\"t_comment".$id."\">";
    if(!empty($comentarios))
    {
                foreach ($comentarios as $comentario)
                    {
    echo "<tr>";
        echo "<td style=\"width:6%;\">";
            echo $this->html->image($comentario['avatar'], array("alt" => "img user comment", 'class'=>"picture_user_comment"));
        echo "</td>";
                    
        //comentario con fecha, usuario y contenido
        echo "<td id=\"comment".$comentario['id']."\">";
                    echo "<small>".$comentario['created']."</small><br>";
                    //echo "<b>".$comentario['username'];
                    echo "<b>"."<a href>".$this->Html->link(__($comentario['username']), array('controller' => 'users','action' => 'view', $comentario['user_id']))."</a>";
                    echo ": </b>".$comentario['content_comment'];
        echo "</td>";
        echo "<td style=\"width:6%;\">";
                    if ( $comentario['user_id'] == $this->session->read('User.id') )
                    {
        echo $this->Form->postLink($this->Html->image(
                                                'Pub_delete_icon.png', array('alt' => __('Borrar'),
                                                'width' => 10, 'height' => 10)), //le image
                                                array('action' => 'delete_comment', $comentario['id']), //le url
                                                array('escape' => false), //le escape
                                __('Està seguro que desea eliminar el comentario #%s?', $comentario['id']) //le confirm
                        ); 
                        echo "<button class=\"b_editar_comment\" id=\"edit_comment".$comentario['id']."\">";
                                            echo $this->Html->image(
                                                            'Pub_edit_icon.jpg', array('alt' => __('Editar'), 
                                                            'width' => 8, 'height' => 10));
                        echo "</button>";
                    }
        echo "</td>";
    echo "</tr>";
                    }
    }

//echo "</table>";
?>