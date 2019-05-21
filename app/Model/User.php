<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');


    class User extends AppModel 
    {
        //var $name = 'Users';


        var $hasOne = array('Connection');
        

        

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

        public $validate = array(
  
            'email'=>array(
                'Valid email'=>array(
                    'rule'=>array('email'),
                    'message'=>'Por favor ingrese un e-mail válido. ***'
                ),
                'That email has already been used'=>array(
                    'rule'=>'isUnique',
                    'message'=>'El e-mail dado ya está actualmente en uso. ***'
                )
            ),
            
            
            'password' => array(
                array(
                    'rule' => 'notBlank',
                    'required' => true,
                    'message' => 'Por Favor ingrese una contraseña'
                    ),
                array(                              
                    'rule' => array('minLength', 8),
                   'message' => 'La contraseña debe contener minimo 8 caracteres',
                   )
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
             * Campo virtual personalizado que contiene la dirección de la imagen de usuario 
             * @var String
             */
            $this->virtualFields['profilePic'] = $this->getProfilePic();

            /**
             * Campo virtual personalizado que contiene el identificador del departamento asociado
             * @var String
             */
            $this->virtualFields['department_id'] = $this->getDepartmentID();

            /**
             * Campo virtual personalizado que contiene el identificador del departamento asociado
             * @var String
             */
            $this->virtualFields['location'] = $this->getLocationString();


            parent::__construct($id, $table, $ds);
        }


        public function getLocationString(){

            return "CONCAT( (SELECT mun.municipality from municipalities as mun WHERE mun.id =  User.municipalities_id), ', ',  (SELECT dep.name from departments as dep WHERE dep.id = (SELECT mun.department_id from municipalities as mun WHERE mun.id =  User.municipalities_id)))";            

        }


        /**
         * Función que el identificador del departamento asociado
         * @return Int número de likes
         */
        public function getDepartmentID(){

            return 'SELECT dep.id from departments as dep WHERE dep.id = (SELECT mun.department_id from municipalities as mun WHERE mun.id =  User.municipalities_id)';

        }

        /**
         * Función que obtiene la dirección de la imagen de perfil del usuario
         */
        public function getProfilePic(){
            
            $serverUrl = Router::url('/', true);

            return "SELECT IFNULL((SELECT CONCAT('".$serverUrl.'resourcesFolder'."', '/',CONCAT (User.user_folder,'/',resources.stored_file_name))  from resources WHERE resources.resource_types_id =  (SELECT resource_types.id FROM resource_types WHERE  resource_types.name = 'profile_pic') AND resources.users_id = User.id),'".$serverUrl."img/avatar/avatar.jpg')";

        }  


        /**
         * Función que valida si una contraseña proporcionada es igual a su hash
         * @param  [type] $passToHash     [description]
         * @param  [type] $hashedPassword [description]
         * @return [type]                 [description]
         */
        public function checkPassword($passToHash = null, $hashedPassword = null){


            $passwordHasher = new BlowfishPasswordHasher();
                         
            return $passwordHasher->check($passToHash,$hashedPassword);

                
        }



        public function beforeSave($options = array()) {
   
            if (!empty($this->data[$this->alias]['password'])) {
                $passwordHasher = new BlowfishPasswordHasher();
                $this->data[$this->alias]['password'] = $passwordHasher->hash(
                    $this->data[$this->alias]['password']
                );
            }
   
            return parent::beforeSave($options);
        }
        
    }
?>