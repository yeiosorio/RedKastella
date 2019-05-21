<?php 
    
	
class ChatController extends AppController{




	public function beforeFilter() {
    
    	parent::beforeFilter();
        
        // $this->Auth->allow('add', 'logout');
        


    }

	public function chat(){

		$this->loadModel('User');

		$users = $this->User->find('all',array('recursive'=>-1));

		$this->set('users',$users);

	}


	public function getChatMessages(){
		
		$this->autoRender = false;


		$data = $this->request->data;

		$userId = $data['userId']; 
		$fromUserId = $data['fromUserId'];
	
		$messages = $this->Chat->find('all',
				array(
					'limit' => 5,
					'order'=>'Chat.created DESC',
					'conditions' => 
						array(
							'Chat.user_id_from = '.$fromUserId.' AND  Chat.user_id_to = '.$userId.' OR Chat.id in (SELECT chats.id FROM chats where user_id_from = '.$userId.' AND user_id_to = '.$fromUserId.')', 
						)
			)
		);

		echo json_encode($messages);		

	}


	public function saveMessage(){

		$this->autoRender = false;

		$data = $this->request->data;

		$message = $data['message']; 
		$toUserId = $data['toUserId'];
		$userId = $data['userId'];
			

		$this->Chat->Create();

        if ($this->Chat->save(Array('message'=>$message, 'user_id_from'=>$userId,'user_id_to'=> $toUserId))) {

        	echo json_encode(Array('success'=>true));

        }else{

        	echo json_encode(Array('success'=>false));
        }

	}

}