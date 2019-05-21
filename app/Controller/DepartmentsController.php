<?php 
    class DepartmentsController extends AppController
    {
        public $name = 'Departments';

         var $uses = array('Departments');
        // // Declaracion de helpers que ayudaran en la creacion de formularios
        // public $helpers = array ('Html', 'Form');
        
        // // Declaracion de los componentes para inicio de sesion
        // public $components = array('Session');
     



       /**
        * Función que obtiene los departamentos
        *
        */ 
        public function getAllDepartments(){

        	$this->autoRender = false;
        	$this->setCharset();


        	    $departments = $this->Departments->find('all',array(
        	    				'order' => Array('Departments.name'=> 'ASC'),
        	    				'fields' => Array(
                                'Departments.id', 'Departments.name'
                                )));    

        	    echo json_encode($departments);


        }



        public function beforeFilter() {
            parent::beforeFilter();
            


            // Allow users to register and logout.
            $this->Auth->allow('getAllDepartments');

            $this->response->header('Access-Control-Allow-Origin','*');
            $this->response->header('Access-Control-Allow-Methods','*');
            $this->response->header('Access-Control-Allow-Headers','X-Requested-With');
            $this->response->header('Access-Control-Allow-Headers','Content-Type, x-xsrf-token');
            $this->response->header('Access-Control-Max-Age','172800');


        }       

    }

?>