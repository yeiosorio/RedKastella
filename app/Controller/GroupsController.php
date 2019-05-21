<?php 
    class GroupsController extends AppController
    {
        

        // Declaracion de los componentes para inicio de sesion
        public $components = array('Session','RequestHandler', 'Paginator');
        
        // Declaracion de helpers que ayudaran en la creacion de formularios
        public $helpers = array ('Time','Session');


        /**
         * Acción que muestra todas los grupos 
         */
      	public function groups(){


      	  $this->loadModel('Organization');		

      	  /**
           * Configuración de la consulta de grupos
           * @var Array
           */
          $this->Paginator->settings = Array(
                        'order'       =>  'Organization.id DESC',
                        'limit'       =>  10, 
                        'recursive' => 3
          );  

          /**
           * Asignamos el resultado de la paginación
           */ 
          $this->set('Organizations',$this->paginate($this->Organization));


          
      	}  


        /**
         * Grupos a los que un usuario pertenece
         */
        public function myGroups(){



        }

        /**
         * Solicitud de pertenecer a un grupo
         */
        public function belongToGroup(){

          $this->autoRender = false;

          $data = $this->request->data;

          $userId = $this->Auth->user('id');

          $groupId = $data['groupId'];

          $this->loadModel('OrganizationRequest');   

          // Si el usuario no ha hecho la solicitud
          if(!$this->userhasRequestedGroup($groupId, $userId)){

            $this->OrganizationRequest->Create();


            // Si se guardo la peticion
            if ($this->OrganizationRequest->save(Array('organizations_id'=> $groupId, 'users_id'=>$userId))) {
                
                echo json_encode(Array('success'=>true, 'request_id' => $this->OrganizationRequest->id));
            }

          }else{

          }

        }


        /**
         * Cancelacion de solicitud de pertenecer a un grupo
         */
        public function cancelBelongToGroup(){


          $this->autoRender = false;


          $this->loadModel('OrganizationRequest');   

          $this->OrganizationRequest->id = $this->request->data['requestId'];

          if($this->OrganizationRequest->delete()){

            echo json_encode(Array('success'=>true));

          }


        }

    
        /**
         * Función que pregunta si el usuario ya ha enviado una invitacion 
         * @param  [type] $groupId [description]
         * @param  [type] $userId  [description]
         * @return [type]          [description]
         */
        public function userhasRequestedGroup($groupId, $userId){

            $this->loadModel('OrganizationRequest');   


            return $this->OrganizationRequest->find('first', array('conditions' => array('organizations_id' => $groupId, 'users_id' => $userId)));


        }


        /**
         * Funcion que obtiene las solicitudes de un grupo
         * @param  [Int] $groupId Identificador del grupo
         * @return [Array] Usuarios que han enviado solicitud al grupo
         */
        public function getGroupRequests($groupId){

            $this->loadModel('OrganizationRequest');   

            $organizationRequests = $this->OrganizationRequest->find('all',Array('order'=>'OrganizationRequest.created DESC' ,'recursive'=>3, 'conditions'=>Array('OrganizationRequest.organizations_id'=> $groupId)));

            return $organizationRequests;

        }



        public function group($nit = null){



              $this->loadModel('Organization');   

              $this->loadModel('OrganizationUser');   


              $organization = $this->Organization->find('first', array('recursive' => 2, 'conditions'=>array('Organization.nit'=>$nit)));

              $numberOfMembers = $this->OrganizationUser->find('count',array('conditions'=>array('OrganizationUser.organization_id'=>$organization['Organization']['id'])));

              $topMembers = $this->OrganizationUser->find('all',array('limit'=> 10, 'recursive' => 2, 'conditions'=>array('OrganizationUser.organization_id'=>$organization['Organization']['id'])));

              $this->set('organization',$organization);

              $this->set('numberOfMembers',$numberOfMembers);

              $this->set('topMembers',$topMembers);

        }


        public function groupMembers($nit = null){


              $this->loadModel('Organization');   
              
              $organization = $this->Organization->find('first', array('recursive' => 2, 'conditions'=>array('Organization.nit'=>$nit)));

              $this->loadModel('OrganizationUser');   


               /**
               * Configuración de la consulta de Los usuarios de la organizacion
               * @var Array
               */
              $this->Paginator->settings = Array(
                            'order'       =>  'User.id DESC',
                            'limit'       =>  10, 
                            'conditions'  => Array('Organization.id'=> $organization['Organization']['id']),
                            'recursive'   => 3
              );

              /**
               * Asignamos el resultado de la paginación
               */ 
              $this->set('organizationUsers',$this->paginate($this->OrganizationUser));            
                    

        }


        /**
         * Funcionalidad de admisitración de grupo 
         */
        public function myGroup(){

            $userId = $this->Auth->user('id');
              
            $this->loadModel('OrganizationUser');   

            /**
             * Variable que contiene la organizacion creada por un usuario
             * @var Array
             */
            $userOrganization = $this->userOwnOrganization($userId);

            /**
             * Se manda la variable anterior a la vista
             */
            $this->set('userOrganization',$userOrganization);


            $numberOfMembers = 0;

            if(isset($userOrganization['Organization'])){

              $numberOfMembers = $this->OrganizationUser->find('count',array('conditions'=>array('OrganizationUser.organization_id'=>$userOrganization['Organization']['id'])));

            }

            /**
             * Se manda la variable anterior a la vista
             */
            $this->set('numberOfMembers',$numberOfMembers);




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
                        $this->redirect(array('controller'=>'Groups', 'action' => 'myGroup'));

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
                    




              /**
               * Solicitudes de grupo
               */

              $groupRequests = $this->getGroupRequests($organizationId);

              /**
               * Asignamos los resultados de las solicitudes
               */ 
              $this->set('groupRequests',$groupRequests);            
              




            }

        }


        /**
         * Función que retorna si un usuario es el creador/propietario de una organización
         * @return Array Organización
         */
        public function userOwnOrganization($userId = null){
          
            $this->loadModel('Organization');   
             
            $userOrganization = $this->Organization->find('first',array('recursive'=>2, 'conditions'=>array('Organization.user_id'=>$userId)));

            return $userOrganization;

        }




        public function acceptUserRequest(){

            /**
             * No renderizamos vista
             * @var boolean
             */
            $this->autoRender = false;
            

            /**
             * Organization users model
             */
            $this->loadModel('OrganizationUser');   


            /**
             * Identificador del usuario logueado
             * @var Int
             */
            $userId = $this->Auth->user('id');


            $idAcceptedUser = $this->request->data['idAcceptedUser'];

            /**
             * Creación de nueva relación de entidad con usuario
             */
            $this->OrganizationUser->create();
              
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
             * Ingreso del registro
             */
            $this->OrganizationUser->save(Array('user_id'=> $idAcceptedUser, 'organization_id' => $organizationId));
                      


            $this->loadModel('OrganizationRequest');   

            $this->OrganizationRequest->id = $this->request->data['requestId'];

            $this->OrganizationRequest->delete();
                        


        }        













        
        public function beforeFilter() {
            parent::beforeFilter();
         
            // Diferente Layout


        }


    }

?>