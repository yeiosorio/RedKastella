<?php 

/**
* Controlador de preferencias de usuario
* 
*/
  
class ContractCategoriesController extends AppController
{


    public $name = 'ContractCategories';

    // var $uses = array('ContractPreferences'); 


    /**
    * Función con la cual traemos las categorias de Secop
    *
    */
    public function getSecopCategories(){

    		$this->autoRender = false;
        

            $ContractCategories = $this->getCategoriesFrom('SECOP');
    
            echo json_encode($ContractCategories);
    }

    /**
    * Función con la que traemos las categorias de un proveedor
    * @param $contractProvider String nombre del proveedor 
    */

    public function getCategoriesFrom($contractProvider){

    	    $ContractCategories = $this->ContractCategory->find('all', array(
            			'conditions'=>array('ContractProvider.name'=> $contractProvider),
            			'contain' => 'ContractCategory'
            			)
            );    

            return $ContractCategories;
    }

}