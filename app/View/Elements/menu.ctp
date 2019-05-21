<?php
    //http://enlacabezadecesar.com/blog/menu-desplegable-con-css/
    $id=$this->session->read('User.id');
    //print_r($opciones_menu);
?>

<div>
    <ul class="nav" id="side-menu">
<?php
    foreach($opciones_menu as $opcion_menu) {
?>
        <li>
        <?php
            //echo $this->html->link($chapt, array('controller'=>$routes[$i],'action'=>'index'), array('class' => 'item'), array ('escape'=>false));
            
            
            if ("Notificaciones"==$opcion_menu['Chapter']['page_title'])
            {
                echo $this->html->link($this->html->image($opcion_menu['Chapter']['icon_route'], array('width' => '38', 'height' => '38')) ." ".$opcion_menu['Chapter']['page_title'], array('controller'=>$opcion_menu['Chapter']['page_route'], 'action' => $opcion_menu['Chapter']['page_route_action'], $id), array('escape' => false, 'class' => 'not-active'));
            }
            else
            {
            echo $this->html->link($this->html->image($opcion_menu['Chapter']['icon_route'], array('width' => '38', 'height' => '38')) ." ".$opcion_menu['Chapter']['page_title'], array('controller'=>$opcion_menu['Chapter']['page_route'], 'action' => $opcion_menu['Chapter']['page_route_action'], $id), array('escape' => false));
            }
            
        ?>
        </li>   
<?php
    }
?>
    </ul>
</div>