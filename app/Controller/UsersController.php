<?php

// http://redkastella.com/index.php/politicas-de-privacidad-y-uso-de-informacion/

    App::uses('AppController', 'Controller');

    App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
   
    class UsersController extends AppController
    {
        
        public $name = 'Users';

        // Declaracion de los componentes para inicio de sesion
        public $components = array(
                                // 'RequestHandler',
                                // 'Paginator',
                                'ResourceManager.ResourceManager',
                                'SimpleEmail'); 

        var $uses = array('User', 'Chapter', 'Organization', 'Department', 'Municipality', 'Publication', 'Comment', 'Privacy', 'OrganizationUser', 'Connection');
        
        // Declaracion de helpers que ayudaran en la creacion de formularios
        public $helpers = array ('Html', 'Form', 'Time', 'Js','Session'); 
        
        public $paginate = array (  'limit' => 10,
                                    'order' => array ('User.surname' => 'DESC' ) );
        
        public function beforeFilter() {
            parent::beforeFilter();

            // Allow users to register and logout.
            $this->Auth->allow('index','add', 'logout', 'termsConditions', 'privacyPolitics','getUserInfo','loginRest','registerRest','alreadyLoggedUser','forgotPassword','forgotPasswordAjax','getUserById','getUserInfoPublic');
            
            // Diferente Layout
            $this->response->header('Access-Control-Allow-Origin','*');
            $this->response->header('Access-Control-Allow-Methods','*');
            $this->response->header('Access-Control-Allow-Headers','X-Requested-With');
            $this->response->header('Access-Control-Allow-Headers','Content-Type, x-xsrf-token');
            $this->response->header('Access-Control-Max-Age','172800');

            

        }
        
        
        /************************************** LOGIN **************************************/
        public function login() {
            
            $this->layout = 'login';
            
            //if already logged-in, redirect
            if($this->Session->check('Auth.User'))
            {
                $this->Session->setFlash('Bienvenido, '. $this->Auth->user('username'));
                //$this->redirect(array('action' => 'index'));
                $this->redirect($this->Auth->redirect(array('controller'=>'Publications', 'action' => 'allPublications')));
            }else{

            
               // $this->redirect("http://redkastella.com/");

            }
            
            // Validacion de ingreso a traves de metodo post (mediante click al boton)
            if ($this->request->is('post')) 
            {
                if ($this->Auth->login()) 
                { 
                    $this->Session->setFlash('Bienvenido, '. $this->Auth->user('username'));
                    
                    // Almacenamiento de variables de sesion
                    $this->Session->write('User.username', $this->Auth->user('username'));
                    $this->Session->write('User.id', $this->Auth->user('id'));
                    $this->Session->write('User.avatar', $this->Auth->user('Person.path_avatar'));
                    $this->Session->write('User.rol_id', $this->Auth->user('role_id'));
                    $this->Session->write('User.color', $this->Auth->user('Person.color'));
                    $this->Session->write('User.org_id', $this->Auth->user('Person.organization_id'));
                    

                    $skinData = $this->requestAction(array('controller'=>'UserPreferences', 'action'=>'getUserPreferences'));


                    /**
                     * asignacion de valor del tema del usuario
                     */
                    $this->Session->write('Auth.User.skin', $skinData);

                    //Se buscan otras conexiones
                    $conexion_anterior = $this->Connection->find('first', 
                                                                 array('conditions'=>
                                                                       array('Connection.user_id' => $this->Auth->user('id') ),
                                                                       'fields'=>'id'
                                                                      )
                                                                );
                    //elimina conexiones previas
                    if (!is_null($conexion_anterior))
                    {
                        $this->Connection->id = $conexion_anterior['Connection']['id'];
                        $this->Connection->delete();
                    }
                    
                    //Se registra la conexion del usuario
                    $conexion = array ('user_id' => $this->Auth->user('id'));
                    $this->Connection->save($conexion);
                    $this->Session->write('User.conexion_id', $this->Connection->id);
                    
                    // Redireccionamiento a la vista index
                    //$this->redirect($this->Auth->redirect(array('action' => 'index')));
                        


                    // $loginData = $this->request->data;
                    // $loginData = $loginData['User'];
                    

                    // $data = array(
                    //     'username' => $loginData['username'],
                    //     'password' => $loginData['password']
                    // );


                    // $url = 'http://192.168.1.50:3000/authenticate';

                    // $options = array(
                    //     'http' => array(
                    //         'header'  => "Content-type: application/x-www-form-urlencoded",
                    //         'method'  => 'POST',
                    //         'content' => http_build_query($data),
                    //     ),
                    // );
                    

                    // $context  = stream_context_create($options);

                    // $result = json_decode(file_get_contents($url, false, $context));

                    // if ($result->success == 1) {
    
                        
                    //     // Almacenamiento del token en una variable de sesión sobre el usuario
                    //     $this->Session->write('Auth.User.sessionToken', $result->token);


                    //     // $this->redirect($this->Auth->redirect(array('controller'=>'Publications', 'action' => 'allPublications')));
                    //     // echo $this->Auth->user('sessionToken');
                    // }
                    

                   $this->redirect($this->Auth->redirect(array('controller'=>'Publications', 'action' => 'allPublications')));
                    

                }
                else 
                {
                    $this->Session->setFlash('Nombre de Usuario y/o Contraseña inválidos.');
                    $this->redirect(array('action' => 'login')); 
                }
            }
        }



        public function alreadyLoggedUser(){

            $this->autoRender = false;
            
            //if already logged-in
            if($this->Session->check('Auth.User'))
            {
                
                  echo json_encode(Array('success'=>true)); 

            }else{

                  echo json_encode(Array('success'=>false)); 

            }



        }



 // Validacion de ingreso a traves de metodo post (mediante click al boton)
        

        /************************************** LOGIN **************************************/
        public function loginRest() {
            
            $this->autoRender = false;
            
            //if already logged-in
            // if($this->Session->check('Auth.User'))
            // {
                // echo json_encode(Array('success'=>true)); 

            // }
                
                // Validacion de ingreso a traves de metodo post (mediante click al boton)
                if ($this->request->is('post')){


                    if ($this->Auth->login()){

                        $this->Session->setFlash('Bienvenido, '. $this->Auth->user('username'));
                        
                        // Almacenamiento de variables de sesion
                        $this->Session->write('User.username', $this->Auth->user('username'));
                        $this->Session->write('User.id', $this->Auth->user('id'));
                        $this->Session->write('User.avatar', $this->Auth->user('Person.path_avatar'));
                        $this->Session->write('User.rol_id', $this->Auth->user('role_id'));
                        $this->Session->write('User.color', $this->Auth->user('Person.color'));
                        $this->Session->write('User.org_id', $this->Auth->user('Person.organization_id'));
                        
                        //Se buscan otras conexiones
                        $conexion_anterior = $this->Connection->find('first', 
                                                                        array('conditions'=>
                                                                           array('Connection.user_id' => $this->Auth->user('id') ),
                                                                           'fields'=>'id'
                                                                          )
                                                                    ); 


                        /**
                         * Julián Andrés Muñoz Cardozo
                         * 2016-09-01 16:17:51
                         * agregado de nueva novedad si el usuario recien es registrado y ha escrito en la parte publica
                         */
                        if(!empty($this->request->data['newPublicationTitle'])){

                            $newPublicationTitle = $this->request->data['newPublicationTitle'];         
                
                            $newPublicationContent = $this->request->data['newPublicationContent'];  

                            $newPublicationPrivacies = $this->request->data['newPublicationPrivacies']; 


                            /**
                             * Guardado del nuevo post
                             * @var [type]
                             */
                            $saveNewPost = $this->requestAction(array(
                                    'controller'=>'Publications', 
                                    'action'    =>'addPost',
                                    $newPublicationTitle, $newPublicationContent,$newPublicationPrivacies
                                )
                            );


                        }


                        /**
                         * Successful login
                         */
                        echo json_encode(Array('success'=>true)); 

                    }else {   

                        /**
                         * Login Error
                         */
                        echo json_encode(Array(
                            'success'=>false,
                            'message'=>'Nombre de Usuario y/o Contraseña inválidos.',

                            )); 

                    }

              }
          
        }


        




        /**
         * Función que retorna el token de sessión
         * @return JsonString String con el token
         */
        public function getSessionToken(){

            $this->autoRender = false;
        
            echo json_encode(Array('token' => $this->Auth->user('sessionToken')));
        
        }

        /************************************** LOGOUT **************************************/
        // Funcion encargada de cerrar sesion al seleccionar la funcion Cerrar Sesion
        public function logout() 
        {

            // $this->Connection->id = $this->Session->read('User.conexion_id');
            // $this->Connection->delete();
            // $this->Session->destroy();
            
            $this->Auth->logout();
        
            $this->redirect("/");
        }
        
        /************************************** INDEX **************************************/
        // Funcion encargada de validar y autenticar el ingreso de usuarios
        // al escritorio de la aplicacion 
        public function index() 
        {
            
            
            
            if ($this->request->is('post')) 
            {
                //$this->Session->setFlash('Bienvenido, '. $this->Session->read('User.name').' '.$this->Session->read('User.lastName'));

                /*$this->paginate = array(
                    'limit' => 6,
                    'order' => array('User.username' => 'asc' )
                );

                $users = $this->paginate('User');
                $this->set(compact('users'));*/
            }
            else
            {
                if ($this->Auth->login()) 
                {
                    $this->Session->setFlash('Bienvenido, '. $this->Session->read('User.username').' '.$this->Session->read('User.lastName'));

                    /*$this->paginate = array(
                        'limit' => 6,
                        'order' => array('User.username' => 'asc' )
                    );

                    $users = $this->paginate('User');
                    $this->set(compact('users'));*/
                }
                else
                {
                    $this->Session->setFlash('Debe ingresar con su Nombre de Usuario y Contraseña.');
                    $this->redirect(array('action' => 'login')); 
                }
            }
        }

        // /*************************************** ADD ***************************************/
        // public function add() {
        //     // Registro de nuevo usuario
        //     if ($this->request->is('post')) 
        //     {
        //         $datos = array(
        //                         'User' => array('username'   => $this->request->data['User']['username'],
        //                                         'password'   => $this->request->data['User']['password'],
        //                                         'pwd_confirmation' => $this->request->data['User']['pwd_confirmation'],
        //                                         'name'       => $this->request->data['User']['name'],
        //                                         'surname'    => $this->request->data['User']['surname'],
        //                                         'email'      => $this->request->data['User']['email']),
        //                         'Person'=>array('username' => $this->request->data['User']['username'],
        //                                         'eslogan' => '',
        //                                         'city' => '',
        //                                         'state' => '',
        //                                         'occupation' => '',
        //                                         'path_avatar' => "avatar/avatar.jpg",
        //                                         'organization_id' => 1, 
        //                                         'role_id' => 2,
        //                                         'color' => 'orange')
        //         );
                
        //         //if (!empty($this->request->data)) {
        //         if ($this->User->saveAll($datos))
        //         {
        //             $this->Session->setFlash(__('El usuario se ha registrado satisfactoriamente.'));
                    
        //             // Con el registro del usuario, se crea automaticamente un perfil
        //             //$this->User->saveAssociated($datos);    
        //             //$this->User->saveAll($datos); 
                    
        //             $datos_login = array('username'  => $this->request->data['User']['username'],
        //                                 'password'  => $this->request->data['User']['password']);
        //             $this->Auth->login($datos_login);
        //             $this->Session->write('User.username', $this->Auth->user('username'));
                    
        //             $id = $this->User->find('first',  
        //                     Array('fields' => 'User.id',
        //                                       'conditions' => Array('User.username'=>$this->request->data['User']['username']),
        //                                       'recursive' => -1
        //                 )
        //             );
                    
        //             $this->Session->write('User.id', $id['User']['id']);
        //             $this->Session->write('User.avatar', "avatar/avatar.jpg");
        //             $this->Session->write('User.rol_id', 2);
        //             $this->Session->write('User.color', $this->Auth->user('Person.color'));
        //             $this->Session->write('User.org_id', $this->Auth->user('Person.organization_id'));

        //             //Se registra la conexion del usuario
        //             $conexion = array ('user_id' => $this->Auth->user('id'));
        //             $this->Connection->save($conexion);

        //             $this->Session->write('User.conexion_id', $this->Connection->id);

        //             $this->redirect(array('controller'=>'Users', 'action' => 'index'));
               
        //         }else {
                    
        //             $this->Session->setFlash(__('El usuario no pudo ser registrado. Por favor, intente nuevamente.'));

        //             $this->set('registro_previo',$datos);
                
        //         }
        //     }
        // }


        /*************************************** ADD ***************************************/
        public function add() {


            // Registro de nuevo usuario
            if ($this->request->is('post')) 
            {   

                $this->User->create(); 

                /**
                 * Obtenemos los datos que vienen por post
                 * @var Array
                 */
                $newUser = $this->request->data;

                /**
                 * Rol del usuario registrado por primera vez
                 */
                $newUser['User']['role_id'] = 2;

                if ($this->User->save($newUser['User']))
                {

                    //configuración de un directorio para el usuario 
                    $this->ResourceManager->getUserFolder($this->User->id);

                    //$this->Session->setFlash(__('El usuario se ha registrado satisfactoriamente.'));
                    
                    
                    // $datos_login = array('username'  => $newUser['username'],
                    //                     'password'  => $newUser['password']);
                    // $this->Auth->login($datos_login);
                    
                    // $this->Session->write('User.username', $this->Auth->user('username'));
                    
                    // $id = $this->User->find('first',  
                    //         Array('fields' => 'User.id',
                    //                           'conditions' => Array('User.username'=>$newUser['username']),
                    //                           'recursive' => -1
                    //     )
                    // );
                    
                    // $this->Session->write('User.id', $id['User']['id']);
                    // $this->Session->write('User.avatar', "avatar/avatar.jpg");
                    // $this->Session->write('User.rol_id', 2);
                    // $this->Session->write('User.color', $this->Auth->user('Person.color'));
                    // $this->Session->write('User.org_id', $this->Auth->user('Person.organization_id'));

                    // //Se registra la conexion del usuario
                    // $conexion = array ('user_id' => $this->Auth->user('id'));
                    // $this->Connection->save($conexion);

                    // $this->Session->write('User.conexion_id', $this->Connection->id);

                    $this->redirect(array('controller'=>'Users', 'action' => 'login'));
               
                }else {

                    $this->Session->setFlash(__('El usuario no pudo ser registrado. Por favor, intente nuevamente.'));

                    $this->set('registro_previo',$newUser);
                
                }
            }
        }

    /*************************************** ADD ***************************************/
        public function registerRest() {

            $this->autoRender = false;


            // Registro de nuevo usuario
            if ($this->request->is('post'))
            {   

                $this->User->create(); 

                /**
                 * Obtenemos los datos que vienen por post
                 * @var Array
                 */
                $newUser = $this->request->data;

                /**
                 * Rol del usuario registrado por primera vez
                 */
                $newUser['User']['role_id'] = 2;

                if ($this->User->save($newUser['User']))
                {

                    //configuración de un directorio inicial de archivos para el usuario 
                    $this->ResourceManager->getUserFolder($this->User->id);
                    
                    $this->SimpleEmail->contactMail("

                        Te damos la bienvenida a la Red Kastella.
                        \n\n
                        
                        Kastella es la primera Red Social de Contratación, ahora podrás conectarte con entidades, proveedores y contratistas, además de encontrar oportunidades de negocio y mejorar tu competitividad y la de tu empresa. 
                        \n\n
                        
                        Podrás compartir historias, noticias e información, ayudando a mejorar los procesos de contratación y promoviendo el desarrollo económico. 
                        \n\n
                        
                        Nos vemos en www.redksatella.com", $newUser['User']['username'], 'RedKastella' ,'Te damos la bienvenida a la Red Kastella.');
                
                        echo json_encode(Array('success'=>true,'message'=>'El usuario se ha registrado satisfactoriamente.'));
                    
        
                }else {

                        echo json_encode(Array('success'=>false,'message'=>'El usuario no pudo ser registrado. Por favor, intente nuevamente.','errors'=>$this->User->validationErrors));
                
                }
            }
        }



        /************************************** EDIT **************************************/
        public function edit() {

            $id = $this->Auth->user('id');


            if ($this->request->is('get')) {
               
                $user = $this->User->find('first',Array('conditions'=>Array('User.id'=> $id)));


                $this->request->data = $user;

            }



            if ($this->request->is(array('post', 'put'))) {


                $userInfo = $this->request->data['User'];
                

                $this->User->validator()->remove('password');

                $user = $this->User->find('first',Array('conditions'=>Array('User.id'=> $id)));

                    
                $this->User->id = $id;


                if($this->User->save(Array('User'=>$userInfo))){


                }

               
               
                $user = $this->User->find('first',Array('conditions'=>Array('User.id'=> $id)));
            
             
            }



                $this->set('user',$user);

        

            
        }
            

        public function changeProfilePic(){

            $this->autoRender = false;

            $id = $this->Auth->user('id');
            
            if (count($_FILES)) {
            
                    $profilePic = $this->ResourceManager->getResources($id, 'user', "profile_pic", $id);
                    
                    /**
                     * Si no hay foto de perfil
                     */
                    if (!$profilePic) {
                        
                         $this->ResourceManager->saveResources($id, 'user', 'profile_pic', $id);

                    }else{  

                        /**
                         * eliminamos el recurso de foto de perfil actual
                         */
                        $this->ResourceManager->deleteResourceById($profilePic[0]['id'],$id);

                        /**
                         * Subimos e insertamos el nuevo recurso de foto de perfil
                         */
                        $this->ResourceManager->saveResources($id, 'user', 'profile_pic', $id);

                        /**
                         * Obtenemos nuevamente los datos del recurso
                         * @var Array
                         */
                        $profilePic = $this->ResourceManager->getResources($id, 'user', "profile_pic", $id);
                        
                        /**
                         * Cambiamos la imagen en la sesion actual
                         */
                        $this->Session->write('Auth.User.profilePic', $profilePic[0]['file']);

                        echo json_encode($profilePic);
                        

                }
     

            }

        }

        /**
         * Funcion para el proceso de cambio de contraseña
         * @return JsonString 
         */
        public function change_pwd(){

            $this->autoRender = false;
            
            /**
             * Datos necesarios para el cambio de contraseña
             * @var Array
             */
            $newPasswordData = $this->request->data['newPasswordData'];

            /**
             * Variable que contiene la contraseña actual
             * @var String
             */
            $actualPassword = $newPasswordData['actualPassword'];
            
            /**
             * Nueva contraseña
             * @var String
             */
            $newPassword = $newPasswordData['newPassword'];
            
            /**
             * Confirmación de nueva contraseña
             * @var String 
             */
            $newPasswordConf = $newPasswordData['newPasswordConf'];


            /**
             * Indentificador del usuario
             * @var Int
             */
            $id = $this->Auth->user('id');
            
            /**
             * Información del usuario Actual
             * @var Array
             */
            $user = $this->User->find('first',Array('conditions' => Array('User.id' => $id)));

            /**
             * control del estado
             * @var boolean
             */
            $status = true;

            /**
             * Mensaje a retornar
             * @var string
             */
            $message = "";

            /**
             * Si el nuevo password y la confirmación son iguales
             */
            if ( $newPassword == $newPasswordConf )
            {

                /**
                 * Si el password actual corresponde con el del usuario
                 */
                if ($this->User->checkPassword($actualPassword,$user['User']['password'])) {
                    
                    $this->User->id = $id;

                    // $this->User->password = $newPassword;

                    
                    /**
                     * Validamos el campo de password si es mayor a 8 caracteres y no esta vacio
                     */
                    if(strlen($newPassword) >= 8 && $newPassword != null){

                        /**
                         * Guardamos el nuevo password
                         */
                        if($this->User->saveField("password", $newPassword)){
                            
                            $message = "Cambio de contraseña éxitoso";
                        
                        }else{

                            $message = "Hubo un error guardando a nueva contraseña";
                            $status = false;
                        }                    
                    
                    } else {


                       $status = false; 
                       $message = 'La contraseña debe contener minimo 8 caracteres';

                    }                    

                }else{  

                    $message = "La contraseña actual no coincide";
                    $status = false;
                }

             }else{

                $message = "Las contraseñas no coinciden";
                $status = false;
            }


            echo json_encode(Array('success'=>$status,'message'=> $message));
        }
        
        function change_picture(){

            //$this->request->onlyAllow('ajax'); // No direct access via browser URL - Note for Cake2.5: allowMethod()
            $this->layout = 'ajax';
            
            $html='';
                            
            if($_FILES['archivo']['size'] > 0)
            {
                //la imagen debe guardarse en la carpeta imagenes
                
                //$_FILES['archivo']['name'] = preg_replace('/\&(.)[^;]*;/', '\\1', $_FILES['archivo']['name']);
                $conservar = '0-9a-z'; // juego de caracteres a conservar

                // Turn down for what!!
                $regex = sprintf('~[^%s]++~i', $conservar); // case insensitive
                
                $nombre_exp = explode(".",$_FILES['archivo']['name']);
                
                $nombrearchivo = preg_replace($regex, '', $nombre_exp[0]).'.'.$nombre_exp[1];
                
                $nombrearchivo = "img/avatar/".$nombrearchivo;
                
                // inicia modificacion para la nube
                $nombrearchivo = 'app/webroot/'.$nombrearchivo;
                // finaliza modificacion para la nube
                
                // Informacion del tipo de archivo subido $this->data['Upload']['archivo']['type']
                if (move_uploaded_file($_FILES['archivo']['tmp_name'],$nombrearchivo)) 
                {
                    $html = $html.'avatar/'.preg_replace($regex, '', $nombre_exp[0]).'.'.$nombre_exp[1];
                    //$this->Session->write('User.avatar', $html);
                }
            }
                
            echo $html;
        }

        public function delete($id = null) {
            // Prior to 2.5 use
            // $this->request->onlyAllow('post');

            $this->request->allowMethod('post');

            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException(__('Usuario inválido o no encontrado'));
            }
            if ($this->User->delete()) {
                $this->Session->setFlash(__('Usuario eliminado'));
                return $this->redirect(array('action' => 'all'));
            }
            $this->Session->setFlash(__('El usuario no fue eliminado'));
            return $this->redirect(array('action' => 'all'));
        }
        
        /************************************** SEARCH **************************************/
        public function search() {
            
            
            
            $list_state = $this->Department->find('all');
            foreach ($list_state as $item) {
                $resultados[$item['Department']['id']]= $item['Department']['name'];
            }
            
            $this->set('list_state',$resultados);
            
            //ojo con los campos de busqueda de nombre, apellido, email, no tienen porque ser obligatorios
            //se ponen obligatorios si se usa como nombre el mismo de la tabla, asi que los variamos con el sufijo -search
            
        }

        function get_municipalities(){
            //$this->request->onlyAllow('ajax'); // No direct access via browser URL - Note for Cake2.5: allowMethod()
            $this->layout = 'ajax';
            
            $id_category = $_POST['id_category'];
            
            $ciudades=$this->Municipality->find(
                'all',
                array('conditions'=>
                        array('department_id'=>$id_category),
                      'fields'=>array('id','municipality')));
            
            
          /*if(count($ciudades) > 0){
               $response=array('success'=>true,'ciudades'=>$ciudades);
                }else{
                $response=array('success'=>false);
                }
           echo json_encode($response);*/
            //if (count($ciudades) > 0) {
                //while ($row = $result->fetch_assoc()) {   
                $html='';
                foreach ($ciudades as $ciudad)
                {
                    $html .= '<option value="'.$ciudad['Municipality']['id'].'">'.$ciudad['Municipality']['municipality'].'</option>';
                }
                
            //}
            echo $html;
        }
            

        /**
         * Funcionalidad de invitar usuarios a una entidad
         * @return Array
         */
        public function inviteUsers(){

            /**
             * No renderizamos vista
             * @var boolean
             */
            $this->autoRender = false;

            /**
             * Identificador del usuairo logueado
             * @var Int
             */
            $userId = $this->Auth->user('id');

            /**
             * Estado de éxito
             * @var boolean
             */
            $state = true;

            /**
             * Indentificadores de los los usuarios encontrados por email
             * @var Array
             */
            $emailsIds = $this->request->data['emailsIds'];

            /**
             * Variable que contiene la organizacion creada por un usuario
             * @var Array
             */
            $userOrganization = $this->userOwnOrganization($userId);

            /**
             * Identificador de la organización
             * @var Int
             */
            $organizationId = $userOrganization['Organization']['id'];

            /**
             * Recorremos los indentificadores de los usuarios obtenidos
             */
            foreach ($emailsIds as $id) {
               
               /**
                * Si el usuario no se encuentra en la organización
                */
               if (!$this->isUserInOrganization($id,$organizationId)) {
                    
                    /**
                     * Creación de nueva relación de entidad con usuario
                     */
                    $this->OrganizationUser->create();

                    /**
                     * Si no se guardo correctamente
                     */
                    if(! $this->OrganizationUser->save(Array('user_id'=> $id, 'organization_id' => $organizationId))){
                    
                        $state = false;

                        break;
                    }

                    /**
                     * limpiamos el modelo
                     */
                    $this->OrganizationUser->Clear();
    
               }

            }

            /**
             * Mandamos el resultado de la acción
             */
            echo json_encode(Array('success'=>$state));

        }
            
        /**
         * Función que busca si un usuario esta en una organización
         * @return boolean true: esta, false: no
         */
        public function isUserInOrganization($userId = null, $organizationId = null){

            $organization = $this->OrganizationUser->find('first',array(
                                        'conditions' => array(
                                                'OrganizationUser.organization_id' => $organizationId, 
                                                'OrganizationUser.user_id'=>$userId
                                        )
                            ));

            return $organization;

        }
        
        /**
         * Función que administra la creaciñon de entidades y miembros de entidad
         */
        public function myOrganization() {
                

            $userId = $this->Auth->user('id');
            
            /**
             * Variable que contiene la organizacion creada por un usuario
             * @var Array
             */
            $userOrganization = $this->userOwnOrganization($userId);

            /**
             * Se manda la variable anterior a la vista
             */
            $this->set('userOrganization',$userOrganization);

            /**
             * Si hay datos para guardar
             */
            if ($this->request->is('post')) 
            {       

                /**
                 * Obtenemos los datos de la organización
                 * @var Array
                 */
                $organization = $this->request->data('Organization');
                
                /**
                 * Configuramos el identificador del usuario que crea la entidad
                 */
                $organization['user_id'] = $userId;

                /**
                 * Si se guardo la entidad con éxito
                 */
                if ($this->Organization->save($organization))
                {   

                        
                    /**
                     * Identificador de la organizacón
                     * @var Int
                     */
                    $organizationId = $this->Organization->id;

                    
                    /**
                     * Creación de nueva relación de entidad con usuario
                     */
                    $this->OrganizationUser->create();

                    if($this->OrganizationUser->save(Array('user_id'=> $userId, 'organization_id' => $organizationId))){
                        /**
                         * Mensaje de información
                         */
                         $this->Session->setFlash(__('La entidad ha sido creada.'));
                        
                        /**
                         * Redirigimos a la misma acción
                         */
                        $this->redirect(array('controller'=>'Users', 'action' => 'myOrganization'));

                    }else{

    
                        /**
                         * Mensaje de información de fallo de relación del usuario con entidad
                         */
                        $this->Session->setFlash(
                            __('La relación del usuario con la entidad no se pudo establecer, por favor contacte al administrador del sistema.')
                        );                        
                    }


                         
                }else{

                    /**
                     * Mensaje de información de fallo
                     */
                    $this->Session->setFlash(
                        __('La entidad no fue creada, si continua teniendo este problema, por favor contacte al administrador del sistema.')
                    );
                }
            }


            /**
             * Si el usuario ha creado una Organización
             */
            if ($userOrganization) {
                

             /**
              * Identificador de la organización
              * @var [type]
              */
            $organizationId = $userOrganization['Organization']['id'];

            
              /**
               * Configuración de la consulta de Los usuarios de la organizacion
               * @var Array
               */
              $this->Paginator->settings = Array(
                            'order'       =>  'User.id DESC',
                            'limit'       =>  10, 
                            'conditions'  => Array('Organization.id'=> $organizationId),
                            'recursive'   => 2
              );

              /**
               * Asignamos el resultado de la paginación
               */ 
              $this->set('organizationUsers',$this->paginate($this->OrganizationUser));            
                    
            }


	    }


        /**
         * Función que retorna los resultados de la búsqueda global : personas y grupos 
         */
        public function globalSearch(){

            $this->autoRender = false;

            /**
             * Criterio de búsqueda
             * @var String
             */
            $query = $this->request->data['query'];

            /**
             * Resultados
             * @var Array
             */
            $users = $this->User->find('all', Array(
                    
                    'recursive'=> 3,

                    'limit'=> 4, 
            
                    // 'fields' => array('User.id','User.username', 'User.email','User.name','User.surname','User.profilePic'),
            
                    'conditions' => array( ' CONCAT(User.name, " ", User.surname) LIKE'=> '%'.$query.'%' )

                )
            );

            /**
             * Lista de usuarios a retornar
             * @var Array
             */
            $usersList = Array();

            /**
             * Formato a devolver
             */
            foreach ($users as $user) {
                
                $usersList[] = Array(

                    'username' => $user['User']['username'],            
                    'name' => $user['User']['name'],
                    'surname' => $user['User']['surname'],
                    'profilePic' => $user['User']['profilePic'],
                    'municipality' => $user['Municipality']['municipality'],
                    'department' => $user['Municipality']['Department']['name']    
                );
                

            }

            


            /**
             * Búsqueda de organizaciones
             */


            $this->loadModel('Organization');

            /**
             * Resultados
             * @var Array
             */
            $organizations = $this->Organization->find('all', Array(
                    
                    'recursive' =>  2,

                    'limit' =>  4, 
            
                    // 'fields' => array('Organization.id','Organization.nit', 'Organization.name'),
            
                    'conditions' => array( 'Organization.name LIKE' => '%'.$query.'%' )

                )
            );
   
            /**
             * Lista de usuarios a retornar
             * @var Array
             */
            $organizationsList = Array();

            /**
             * Formato a devolver
             */
            foreach ($organizations as $organization) {
                
                $organizationsList[] = Array(
                        'nit' => $organization['Organization']['nit'],
                        'name' => $organization['Organization']['name'],
                        'slogan' => $organization['Organization']['slogan'], 
                        'pic' => $organization['User']['profilePic'],
                        'municipality' => $organization['Municipality']['municipality'],
                        'department' => $organization['Municipality']['Department']['name']
                );                    
            }

            /**
             * Impresión de resultados
             */
            echo json_encode( Array('users' => $usersList, 'organizations' => $organizationsList ));

        }







      
        /**
         * Función que retorna si un usuario es el creador/propietario de una organización
         * @return Array Organización
         */
        public function userOwnOrganization($userId = null){

             
            $userOrganization = $this->Organization->find('first',array('conditions'=>array('Organization.user_id'=>$userId)));

            return $userOrganization;

        }
        
        // public function invite () {
        //     $this->layout='ajax';
            
        //     $user_id=$this->Session->read('User.id');
            
        //     // Encuentra el id de la entidad, con base al user_id
        //     $organization_id = $this->Organization->find ('first',
        //                                           array ( 'conditions'=>
        //                                                 array('Organization.adminid' => $user_id),
        //                                                  'recursive'=>-1
        //                                                ));
        //     $organization_id = $organization_id['Organization']['id'];
            
        //     //$correos = $_POST['correos'];
        //     $correos = $this->request->data['Invitation']['Emails'];;
        //     $correos = str_replace(' ', '', $correos);
        //     $correos = explode(',',$correos);
            
        //     $i=0;
        //     $no_encontrados='';
            
        //     foreach ($correos as $correo)
        //     {
        //         $nuevo_miembro = $this->User->find('first',
        //                                            array ( 'conditions'=>
        //                                                 array('User.email' => $correo),
        //                                                  'recursive'=>0
        //                                                ));
                
        //         if (empty($nuevo_miembro))
        //         {
                    
        //             if ($i==0)
        //             {
        //                 $no_encontrados = $no_encontrados.$correo;
        //             }
        //             else
        //             {
        //                 $no_encontrados = ', '.$no_encontrados.$correo;
        //             }
        //             $i++;
        //         }
        //         else
        //         {
        //             if ($nuevo_miembro['Person']['role_id']==7)
        //             { $nuevo_rol=8; }
        //             else
        //             { $nuevo_rol=4; }
        //             // Esto sirve solo si un usuario solo perteneciera a una sola entidad
        //             $datos = array('Person'=>array( 'username' => $nuevo_miembro['Person']['username'],
        //                                             'user_id' => $nuevo_miembro['Person']['user_id'],
        //                                             'eslogan' => $nuevo_miembro['Person']['eslogan'],
        //                                             'city' => $nuevo_miembro['Person']['city'],
        //                                             'state' => $nuevo_miembro['Person']['state'],
        //                                             'occupation' => $nuevo_miembro['Person']['occupation'],
        //                                             'path_avatar' => $nuevo_miembro['Person']['path_avatar'],
        //                                             'organization_id' => $organization_id,
        //                                             'role_id' => $nuevo_rol)
        //                             );

        //             $this->Person->id=$nuevo_miembro['Person']['user_id'];

        //             if($this->Person->save($datos))
        //             {
        //             //    echo "persona actualizada<br>";
        //             }

        //             // Agregar entidad a perfil: 
        //             $anade_entidad = array ('OrganizationUser' =>
        //                                         array ( 'user_id' => $nuevo_miembro['Person']['user_id'],
        //                                         'organization_id' => $organization_id));

        //             if($this->OrganizationUser->save($anade_entidad))
        //             {
        //                 //echo "OU actualizado<br>";
        //                 $this->OrganizationUser->id = false;
        //             }
        //             else
        //             {
        //                 //echo "OU NO Act";
        //             }
        //         }   
                
        //     }
            
        //     if ($i==0)
        //     {
        //         echo "Los usuarios han sido añadidos a la entidad ";//.$organization_id;
        //     }
        //     else
        //     {
        //         echo "<p>";
        //         echo (count($correos)-$i)." han sido añadidos a la entidad. <br>";
        //         echo $i." correos no corresponden a usuarios registrados."."(".$no_encontrados.")";
        //         echo "</p>";
        //     }
        // }
        
        public function ask(){
            
        }
        

        public function accept(){
        

        }
        
        public function termsConditions(){
            

        }
        
        public function privacyPolitics(){
            
        
        }
        
        /*********************************************************************************/

        /***************************************************************************************************/
        /************************************ Perfil usuario ************************************/
        public function profile($username = null) {
            
            
            $user = $this->User->find('first',Array('recursive'=>2,'conditions'=>Array('User.username'=> $username)));


            $userId = $user['User']['id'];

            /**
             * Identificador de usuario
             * @var Int
             */
            $userInViewId = $this->Auth->user('id');

            /**
             * Solicitudes de amistad
             */
            $this->loadModel('FriendRequest');                   

            /**
             * Si el usuario que se ve actualmente ha echo una solocitud al usuario logueado
             * @var [type]
             */
            $foundRequest = $this->FriendRequest->find('first',array('conditions'=>Array("request_user_id" => $userId,"requested_user_id" => $userInViewId)));


            /**
             * Si el usuario ha hecho ya una solicitud
             * @var [type]
             */
            $foundRequestByMe = $this->FriendRequest->find('first',array('conditions'=>Array("request_user_id" => $userInViewId,"requested_user_id" => $userId)));


     
            $this->set('User', $user);
            
            $this->set('foundRequest', $foundRequest);

            $this->set('foundRequestByMe', $foundRequestByMe);

            /**
             * Solicitudes de amistad
             */
            $this->loadModel('Friend');  

            /**
             * Si el usuario consultado es amigo del usuario actual
             * @var Array
             */
            $foundFriend = $this->Friend->find('first',array('conditions'=>Array('from_friend_id'=> $userId, 'to_friend_id'=> $userInViewId)));

            $this->set('foundFriend', $foundFriend);

            /**
             * Total de amigos del usuario que esta en la vista
             * @var Int
             */
            $totalFriends = $this->totalFriends($userInViewId);


            $this->set('totalFriends', $totalFriends);



            $topFriends = $this->Friend->find('all',array('limit'=> 10, 'conditions'=>array('Friend.from_friend_id'=> $userId)));

            $this->set('topFriends', $topFriends);


            /**
             * Si el usuario actual esta viendo su perfil
             */
            if($userId == $userInViewId){

                /**
                 * Solicitudes de amistad del usuario actual
                 */
                $friendRequests = $this->FriendRequest->find('all',array('limit'=>5,'conditions'=>Array("requested_user_id" => $userId)));


                $this->set('friendRequests', $friendRequests);

            }
            
	   }


       public function friendsRequests($username){



           $user = $this->User->find('first',Array('recursive'=>2,'conditions'=>Array('User.username'=> $username)));
    
            /**
             * Identificador de usuario
             * @var Int
             */
            $userInViewId = $user['User']['id'];


            /**
             * Solicitudes de amistad
             */
            $this->loadModel('FriendRequest');    


          /**
           * Configuración de la consulta de posts
           * @var Array
           */
          $this->Paginator->settings = Array(
                        // 'order'       =>  'Friend.created DESC',
                        'limit'       =>  10, 
                        'conditions'  => array("requested_user_id" => $userInViewId)
                        // 'recursive' => 2
          );  

            /**
             * Asignamos el resultado de la paginación
             */ 
            $this->set('FriendRequests',$this->paginate($this->FriendRequest));


       }


        /**
         * Funcion que retorna el total de amigos de un usuario
         * @param  [Int] $userId 
         * @return [Int]        
         */
       public function totalFriends($userId){

            $this->loadModel('Friend');  


            /**
             * Total de amigos
             * @var Int
             */
            $totalFriends = $this->Friend->find('count', array(
                'conditions' => Array('from_friend_id'=> $userId)
            ));

            return $totalFriends;
       }

        /**
         * Funcion que retorna el total de amigos de un usuario
         * @param  [Int] $userId 
         * @return [Int]        
         */
       public function totalFriendsAsync(){  

            $this->autoRender = false;

            $this->loadModel('Friend');  

            $userId = $this->request->data['userId'];
    
            /**
             * Total de amigos
             * @var Int
             */
            $totalFriends = $this->Friend->find('count', array(
                'conditions' => Array('from_friend_id'=> $userId)
            ));

            echo json_encode(Array('total'=> $totalFriends));
       }

       public function friendsFrom($username){

    
           $user = $this->User->find('first',Array('recursive'=>2,'conditions'=>Array('User.username'=> $username)));
    
            /**
             * Identificador de usuario
             * @var Int
             */
            $userInViewId = $user['User']['id'];


            /**
             * Solicitudes de amistad
             */
            $this->loadModel('Friend');  


          /**
           * Configuración de la consulta de posts
           * @var Array
           */
          $this->Paginator->settings = Array(
                        'order'       =>  'Friend.created DESC',
                        'limit'       =>  10, 
                        'conditions'  =>  array('Friend.from_friend_id'=> $userInViewId),
                        'recursive'   => 3
          );  

          /**
           * Asignamos el resultado de la paginación
           */ 
          $this->set('userFriends',$this->paginate($this->Friend));

       }


        /*=================================================================================================*/
        
        //
        public function change_color($idColor)
        {
            //$this->layout='ajax';
            $this->autoRender = false;
            
            $userId = $this->Person->id = $this->Auth->user('id');


            // Obtencion de id de sesion de usuario
            //$userId = $this->session->read('User.id');
            
            $usuario = $this->Person->find('first',
                                           array ( 'conditions'=> array('Person.user_id' => $userId),
                                                   'recursive'=>-1
                                                 ));
            
            // Set del id de usuario
            $this->Person->id = $userId;
            
            // Declaracion del array a guardar
            /*$data = array( 'Person' => array(
                    'color' => $idColor
                )
            );*/
            $datos = array('Person'=>array( 'username' => $usuario['Person']['username'],
                                            'user_id' => $usuario['Person']['user_id'],
                                            'eslogan' => $usuario['Person']['eslogan'],
                                            'city' => $usuario['Person']['city'],
                                            'state' => $usuario['Person']['state'],
                                            'occupation' => $usuario['Person']['occupation'],
                                            'path_avatar' => $usuario['Person']['path_avatar'],
                                            'organization_id' => $usuario['Person']['organization_id'],
                                            'role_id' => $usuario['Person']['role_id'],
                                            'color' => $idColor)
                                    );
            
            if( $this->Person->save($datos) )
            {
                $this->Session->write('User.color', $idColor);
                //$this->redirect(array('action' => 'edit'));
                echo 'pase';
            }
            else
            {
                echo 'no pase';
            }
            
        }
        

        /**
         * Función que obtiene información del usuario actual
         * @return JsonString  información de usuario
         */
        public function getUserInfo(){

            $this->autoRender = false;

            /**
             * Identificador del usuario
             * @var Int
             */
            $userId = $this->Auth->user('id');

            $username = $this->Auth->user('username');

            $profilePic = $this->Auth->user('profilePic');

            /**
             * Arreglo con la información
             * @var Array
             */
            $userInfo = Array('success'=>true, 'userInfo'=>Array('id' => $userId, 'username' => $username, 'profilePic' => $profilePic));

            /** 
             * Si el usuario esta logueado mandamos los datos
             */
            if ($this->Auth->loggedIn()) {
         
                echo json_encode($userInfo);

            }else{

                /**
                 * Estado sin éxito
                 */
                echo json_encode(Array('success'=>false));

            }

        }   

        /**
         * Función que obtiene información del usuario actual
         * @return JsonString  información de usuario
         */
        public function getUserInfoPublic(){

            $this->autoRender = false;

            $user = $this->User->find('first',array('recursive'=> 2, 'conditions'=>array('User.id'=>421)));

             /**
             * Arreglo con la información
             * @var Array
             */
            $userInfo = Array('success'=>true, 'userInfo'=>Array('id' => 421, 'username' => $user['User']['username'], 'profilePic' => $user['User']['profilePic']));

     
         
            echo json_encode($userInfo);

        
        }   

        /**
         * Función que obtiene información del usuario actual
         * @return JsonString  información de usuario
         */
        public function getUserById($id){

            $this->autoRender = false;

            $user = $this->User->find('first',array('recursive'=> 2, 'conditions'=>array('User.id'=>$id)));

            return $user;

        }   


        public function getFriends(){


            $this->autoRender = false;


              /**
               * Solicitudes de amistad
               */
              $this->loadModel('Friend');   


              pr($this->Friend->find('all'));


        }

        /**
         * Función para aceptar amistad
         */
        public function acceptFriend(){

            $this->autoRender = false;

            /**
             * Identificador de usuario
             * @var Int
             */
            $userId = $this->Auth->user('id');

            /**
             * Usuario a aceptar amistad
             * @var [type]
             */
            $userToRequestId = $this->request->data['userToRequestId'];
            
            $this->loadModel('Friend');   

            $state = true;
            
            $foundFriend = $this->Friend->find('first',array('conditions'=>Array('from_friend_id'=> $userId, 'to_friend_id'=> $userToRequestId)));

            /**
             * Si no son amigos se crean las relaciones 
             */
            if (!$foundFriend) {


                /**
                 * Primera Relación
                 */
                $this->Friend->Create();
                if(!$this->Friend->save(Array('from_friend_id'=> $userId, 'to_friend_id'=> $userToRequestId))){
                    $state = false;
                }
                
                $this->Friend->Clear();

                /**
                 * Segunda relación 
                 */
                $this->Friend->Create();
                if(!$this->Friend->save(Array('from_friend_id'=> $userToRequestId, 'to_friend_id'=> $userId))){
                    $state = false;
                }
                
                $this->Friend->Clear();

                /**
                 * Eliminacion de requests
                 */

                /**
                 * Solicitudes de amistad
                 */
                $this->loadModel('FriendRequest');   

                /**
                 * Si el usuario que se ve actualmente ha echo una solocitud al usuario logueado
                 */
                $foundRequest = $this->FriendRequest->find('first',array('conditions'=>Array("request_user_id" => $userId,"requested_user_id" => $userToRequestId)));

                if($foundRequest){

                    $this->FriendRequest->id = $foundRequest['FriendRequest']['id'];
                    $this->FriendRequest->delete();
                    $this->FriendRequest->clear();
                }

                /**
                 * Si el usuario ha hecho ya una solicitud
                 */
                $foundRequest = $this->FriendRequest->find('first',array('conditions'=>Array("request_user_id" => $userToRequestId,"requested_user_id" => $userId)));

                if($foundRequest){

                    $this->FriendRequest->id = $foundRequest['FriendRequest']['id'];
                    $this->FriendRequest->delete();
                    $this->FriendRequest->clear();
                }

            }   

            echo json_encode(Array('success'=>true));
        }


        public function friendRequests(){


            $this->autoRender = false;

              /**
               * Solicitudes de amistad
               */
              $this->loadModel('FriendRequest');   

              

              pr($this->FriendRequest->find('all'));
        }

        /**
         * Solicitud de amistad
         */
        public function friendRequest(){


            $this->autoRender = false;

            /**
             * Solicitudes de amistad
             */
            $this->loadModel('FriendRequest');   


            /**
             * Identificador de usuario
             * @var Int
             */
            $userId = $this->Auth->user('id');


            $userToRequestId = $this->request->data['userToRequestId'];

            $this->FriendRequest->Create();

            $state = true;


            if(!$this->FriendRequest->save(Array("request_user_id" => $userId,"requested_user_id" => $userToRequestId,'viewed'=>0))){

                $state = false;

            }

            echo json_encode(Array("success"=>$state));

        }


        public function cancelFriendRequest(){


            $this->autoRender = false;

            /**
             * Identificador de usuario
             * @var Int
             */
            $userId = $this->Auth->user('id');


            $userToRequestId = $this->request->data['userToRequestId'];

            /**
             * Solicitudes de amistad
             */
            $this->loadModel('FriendRequest');   

            $state = true;

            $foundRequest = $this->FriendRequest->find('first',array('conditions'=>Array("request_user_id" => $userId,"requested_user_id" => $userToRequestId)));

            if ($foundRequest) {
                
                $this->FriendRequest->id = $foundRequest['FriendRequest']['id'];
                
                if (!$this->FriendRequest->delete()) {
                   
                    $state = false;
                
                }
            
            }

            echo json_encode(Array("success"=>$state));

        }



        public function ensayemos(){

            $this->autoRender = false;

            // $passwordHasher = new BlowfishPasswordHasher();

            // echo $passwordHasher->hash('12345678');

            // echo $this->Session->read('Config.time');

            $bytes = openssl_random_pseudo_bytes(15);
            $pwd = bin2hex($bytes);


            echo $pwd;
        }

        public function forgotPassword(){

            // Diferente Layout
            $this->layout = 'login';

            if ($this->request->is('post')) {


                $email = $this->request->data['User']['email'];


                $foundUser = $this->User->find('first',array('recursive'=> -1, 'conditions'=>array('User.email'=>$email)));

                if($foundUser){
                


                    $this->User->id = $foundUser['User']['id'];

                    $randomPassword = $this->getRandomPassword();



                    /**
                     * Si se guardo la nueva contraseña
                     */
                    if($this->User->saveField("password", $randomPassword)){


                        /**
                         * Envio de Email con el password generado...
                         */
                        if($this->restorePassMail($foundUser, $randomPassword)){


                            $this->Session->setFlash('Ocurrio un problema al enviar el correo electronico');

                            $this->redirect($this->Auth->redirect(array('controller'=>'Users', 'action' => 'login')));


                        }else{

                            $this->Session->setFlash('Se ha enviado un correo eléctronico a '.$foundUser['User']['email'].' con información para restaurar tu contraseña');

                            $this->redirect($this->Auth->redirect(array('controller'=>'Users', 'action' => 'login')));


                        }


                    }



                }else{

                    $this->Session->setFlash('No se encontró el email');

                }                

            }

        }

        /**
         * Funcion de recuperacion de contraseña
         */
        public function forgotPasswordAjax(){


            $this->autoRender = false;

            if ($this->request->is('post')) {

                $email = $this->request->data['email'];

                $foundUser = $this->User->find('first',array('recursive'=> -1, 'conditions'=>array('User.email'=>$email)));

                if($foundUser){
                
                    $this->User->id = $foundUser['User']['id'];

                    $randomPassword = $this->getRandomPassword();

                    /**
                     * Si se guardo la nueva contraseña
                     */
                    if($this->User->saveField("password", $randomPassword)){

                        /**
                         * Envio de Email con el password generado...
                         */
                        if($this->restorePassMail($foundUser, $randomPassword)){

                            echo json_encode(Array('success'=>false,'message'=> 'Ocurrio un problema al enviar el correo electronico'));

                        }else{

                            $message = 'Se ha enviado un correo eléctronico a '.$foundUser['User']['email'].' con información para restaurar tu contraseña';

                            echo json_encode(Array('success' => true, 'message' => $message ));                            

                        }
                   }

                }else{

                    echo json_encode(Array('success' => false, 'message' => 'No se encontró el email' ));

                }
            }
        }

        public function restorePassMail($user, $randomPassword){


            /**
             * Julián Andrés Muñoz Cardozo
             * 2016-08-17 11:47:17
             * cambio en mensaje de recuperacion de contraseña
             */
             $msg = "Recuperaci&oacute;n de contrase&ntilde;a, ahora tus datos de sesi&oacute;n son: usuario: ".$user['User']['username']." contrase&ntilde;a: ".$randomPassword. " ingresa ahora a http://redkastella.com/ y Aseg&uacute;rate de cambiar tu contrase&ntilde;a en tanto inicies sesi&oacute;n";

             // send email
             // mail($user['User']['email'], "Kastella - Cambio de contraseña", $msg);



            return $this->SimpleEmail->contactMail($msg, $user['User']['email'], 'RedKastella', 'RedKastella' ,'Recuperación de contraseña');


        }


        /**
         * Función que obtiene una contraseña aleatoria
         */
        public function getRandomPassword(){

            $bytes = openssl_random_pseudo_bytes(10);
            $pwd = bin2hex($bytes);

            return trim($pwd);

        }



        public function setRecentlyRegistered(){

            $this->autoRender = false;

            $this->User->id = $this->Auth->user('id');

            $this->User->saveField('recently_registered',0);

            $this->Session->write('Auth.User.recently_registered', 0);


        }

        public function actualizar(){

            $this->autoRender = false;
            
            $consulta = $this->User->query("SELECT TABLE_NAME, ENGINE FROM information_schema.TABLES where TABLE_SCHEMA = 'kastdb' and information_schema.TABLES.ENGINE = 'InnoDB';");
            
            foreach($consulta as $cons){
                
                $table = $cons['TABLES']['TABLE_NAME'];
                
                echo "ALTER TABLE ".$table." ENGINE=MyISAM;";
                echo "<br />";
                
            }
        }



        
    }



