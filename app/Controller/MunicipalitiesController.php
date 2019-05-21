<?php 
    class MunicipalitiesController extends AppController
    {
        public $name = 'Municipalities';
        // Declaracion de helpers que ayudaran en la creacion de formularios
        public $helpers = array ('Html', 'Form');
        

        // var $uses = array('Municipalities');

        // Declaracion de los componentes para inicio de sesion
        public $components = array('Session');
        
        //ELIMINAR ESTA FUNCION
        public function getByDepartment() {
            $department_id = $this->request->data['User']['select_state'];

            $subcategories = $this->Municipality->find('list', array(
            'conditions' => array('Municipality.department_id' => $department_id),
            'recursive' => -1
            ));

            $this->set('municipalities',$municipalities);
            $this->layout = 'ajax';
        }

        /**
        * Función que obtiene las ciudades por un id de departamento
        *
        */
        
        public function getCitiesByDepartmentId(){

            $this->setCharset();

            $this->autoRender = false;

            $departmentId = $this->request->data['departmentId'];

            $cities = $this->Municipality->find('all', array(
                'order'=> Array('Municipality.municipality'=>'ASC'),
                'conditions' => array('Municipality.department_id'=> $departmentId),
                'fields' => array(
                                'Municipality.id', 'Municipality.municipality'
                      )));    

            echo json_encode($cities);

        }


        /**
         * Función que obtiene el identificador de un departamento 
         * @return Int Indentificador
         */
        public function getByDepartmentIdByMunicipalityId(){

           $this->autoRender = false;

           $municipalityId = intval($this->request->data['municipalityId']);

           $municipality = $this->Municipality->find('first', Array('conditions' => Array('Municipality.id' => $municipalityId)));

           echo json_encode(Array('success'=>true, 'id'=> $municipality['Municipality']['department_id']));

        }

        public function beforeFilter() {
            parent::beforeFilter();
            // Allow users to register and logout.
            $this->Auth->allow('getCitiesByDepartmentId');

            $this->response->header('Access-Control-Allow-Origin','*');
            $this->response->header('Access-Control-Allow-Methods','*');
            $this->response->header('Access-Control-Allow-Headers','X-Requested-With');
            $this->response->header('Access-Control-Allow-Headers','Content-Type, x-xsrf-token');
            $this->response->header('Access-Control-Max-Age','172800');

        }       
    }

?>