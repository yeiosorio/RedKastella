<?php 
    class DocumentsController extends AppController
    {
        public $name = 'Documents';
        var $uses = array('Document', 'User', 'Chapter', 'Privacy', 'Publication', 'MarketResearch', 'EstimateResearch','Post');
        // Declaracion de helpers que ayudaran en la creacion de formularios
        public $helpers = array ('Html', 'Form', 'Time', 'Js','Icon','StringUtil','Session');
        
        // Declaracion de los componentes para inicio de sesion
        public $components = array('Session','RequestHandler', 'Paginator');
        
        public function beforeFilter() {

            parent::beforeFilter();
 
        }
    
    

        /**
         * Función que obtiene las publicaciones
         * @return JsonString String con los datos en formato Json
         */
        public function myFolder(){

          /**
           * Configuración de la consulta de posts
           * @var Array
           */
          $this->Paginator->settings = Array(
                        'order'       =>  'Post.modified DESC',
                        'limit'       =>  10, 
                        'conditions' =>Array('User.id'=> $this->Auth->user('id')),
                        'recursive' => 2
          );  

          /**
           * Asignamos el resultado de la paginación
           */ 
          $this->set('myFolder',$this->paginate($this->Post));


        }


     
        
    }

?>