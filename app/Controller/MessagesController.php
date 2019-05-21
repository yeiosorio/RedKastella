<?php
App::uses('AppController', 'Controller');
/**
 * Messages Controller
 *
 * @property Message $Message
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class MessagesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','Session');

	public $helpers = array('Session');


	public function getItemsCommaSep($arr){

		$ids = "";

        foreach ($arr as $a) {
        
        	$ids = $ids.$a.',';

        }

 		return substr($ids, 0, -1);

   	}


	public function messages(){

	 	
	 

	    $organizations = $this->getUserOrganizations();

	    
	    $organizationsList = Array();

	    foreach ($organizations as $organization) {
	    		
	    	$organizationsList[$organization['Organization']['id']] = $organization['Organization']['name'];
	    	
	    }


	    $this->set('organizationsList',$organizationsList);


	}
	/**
	 * Función que obtiene las organizaciones a las que pertenece un usuario
	 * @return [type] [description]
	 */
	public function getUserOrganizations(){


		$userId = $this->Auth->user('id');

		$this->loadModel('OrganizationUser');

	    $organizations = $this->OrganizationUser->find('all',array('conditions'=>array('OrganizationUser.user_id' => $userId )));

	    return $organizations;

	}


// /**
//  * index method
//  *
//  * @return void
//  */
// 	public function index() {
// 		$this->Message->recursive = 0;
// 		$this->set('messages', $this->Paginator->paginate());
// 	}

// /**
//  * view method
//  *
//  * @throws NotFoundException
//  * @param string $id
//  * @return void
//  */
// 	public function view($id = null) {
// 		if (!$this->Message->exists($id)) {
// 			throw new NotFoundException(__('Invalid message'));
// 		}
// 		$options = array('conditions' => array('Message.' . $this->Message->primaryKey => $id));
// 		$this->set('message', $this->Message->find('first', $options));
// 	}

/**
 * add method
 *
 * @return void
 */
	public function send() {

		$this->autoRender = false;

		if ($this->request->is('post')) {
			
			$this->Message->create();	

			$data = $this->request->input('json_decode');

			$newData['users_id'] = $this->Auth->user('id');

			$newData['message'] = $data->message;

			$newData['subject'] = $data->subject;
				
			
			if ($this->Message->save($newData)) {
				
				// si es para usuarios ingresados 
				// 
				
				if($data->selectedType == 1){

					/**
					 * identifiers of users
					 * @var Array
					 */
					$usersIds = $data->usersIds;

					$this->saveMessagesToUsers($this->Message->id, $usersIds);



					// si es para usuarios de un grupo seleccionado
				}else{


					$this->loadModel('OrganizationUser');

					$organizationId = intval($data->organizationId);


					$userIds = $this->OrganizationUser->find('all',array('conditions'=>array('OrganizationUser.organization_id'=> $organizationId)));

					$userIdsArray = Array();

					foreach ($userIds as $userId) {
						
						/**
						 * Si el usario es diferente del que lo envia
						 */
						if($userId['User']['id'] != $this->Auth->user('id')){
							$userIdsArray[] = $userId['User']['id'];	
						}

					}

					

					$this->saveMessagesToUsers($this->Message->id, $userIdsArray);



				}

					echo json_encode(Array('success' => true));
					
			} else {

					echo json_encode(Array('success' => false));
			
			}
		
		}
		
	}


	// insert the message in relation with selected users

	public function saveMessagesToUsers($messageId, $usersIds){



		$this->loadModel('MessagesUser');

		foreach ($usersIds as $userId) {



			$this->MessagesUser->create();

			$this->MessagesUser->save(Array('messages_id'=> $messageId,'users_id'=>$userId));

			$this->MessagesUser->Clear();


		}

	}

	public function pageMessagesReceived(){



		$this->autoRender = false;

		$this->loadModel('MessagesUser');

		$data = $this->request->input('json_decode');
		
		$offset = $data->offset;

		$info = $this->MessagesUser->find('all',array( 'order'=>'MessagesUser.created DESC', 'recursive'=>2, 'limit' => 10, 'offset'=> $offset, 'conditions'=>array('MessagesUser.users_id'=> $this->Auth->user('id'),'MessagesUser.dropped'=>0)));


		$totalItems = $this->MessagesUser->find('count',Array('conditions' => Array('MessagesUser.users_id'=> $this->Auth->user('id'))));

		if ($info) {
			
			echo json_encode(Array('success'=>true,'info' => $info,'total'=>$totalItems));
	
		}else{
		
			echo json_encode(Array('success'=>false));
		}


	}





	public function pageSentMessages(){

		$this->autoRender = false;

		$data = $this->request->input('json_decode');
		
		$offset = $data->offset;

		$info = $this->Message->find('all', Array('order'=>'Message.created DESC', 'recursive'=> 2, 'limit' => 10, 'offset'=> $offset, 'conditions' => Array('Message.users_id'=> $this->Auth->user('id'),'Message.dropped'=>0 )));

		$totalItems = $this->Message->find('count',Array('conditions' => Array('Message.users_id'=> $this->Auth->user('id'))));

		if ($info) {
			
			echo json_encode(Array('success'=>true,'info' => $info,'total'=>$totalItems));
	
		}else{
		
			echo json_encode(Array('success'=>false));
		}

	}

	public function dropMessage(){

		$this->autoRender = false;

 
	    $data = $this->request->input('json_decode');

	    $messageId = $data->messageId;

	    $ownOr = $data->ownOr;

	    /**
	     * Variable para identificar si el mensaje es enviado o recibido, si es 1 es recibido, si es 2 enviado
	     * @type {Number}
	     */
   	    if ($ownOr == 1) {
	   		

	   		$this->loadModel('MessagesUser');

	   		$this->MessagesUser->id = intval($messageId);

	   		$this->MessagesUser->save(Array('dropped'=>1));


	    }else{


	   		$this->Message->id = intval($messageId);

	   		$this->Message->save(Array('dropped'=>1));


	    }


	    echo json_encode(Array('success'=>true));


	}


	/**
	 * Función para obtener los usuarios en la búsqueda
	 */
	// public function getUsersToSendEmail(){


	// 	$this->autoRender = false;

	// 	$this->loadModel('User');

	// 	// Busqueda de personas en amigos y usuarios de grupos al que pertenece el usuario logueado 

	// 	$term = $this->request->data['query'];


	// 	$result = $this->User->find('all', array('conditions' => array("User.username like '%".$term."%' OR User.email like '%".$term."%' OR concat(User.name, ' ', User.surname) like '%".$term."%' ")));

				
	// 	echo json_encode($result);

	// }



	public function getOrganizationIdsUser(){


		$organizations = $this->getUserOrganizations();

		$organizationIds = Array();

		foreach ($organizations as $organization) {
			
		 	$organizationIds[] = $organization['OrganizationUser']['organization_id'];
		
		}

		return $organizationIds;

	}

	/**
     * Función que retorna los resultados de la búsqueda global : personas y grupos - tengo que hacer esto :D
     */
    public function peopleSearch(){


            $this->autoRender = false;

            $userId = $this->Auth->user('id');

            $this->loadModel('User');



            // $organizationIds = $this->getOrganizationIdsUser();


            // $organizationIdsComma = $this->getItemsCommaSep($organizationIds);


            /**
             * Criterio de búsqueda
             * @var String
             */
            $query = $this->request->data['query'];

            /**
             * Resultados
             * @var Array
             */
            
           	$users = $this->User->find('all', array(
           			'recursive' =>  2, 
           			'limit'=>10, 
           			'conditions' => array(
           					"User.username like '%".$query."%' OR User.email like '%".$query."%' OR concat(User.name, ' ', User.surname) like '%".$query."%' ",
           					//"User.id in(SELECT ou.user_id FROM organization_users ou WHERE ou.user_id in (".$organizationIdsComma.")) OR User.id in (SELECT f.from_friend_id FROM friends f WHERE f.to_friend_id = ".$userId.")"
           			)
           	));
           	



           	// 
           	// 

           	// 

           	// friends.from_friend_id   User.id

           	// friends.to_friend_id     $userId
           	


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
                	'id' => $user['User']['id'],
                	'email' => $user['User']['email'],	
                    'username' => $user['User']['username'],            
                    'name' => $user['User']['name'],
                    'surname' => $user['User']['surname'],
                    'profilePic' => $user['User']['profilePic'],
                    'municipality' => $user['Municipality']['municipality'],
                    'department' => $user['Municipality']['Department']['name']    
                );
                

            }


            /**
             * Impresión de resultados
             */
            echo json_encode( Array('users' => $usersList));

      

        }




// /**
//  * edit method
//  *
//  * @throws NotFoundException
//  * @param string $id
//  * @return void
//  */
// 	public function edit($id = null) {
// 		if (!$this->Message->exists($id)) {
// 			throw new NotFoundException(__('Invalid message'));
// 		}
// 		if ($this->request->is(array('post', 'put'))) {
// 			if ($this->Message->save($this->request->data)) {
// 				$this->Flash->success(__('The message has been saved.'));
// 				return $this->redirect(array('action' => 'index'));
// 			} else {
// 				$this->Flash->error(__('The message could not be saved. Please, try again.'));
// 			}
// 		} else {
// 			$options = array('conditions' => array('Message.' . $this->Message->primaryKey => $id));
// 			$this->request->data = $this->Message->find('first', $options);
// 		}
// 		$users = $this->Message->User->find('list');
// 		$messagesTypes = $this->Message->MessagesType->find('list');
// 		$this->set(compact('users', 'messagesTypes'));
// 	}

// /**
//  * delete method
//  *
//  * @throws NotFoundException
//  * @param string $id
//  * @return void
//  */
// 	public function delete($id = null) {
// 		$this->Message->id = $id;
// 		if (!$this->Message->exists()) {
// 			throw new NotFoundException(__('Invalid message'));
// 		}
// 		$this->request->allowMethod('post', 'delete');
// 		if ($this->Message->delete()) {
// 			$this->Flash->success(__('The message has been deleted.'));
// 		} else {
// 			$this->Flash->error(__('The message could not be deleted. Please, try again.'));
// 		}
// 		return $this->redirect(array('action' => 'index'));
// 	}
}
