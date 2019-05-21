<?php 
    class PermissionsController extends AppController
    {
        public $name = 'Permissions';
        // Declaracion de helpers que ayudaran en la creacion de formularios
        public $helpers = array ('Html', 'Form');
        
        // Declaracion de los componentes para inicio de sesion
        public $components = array('Session');
               
    }

?>