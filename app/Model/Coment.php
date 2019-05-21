<?php
    class Coment extends AppModel 
    {
        var $name = 'Coments';


        /**
         * Relación con Usuarios
         * @var array
         */
        public $belongsTo = array(
			'User' => array(
			'className' => 'User',
			'foreignKey' => 'users_id'
		)

		);


	/**
	 * Relación con PostComemnt con opcion de ordenamiento
	 * @var array
	 */
	public $hasMany = array(
        'PostComment' => array(
            'className' => 'PostComment',
           	'foreignKey'   => 'comment_id'
        ),
        'Resource' => array(
            'className' => 'ResourceManager.Resource',
           	'foreignKey'   => 'entity_id',
        ),
    );


	/**
	 * Método constructor de la clase
	 * @param boolean $id    [description]
	 * @param [type]  $table [description]
	 * @param [type]  $ds    [description]
	 */
	public function __construct($id = false, $table = null, $ds = null) {
		/**
		 * Campo virtual personalizado que contiene la ruta del archivo 
		 * @var String
		 */

	    $this->virtualFields['username'] = $this->getUserName();

            /**
             * Campo virtual personalizado que contiene la dirección de la imagen de usuario 
             * @var String
             */
        $this->virtualFields['profilePic'] = $this->getProfilePic();


	    parent::__construct($id, $table, $ds);
	}


	/**
	 * Función que retorna el nombre del archivo original con su extensión
	 * @return String nombre
	 */
	public function getUserName(){

		return 'SELECT users.username FROM users WHERE users.id = Coment.users_id';

	}

        /**
         * Función que obtiene la dirección de la imagen de perfil del usuario
         */
        public function getProfilePic(){
            
            $serverUrl = Router::url('/', true);

            return "SELECT IFNULL((SELECT CONCAT('".$serverUrl.'resourcesFolder'."', '/',CONCAT (user.user_folder,'/',resources.stored_file_name))  from users as user, resources WHERE resources.resource_types_id =  (SELECT resource_types.id FROM resource_types WHERE  resource_types.name = 'profile_pic') AND resources.users_id = Coment.users_id and resources.users_id = user.id),'".$serverUrl."img/avatar/avatar.jpg')";

        }  

    
    }







