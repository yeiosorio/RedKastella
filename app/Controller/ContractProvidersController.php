<?php 

/**
* Controlador de preferencias de usuario
* 
*/
  
class ContractProvidersController extends AppController
{


    public $name = 'ContractProviders';

    // var $uses = array('ContractPreferences'); 


    public function see(){

    		$this->autoRender = false;
            $this->setCharset();


            $ContractProvoders = $this->ContractProvider->find('all', array());    

            echo json_encode($ContractProvoders);
    }

}