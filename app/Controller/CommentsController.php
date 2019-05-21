<?php 
    class CommentsController extends AppController
    {
        public $name = 'Comments';
        // Declaracion de helpers que ayudaran en la creacion de formularios
        public $helpers = array ('Html', 'Form');
        
        // Declaracion de los componentes para inicio de sesion
        public $components = array('Session');
  	             
    }



?>