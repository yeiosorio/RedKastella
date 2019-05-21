<?php 


class NotificationsController extends AppController
{
        

    public $components = array('Paginator'); 

    public $helpers = array('Session'); 





	public function notifications(){


		// $this->loadModel('NotificationsInterestContract');

		// $this->loadModel('InterestContract');

		// $id = $this->Auth->user('id');

  //       /**
  //        * Configuración de la consulta de posts
  //        * @var Array
  //        */
  //       $this->Paginator->settings = Array(
  //                   'order'       =>  'NotificationsInterestContract.created DESC',
  //                   'limit'       =>  20, 
  //                   'conditions'  => Array('UserInterestContracts.users_id'=> $id),
  //                   'recursive' => 2,
  //       );  


  //       $results = $this->paginate($this->NotificationsInterestContract);

  //       $i = 0;
  //       foreach ($results as $result) {

  //       	$contract = $this->InterestContract->find('first',array('recursive' => -1, 'conditions'=>array('InterestContract.id' => $result['UserInterestContracts']['interest_contracts_id'])));
  //      		$results[$i]['InterestContract'] = $contract['InterestContract'];

  //      		$i = $i +1;
  //       }

  //      	/**
  //        * Asignamos el resultado de la paginación
  //        */ 
  //        

        // $this->set('notifications',$results);

	}


	public function getNotifications(){


		$this->autoRender = false;

		$this->loadModel('NotificationsInterestContract');

		$this->loadModel('InterestContract');		

		$id = $this->Auth->user('id');

		$notifications = $this->NotificationsInterestContract->find('all',array('order' => 'NotificationsInterestContract.created DESC', 'limit' => 2, 'group'=> 'NotificationsInterestContract.user_interest_contracts_id', 'conditions' => array('UserInterestContracts.users_id'=> $id)));




		$countNotifications = $this->NotificationsInterestContract->find('count',array('group'=> 'NotificationsInterestContract.user_interest_contracts_id', 'conditions'=>array('UserInterestContracts.users_id'=> $id,'NotificationsInterestContract.viewed'=>0)));
		
		$contractsNotify = Array();
		
		foreach ($notifications as $notification) {
				
			$contractsNotify[] = $this->InterestContract->find('first',array('recursive' => -1, 'conditions'=>array('InterestContract.id'=> $notification['UserInterestContracts']['interest_contracts_id'])));

		}


		$contractsFormatted = Array();

		foreach ($contractsNotify as $contract) {
		
			$contractsFormatted[] = $contract['InterestContract'];
		}

		echo json_encode(Array('contractNotifications' => $contractsFormatted, 'numberNewNotifications'=> $countNotifications));
	}



	/**
	 * Function to update the estate of view from the last notifications about contracts
	 */
	public function updateContractNotifications(){

		$this->autoRender = false;

		$this->loadModel('NotificationsInterestContract');

		$id = $this->Auth->user('id');

		$notifications = $this->NotificationsInterestContract->find('all',array('conditions'=>array('UserInterestContracts.users_id'=> $id,'NotificationsInterestContract.viewed'=>0)));

		foreach ($notifications as $notification) {
			
				$this->NotificationsInterestContract->id = $notification['NotificationsInterestContract']['id'];

				$this->NotificationsInterestContract->save(Array('viewed' => 1));	

				$this->NotificationsInterestContract->clear();

		}

		echo json_encode(Array('success' => true));

	}


	/**
	 * Funcion to get the friend requests as notifications
	 */
	public function getFriendNotifications(){


		$this->autoRender = false;

        /**
         * Solicitudes de amistad
         */
        $this->loadModel('FriendRequest');    
		
        
        $userId = $this->Auth->user('id');

		/**
         * Solicitudes de amistad del usuario actual
         */
        $friendRequests = $this->FriendRequest->find('all',array('recursive'=> 3, 'limit' => 5, 'conditions' => Array("requested_user_id" => $userId)));


        $numFriendRequests = $this->FriendRequest->find('count',array('conditions'=>Array("requested_user_id" => $userId,'viewed'=>0)));

        
        echo json_encode(Array('FriendRequest'=> $friendRequests,'numFriendRequests'=> $numFriendRequests));


	}

	/**
	 * Function to update the friend request notifications  
	 */
	public function updateFriendNotification(){

		$this->autoRender = false;

        /**
         * Solicitudes de amistad
         */
        $this->loadModel('FriendRequest');    

        $userId = $this->Auth->user('id');

        $friendRequestsNotifications = $this->FriendRequest->find('all',array('conditions'=>Array("requested_user_id" => $userId,'viewed'=>0)));


        foreach ($friendRequestsNotifications as $friendRequestsNotification) {
        		

        	$id = $friendRequestsNotification['FriendRequest']['id'];

        	$this->FriendRequest->id = $id;

        	$this->FriendRequest->save(Array('viewed'=>1));

			$this->FriendRequest->clear();


        }

        echo json_encode(Array('success'=>true));

	}


    public function beforeFilter() {
        parent::beforeFilter();

        // Allow users to register and logout.
        $this->Auth->allow('getNotifications','getFriendNotifications');
        

    }



   
        /**
         * Función que obtiene los datos de las notificaciones
         * @return JsonString String con los datos en formato Json
         */
        public function getContractNotifyUser(){

          $this->autoRender = false;

          $userId = $this->Auth->user('id');

          /**
           * desde que número de resultados devolvera items
           * @var Int
           */
          $from = $this->request->data['from'];

          /**
           * Número de resultados
           * @var Int
           */
          $number = 20;



      		$this->loadModel('NotificationsInterestContract');

      		$this->loadModel('InterestContract');


      		$results = $this->NotificationsInterestContract->find('all',array(
      					'order'       =>  'NotificationsInterestContract.created DESC',
                          'limit'       =>  $number, 
                          'offset'      =>  $from,
                          'conditions'  => Array('UserInterestContracts.users_id'=> $userId),
                          'recursive' => 3,
      			)
      		);


        $i = 0;
        foreach ($results as $result) {


          $this->NotificationsInterestContract->id = $result['NotificationsInterestContract']['id'];

          $this->NotificationsInterestContract->save(Array('NotificationsInterestContract'=>Array('viewed'=> 1)));

        	$contract = $this->InterestContract->find('first',array('recursive' => 2, 'conditions'=>array('InterestContract.id' => $result['UserInterestContracts']['interest_contracts_id'])));

       		$results[$i]['InterestContract'] = $contract['InterestContract'];

       		$i = $i +1;
        }

          /**
           * Se imprimen los resultados en tipo Json
           */
          echo json_encode($results);

        }


}






