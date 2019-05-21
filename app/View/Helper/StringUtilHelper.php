<?php

App::uses('AppHelper', 'View/Helper');

/**
 * Helper para mostrar los iconos de los archivos por tipo
 */
class StringUtilHelper extends AppHelper {
    		
    public function limitWords($string = null, $limit = null) {

    	$words = explode(" ", $string);
    	return implode(" ",array_splice($words,0,$limit));

    	
    } 
}

