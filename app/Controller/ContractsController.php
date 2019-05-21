<?php 

class ContractsController extends AppController
{

	var $uses = array('Post','SecopLink');

	public $components = array('ResourceManager.ResourceManager');
  public $helpers = array ('Html', 'Form', 'Time', 'Js','Session');


	public function contracts(){



	}

	 /**
      * Función que obtiene las publicaciones
      * @return JsonString String con los datos en formato Json
      */
	  public function getContracts(){

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
          $number = 10;

          /**
           * Variable que contiene los filtros de privacidad
           * @var String
           */
          $privacyFilters = $this->requestAction(array('controller'=>'Posts', 'action'=>'getPrivacyFilters',$userId));

          /**
           * variable que contiene el resultado de la consulta de posts
           * @var Array
           */
          $posts = $this->Post->find('all',
          	Array(
          		'order'       =>  Array('Post.id DESC'),
          		'limit'       =>  $number, 
          		'offset'      =>  $from,
          		'recursive'   => 3,
                /**
                 * Configuramos las opciones de privacidad
                 */
                'conditions'  => Array($privacyFilters,'PostType.name'=> 'contracts')
                )
          	);

          /**
           * Se imprimen los resultados en tipo Json
           */
          echo json_encode($posts);

      }

      /**
       * Función que inserta un link del secop relacionado con un post de tipo contratp
       */
      public function insertSecopLink($link = null,$postId = null){

      	$this->SecopLink->Create();

      	return $this->SecopLink->save(Array('link'=> $link, 'posts_id'=>$postId));

      }

      /**
       * Función que actualiza el link del secop de un contrato
       */
      public function updateSecopLink($post = null){

        $this->SecopLink->id = $post['secoplinkid'];
        $this->SecopLink->saveField('link',$post['secoplink']);

      }

        /**
         * Función que edita una publicación
         */
        public function editPost(){

          $this->autoRender = false;

          $post = $this->request->data;

          if($this->Post->save($post)) {
              
              /**
               * Actualizamos el link del secop asociado al post
               */
              $this->updateSecopLink($post);

              /**
               * Consultamos el post acutalizado
               */
              $post = $this->Post->find('first',array('conditions'=>array('Post.id'=>$post['id']),'recursive' => 2 ));

              echo json_encode(array('success'=>true,'post'=>$post));

          }else{
    
              echo json_encode(array('success'=>false));
          }

        }

        /**
         * Función que obtiene un post por su id
         * @return JsonString post
         */
        public function getPostById(){

            $this->autoRender = false;

            $postId = $this->request->data['postId'];

            $post = $this->Post->find('first',array('conditions'=>array('Post.id'=>$postId),'recursive' => 2 ));

            echo json_encode($post);
        }





        /**
         * Función utilizada para borrar un post
         * @return JsonString Resultado
         */
        public function deletePost(){

          $this->autoRender = false;

          $postId = $this->request->data['postId'];

          if ($this->Post->delete($postId)) {
              

                $userId = $this->Auth->user('id'); 
              
                $this->ResourceManager->deleteResources($userId, 'post', $postId);
                
                

              echo json_encode(Array('success'=>true));          

          }else{
              echo json_encode(Array('success'=>false));          
          }
         
  

        }

        /**
         * Función que agrega una nueva publicación
         */
        public function addPublication(){
            
             $this->autoRender = false;

            /**
             * Variable que contiene los datos del nuevo post
             * @var Array
             */
            $newPostData = $this->request->data;
            
            /**
             * Titulo del post
             * @var String
             */
            $title = $newPostData['title'];
            
            /**
             * Contenido del post
             * @var Srtring
             */
            $content = $newPostData['content'];
            
            /**
             * Variable que contiene el link de l secop
             * @var [type]
             */
            $secopLink = $newPostData['secoplink'];

            /**
             * Visibilidad
             * @var Int
             */
            $privacyId = $newPostData['privacyId'];

            /**
             * Identificador del usuario
             * @var Int
             */
            $userId = $this->Auth->user('id');

            /**
             * Uso de la función newPost del modelo de posts
             */

            $addedPost = $this->Post->newPost($title, $content, $privacyId, $userId, 'contracts');

            //si se ha guardado el post correctamente
            if ($addedPost) {
                  
               $postId = $addedPost['Post']['id'];

               $this->insertSecopLink($secopLink, $postId);

               /**
                * Guardamos los recursos usando el componente ResourceManager y lo configuramos
                */
                $this->ResourceManager->saveResources($userId, 'post', 'attachment', $postId);
                
                $post = $this->Post->find('first',array('conditions'=>array('Post.id'=>$this->Post->id),'recursive' => 2 ));

                echo json_encode($post);
            }
        }

      public function beforeFilter() {
      	parent::beforeFilter();

      }        

  }

  ?>