<?php

App::uses('AppHelper', 'View/Helper');

/**
 * Helper para mostrar los iconos de los archivos por tipo
 */
class IconHelper extends AppHelper {
    
    public function icon($fileType = null) {

    	switch ($fileType) {

    		case 'audio':
				
				$icon = '<i class="fa a-volume-up">'; 	

    			break;
   			
   			case 'video':
   			    $icon = '<i class="fa fa-video-camera">'; 
   				break;

   			case 'image':

   				$icon = '<i class="fa fa-image">'; 
   				break;

			case 'document':

				$icon = '<i class="fa fa-file-text">&nbsp;'; 

				break;

       	}

    	return $icon." ";
    } 
}