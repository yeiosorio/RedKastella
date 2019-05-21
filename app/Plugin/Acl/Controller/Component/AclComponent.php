<?php

App::uses('Component', 'Controller');

class AclComponent extends Component
{

    public function __construct() {
       	
    	//carga de modelos
        $this->Acos                 = ClassRegistry::init('Acl.Acos');
        $this->Aros 	            = ClassRegistry::init('Acl.Aros');
        $this->PermissionRoles 		= ClassRegistry::init('Acl.PermissionRoles');
        $this->Roles 	            = ClassRegistry::init('Acl.Roles');
        $this->User 				= ClassRegistry::init('User');

        /**
         * Variable que define el contexto de la aplicación
         * @var Array
         */
        $this->App = null;
    }


    /**
     * Función que configura el contexto de la aplicación en el componente
     * @param Array $app contexto
     */
    public function setApp($app = null){

        $this->App = $app;
    }


    /**
     * Funcion que determina si el usuario tiene o no permisos para acceder a la ruta actual
     * @return boolean true si tiene permiso y esta activo el permiso, false si no lo tiene o esta inactivo
     */    

    /**
     * Función que autoriza un usuario para ir a un lugar de la aplicación
     * @param  Array $app  Contexto de la aplicación
     * @param  Array $user Información del usuario
     * @return Boolean      si tiene acceso o no
     */
    public function authorize($app = null, $user = null){

        /**
         * Configuramos el contexto de la aplicación
         */
        $this->setApp($app);

        if($user){
           
            $role_id = $user['role_id'];
    
            $result = $this->PermissionRoles->find('first',
                            array('conditions'=> array(
                                    'aro_id' => $this->getAroId(),
                                    'aco_id'=>$this->getAcoId(),
                                    'role_id'=>$role_id,
                                    'access'=>1)));

            if ($result) { return true; } else { return false;  }
                 
        } else{  return false; }
        
    }
    
    /**
     * Funcion para obtener id el Aro (Access Request Object)
     * @return int entero con el id, si no lo encuentra retornara 0
     */
    public function getAroId(){

        $AroName = $this->getThisAroName();
 
        $result = $this->Aros->find('first',array('conditions'=>array('name' => $AroName)));

        if ($result) { return $result['Aros']['id']; } else { return 0; }
    }

    /**
     * Funcion para obtener id el Aco (Access Control Object)
     * @return int entero con el id, si no lo encuentra retornara 0
     */
    public function getAcoId(){
        $AcoName =  $this->getThisAcoName();
   
        $result = $this->Acos->find('first',array('conditions'=>array('name' => $AcoName)));
           
        if ($result) { return $result['Acos']['id']; } else { return 0; }
        
    }

    /**
     * Funcion para obtener el nombre del Aro Actual en miniscula
     * @return String
     */    
    public function getThisAroName(){
    
       return strtolower($this->App->request->controller);
    }
    
    /**
     * Funcion para obtener el nombre del Aco Actual en minuscula
     * @return String
     */    
    public function getThisAcoName(){
       return strtolower($this->App->request->action);
    }

    /**
     * Funcion que imprime el nombre del Aro y Aco Actual
     */    
    public function prAroAco(){
        echo $this->getThisAroName()." - ".$this->getThisAcoName();
    }

}