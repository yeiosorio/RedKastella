<?php
    

	/**
	 * Controlador de likes
	 */
   	class LikesController extends AppController
    {


       public $name = 'Likes';

       public $uses = array('Like','PostLike','User');

        // Declaracion de helpers que ayudaran en la creacion de formularios
        public $helpers = array ('Html', 'Form', 'Time', 'Js','Icon','StringUtil','Session');
        
        // Declaracion de los componentes para inicio de sesion
        public $components = array('Session','RequestHandler', 'Paginator');



    	/**
    	 * Función que agregara un like a una entidad
    	 * @return JsonString
    	 */
    	public function like(){

    		$this->autoRender = false;

    		/**
    		 * datos del método post
    		 * @var Array
    		 */
    		$data = $this->request->data;

    		/**
    		 * Entidad definida en plural de donde sera enlazado
    		 * @var String
    		 */
    		$entity = $data['to']; 
    		/**
    		 * Identificador de la entidad
    		 * @var Int
    		 */
    		$entityId = $data['id'];


    		$state = true;

    		/**
    		 * switch donde se evaluara la entidad a la que ira el like
    		 */
    		switch ($entity) {
    			case 'comments':


    				break;

    			case 'posts':
    			
    				$state = $this->insertLikeToPost($entityId);	
    				break;
    		}



    		/**
    		 * Resultado
    		 */
    		echo json_encode(Array('success'=>$state));


    	}


    	/**
    	 * Función que inserta un like
    	 * @return Int identificador del like, Boolean false en caso de fallo
    	 */
    	public function insertLike(){

    		$this->autoRender = false;

	    		$userId = $this->Auth->user('id');

	    		$this->Like->Create();

	    		if($this->Like->save(Array('users_id'=> $userId))){

	    			return $this->Like->id;
	    		}

	    		return false;
     	}

    	/**
    	 * Enlazamos el post al like
    	 * @param  Int $postId identificador
    	 * @return Boolean estado de guardado
    	 */
    	public function insertLikeToPost($postId = null){

    		/**
    		 * Verificamos si el post tiene un like
    		 * @var Boolean
    		 */
    		$existLike = $this->existLikeInPost($postId);

    		/**
    		 * Si no tiene like
    		 */
    		if (!$existLike) {

    			/**
    			 * Variable que contiene el ideintificador del like insertado
    			 * @var Int
    			 */
	    		$likeId = $this->insertLike($postId);

	    		/**
	    		 * Si no hubo errores insentando guardamos la relación
	    		 */
	    		if ($likeId) {

	    			$this->PostLike->Create();
	    			
	    			if($this->PostLike->save(Array('posts_id'=>$postId,'likes_id'=>$likeId))){

	    				return true;
	    			}
	    			return false;
	    		}

	    		return false;    			


    		}else{
    			return true;
    		}

    	}

    	/**
    	 * funcion que verifica si un post tiene like de un usuario
    	 * @param  int $postId Identificador del post
    	 * @return Boolena true si tiene un like y lo elimino, falso si no tiene
    	 */
    	public function existLikeInPost($postId = null){

    		$userId = $this->Auth->user('id');

    		/**
    		 * Buscamos su relación con posts
    		 * @var Array
    		 */
    		$likes = $this->Like->PostLike->find('first',array(
    			'conditions'=>Array('PostLike.posts_id'=> $postId,'PostLike.likes_id = Like.id','Like.users_id'=>$userId)
    			));

    		/**
    		 * si ya tiene un like
    		 */
    		if ($likes) {
    			
   				if($this->Like->delete($likes['Like']['id'])){
   					return true;
   				}

   				return false;
   			}
   			return false;
    	}


        public function getUserLikesPost(){


            $this->autoRender = false;

            $postId = $this->request->data['postId'];

            $postLikes =  $this->PostLike->find('all',array('limit' => 5, 'conditions' => array('PostLike.posts_id' => $postId)));

            $usersLikes = Array();

            foreach ($postLikes as $like) {
                
                $usersLikes[] = $this->User->find('first',array('recursive'=> -1, 'conditions'=>array('User.id'=>$like['Like']['users_id'])));

            }



            echo json_encode($usersLikes);
        }


        public function seePeopleToPostLikes($postId){



              /**
               * Configuración de la consulta de posts
               * @var Array
               */
              $this->Paginator->settings = Array(
                            // 'order'       =>  'Post.id DESC',
                            'limit'       =>  10, 
                            'conditions' => Array('PostLike.posts_id' => $postId),
                            'recursive' => -2
              );  



              $userPostLikeComplete = Array();

              $userPostLikes = $this->paginate($this->PostLike);



              foreach ($userPostLikes as $userPostLike) {
               
                    $userPostLikeComplete[] = $this->User->find('first',array('recursive'=> -1, 'conditions'=>array('User.id'=>$userPostLike['Like']['users_id'])));


              }

              /**
               * Asignamos el resultado de la paginación
               */ 
              $this->set('userPostLikeComplete',$userPostLikeComplete);


        }


        public function beforeFilter() {
            
            parent::beforeFilter();

            
        }

    }









