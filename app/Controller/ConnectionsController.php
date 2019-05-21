<?php
    App::uses('AppController', 'Controller');

    class ConnectionsController extends AppController
    {
        // Mediante este controlador se puede implementar futuramente un LOG
        public $name = 'Connections';
        // Declaracion de los componentes para inicio de sesion
        public $components = array('Session', 'RequestHandler'); 
        
        
        var $uses = array('User', 'Person', 'Chapter', 'Organization', 'OrganizationUser');
        
        // Declaracion de helpers que ayudaran en la creacion de formularios
        public $helpers = array ('Html', 'Form', 'Time', 'Js'); 
        
        
        public function beforeFilter() {
            parent::beforeFilter();
            // Diferente Layout
      
            
            /*********** FRAGMENTO DE CODIGO A IMPLEMENTAR EN TODOS LOS CONTROLADORES ***********
            /******** CUYAS FUNCIONES POSEAN LAYOUTS E INCLUYAN EL BUSCADOR DE PERSONAS *********/
            // Se envian a la vista todas los usuarios disponibles para realizar la busqueda
            $cont_buscador = $this->User->find('all',  //el método puede ser 'all' o 'list'
                array(
                    'fields' => array(
                                        'User.id',
                                        'User.username',
                                        'User.name',
                                        'User.surname',
                                        'Person.path_avatar'
                                       ),
                    'recursive' => -1,
                    'joins' => array(
                        array(
                            'table' => 'people',
                            'alias' => 'Person',
                            'type' => 'left',
                            'conditions' => array(
                                'Person.user_id = User.id'
                            )
                        ))
                     //'conditions' => array('Chapter.role_id' => $this->Auth->user('role_id') )
                     //'conditions' => array('Chapter.role_id' => '1' )
                )
            );   
           
            $this->set('cont_buscador', $cont_buscador);
           
            /****************** FINAL DEL SET DE VARIABLES PARA LA BUSQUEDA ***********************/
            
            /*********** FRAGMENTO DE CODIGO A IMPLEMENTAR EN TODOS LOS CONTROLADORES *************
            /*********************** PARA LA CARGA DE LAS OPCIONES DEL MENU ***********************/
            // Se envian a la vista todas las categorias y datos asociados al menu
            $my_rol=$this->Session->read('User.rol_id');
            $opciones_menu = $this->Chapter->find('all',  
                                                    array('fields' => array('Chapter.page_title', 
                                                                            'Chapter.page_route', 
                                                                            'Chapter.page_route_action',
                                                                            'Chapter.icon_route'
                                                                                ),
                                                      'conditions' => array('Chapter.role_id' => $my_rol )
                                                            )
                                                    );    
            
            $this->set('opciones_menu',$opciones_menu);
            /*********************** FINAL CARGA DE LAS OPCIONES DEL MENU *************************/
        }
    }
?>