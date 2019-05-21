<?php 
    class ChaptersController extends AppController
    {
        public $name = 'Chapters';
        // Declaracion de helpers que ayudaran en la creacion de formularios
        public $helpers = array ('Html', 'Form');
        
        // Declaracion de los componentes para inicio de sesion
        public $components = array('Session');
           


        public function getChapters(){

        	 // Se envian a la vista todas las categorias y datos asociados al menu
        $opciones_menu = $this->Chapter->find('all',  //el método puede ser 'all' o 'list'
                                               array('fields' => array(
                                                                        'Chapter.page_title',
                                                                        'Chapter.page_route',
                                                                        'Chapter.page_route_action',
                                                                        'Chapter.icon_route'
                                                                       ),
                                                            
                                                     //'conditions' => array('Chapter.role_id' => $this->Auth->user('role_id') )
                                                     'conditions' => array('Chapter.role_id' => $this->Auth->user('role_id') )
                                                    )
        );    
            
            return $opciones_menu;	

        }

    }

?>