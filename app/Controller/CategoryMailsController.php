<?php 
    class CategoryMailsController extends AppController
    {
        public $name = 'CategoryMails';
        // Declaracion de helpers que ayudaran en la creacion de formularios
        public $helpers = array ('Html', 'Form');
        
        // Declaracion de los componentes para inicio de sesion
        public $components = array('Session');
        
        public function index() {
            /*
            $this->CategoryMail->recursive = 0;
            $this->paginate['CategoryMail']['limit'] = 3;
            //$this->paginate['Mesero']['conditions'] = array('Mesero.dni' => "34343");
            $this->paginate['CategoryMail']['order'] = array('CategoryMail.id' => 'asc');
            //$this->Paginator->settings = $this->paginate;
            $this->set('CategoryMails', $this->paginate());
            */
	   }
               
    }

?>