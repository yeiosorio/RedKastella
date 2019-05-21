<?php
    App::uses('AppController', 'Controller');

    class AudiencesController extends AppController
    {
        



    	/**
    	 * Método usado para obtener audiencia
    	 */
    	public function take(){

    		$this->autoRender = false;
    		$person = $this->request->data;

    		$nombre = $person['nombre'];

    		$email = $person['email'];


    		$audience = $this->Audience->find('all',Array('conditions'=>Array('Audience.email'=>$email)));

    		if (!$audience) {
		
		        $this->Audience->create();
	    		$this->Audience->save(array('nombre'=>$nombre,'email'=>$email));
			    			
	    		echo json_encode(array('success'=>'created'));


    		}else{

    			echo json_encode(array('success'=>'exists'));

    		}

    	}


	 	public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('take');
       	}

  	}