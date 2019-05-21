<?php
    class Organization extends AppModel 
    {
        var $name = 'Organizations';
        
        // var $hasMany = array();


        public $belongsTo = array(
            'Municipality' => array(
                'className' => 'Municipality',
                'foreignKey' => 'municipality_id'
               ),

            /**
             * Relación que define el creador de la entidad
             */
            'User' => array(
                'className' => 'User',
                'foreignKey' => 'user_id'
               ),



        );
        
        public $validate = array(
            
            'name' => array(
                'required' => array(
                    'rule' => array('notBlank'),
                    'message' => 'Por favor ingrese el nombre de la entidad'
                )
            ),
            
            'nit' => array(
                'alphaNumeric' => array(
                    'rule' => 'alphaNumeric',
                    'required' => true,
                    'message' => 'Solo está permitido ingresar letras y números. Por favor escriba el Nit y digito de verificación, sin puntos ni guiones.'
                    ),
                'That username has already been taken'=>array(
                    'rule'=>'isUnique',
                    'message' => 'El Nit actualmente se encuentra registrado. Contacte al administrador del sistema si es usted el representante de su entidad.'
                    )
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
             * Campo virtual personalizado que contiene el identificador del departamento asociado
             * @var String
             */
            $this->virtualFields['department_id'] = $this->getDepartmentID();
    
            
            $userId = 421;

            if (CakeSession::read("Auth.User.id") != null) {
                
                $userId = CakeSession::read("Auth.User.id");
            
            }            
            
            /**
             * Campo virtual personalizado que contiene el estado de si el usuario actual ha hecho una peticion a un grupo
             * @var String
             */
            $this->virtualFields['current_user_request'] = $this->currentUserRequest($userId);


            parent::__construct($id, $table, $ds);
        }
        

        public function currentUserRequest($userId){

            return 'SELECT IFNULL((SELECT orgr.id FROM organization_requests AS orgr WHERE orgr.organizations_id = Organization.id AND orgr.users_id = '.$userId.'),0) as current_user_request';
        }

        /**
         * Función que el identificador del departamento asociado
         * @return Int número de likes
         */
        public function getDepartmentID(){

            return 'SELECT dep.id from departments as dep WHERE dep.id = (SELECT mun.department_id from municipalities as mun WHERE mun.id =  Organization.municipality_id)';

        }


    }
?>