<?php 
    class PrivaciesController extends AppController
    {
        public $name = 'Privacies';
        // Declaracion de helpers que ayudaran en la creacion de formularios
        public $helpers = array ('Html', 'Form');
        
        // Declaracion de los componentes para inicio de sesion
        public $components = array('Session');




        public function beforeFilter() {
            parent::beforeFilter();

            // Allow users to register and logout.
            $this->Auth->allow('getPrivacies');
            
 
        }
        

        /**
         * Funcion que retorna las opciones de privacidad
         * @return JsonString Opciones de privacidad
         */
        public function getPrivacies(){

        	$this->autoRender = false;

        	echo json_encode($this->Privacy->find('all'));
        }


        
               
    }

