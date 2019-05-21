<?php
App::uses('AppModel', 'Model');

    /**
     * Modelo creado exclusivamente para la relacion de solicitudes de usuarios
     */
    class RequestedUser extends AppModel 
    {
            
        public $useTable = 'users';

        var $hasMany = array(

            'OrganizationUser',

            'Organization' => array(
                    'className' => 'Organization',
                    'foreignKey'   => 'user_id'
        ));


        public $belongsTo = array(
        'Municipality' => array(
            'className' => 'Municipality',
            'foreignKey' => 'municipalities_id'
           )
        );
        
        /**
         * Método constructor de la clase
         * @param boolean $id    [description]
         * @param [type]  $table [description]
         * @param [type]  $ds    [description]
         */
        public function __construct($id = false, $table = null, $ds = null) {
            /**
             * Campo virtual personalizado que contiene la dirección de la imagen de usuario 
             * @var String
             */
            $this->virtualFields['profilePic'] = $this->getProfilePic();

            /**
             * Campo virtual personalizado que contiene el identificador del departamento asociado
             * @var String
             */
            $this->virtualFields['department_id'] = $this->getDepartmentID();

            parent::__construct($id, $table, $ds);
        }


        /**
         * Función que el identificador del departamento asociado
         * @return Int número de likes
         */
        public function getDepartmentID(){

            return 'SELECT dep.id from departments as dep WHERE dep.id = (SELECT mun.department_id from municipalities as mun WHERE mun.id = RequestedUser.municipalities_id)';

        }

        /**
         * Función que obtiene la dirección de la imagen de perfil del usuario
         */
        public function getProfilePic(){
            
            $serverUrl = Router::url('/', true);

            return "SELECT IFNULL((SELECT CONCAT('".$serverUrl.'resourcesFolder'."', '/',CONCAT (RequestedUser.user_folder,'/',resources.stored_file_name))  from resources WHERE resources.resource_types_id =  (SELECT resource_types.id FROM resource_types WHERE  resource_types.name = 'profile_pic') AND resources.users_id = RequestedUser.id),'".$serverUrl."img/avatar/avatar.jpg')";

        }  
        
    }
?>