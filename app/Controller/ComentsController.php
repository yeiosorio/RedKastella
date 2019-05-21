<?php 
    class ComentsController extends AppController
    {
       
       public $name = 'Coments';

       public $uses = array('Coment','PostComment');

       public $components = array('ResourceManager.ResourceManager');
       
       /**
        * Funcion que agrega un comentario a un post
        */
       public function addComment(){

       	$this->autoRender = false;

        /**
         * variable que contiene los datos que vienen por el método post
         * @var Array
         */
       	$data = $this->request->data; 

        /**
         * Comentario
         * @var String
         */
       	$comment = htmlentities( $data['comment'] );
       	
        /**
         * Identificador del post
         * @var Int
         */
        $postId = $data['postId'];

        /**
         * Identificador del usuario
         * @var [type]
         */
       	$userId = $this->Auth->user('id');

        /**
         * Creación de una nueva entidad de tipo comentario
         */
       	$this->Coment->Create();


        /**
         * Guardado del nuevo comentario
         */
       	if ($this->Coment->save(Array('comment'=>$comment, 'users_id'=>$userId))) {

          /**
           * Identificador del nuevo comentario
           * @var Int
           */
           $commentId = $this->Coment->id;

           /**
            * guardamos su relación con el post
            */
           if($this->makePostRelation($postId,$commentId)){

            /**
             * Obtenemos la información del post
             * @var [type]
             */
            $comment = $this->Coment->find('first',Array('conditions'=>Array('Coment.id'=>$commentId)));

            /**
              * Guardamos los recursos usando el componente ResourceManager y lo configuramos
              */
             
              $this->ResourceManager->saveResources($userId, 'post_comment', 'attachment', $commentId);

            /**
             * Variable que contiene el numero de comentarios el cual se obtiene de la función getNumberOfCommentsFromPost
             * @var Int
             */
            $numberOfCommentsFromPost = $this->getNumberOfCommentsFromPost($comment['PostComment'][0]['post_id']);

             /**
             * Obtenemos la información del post
             * @var [type]
             */
            $comment = $this->Coment->find('first',Array('conditions'=>Array('Coment.id'=>$commentId)));

           /**
            * escribimos el String tipo Json
            */
            echo json_encode(Array('success'=>true,'Comment'=>$comment,'numberComments'=> $numberOfCommentsFromPost));

          } else {
          
          /**
            * escribimos el estado sin éxito
            */
            echo json_encode(Array('success'=>false));
          }       			

        }else{

          /**
            * escribimos el estado sin éxito
            */
         echo json_encode(Array('success'=>false));
       }

      }


      /**
       * Función que edita un comentario
       * @return JsonString Estado, comentario editado
       */
      public function editComment(){

        $this->autoRender = false;

        /**
         * Obtenemos los datos del comentario que viene por post
         * @var [type]
         */
        $comment = $this->request->data;

        /**
         * Identificador del comentario
         * @var Int
         */
        $commentId = $comment['commentId'];

        /**
         * Contenido del comentario
         * @var String
         */
        $commentContent = htmlentities( $comment['comment'] );

        /**
         * Asignamos el identificador del comentario
         * @var Int
         */
        $this->Coment->id = $commentId;


        /**
         * Guardamos los cambios, si se ha guardado
         */
        if($this->Coment->save(Array('comment'=>$commentContent))){

            /**
             * Mandamos el resultado con estado de éxito y el comentario editado
             */
            echo json_encode(Array('success'=>true, 'Comment'=>$this->getCommentById($commentId)));
        
          }else{

            /**
             * Mandamos estado sin éxito
             */
            echo json_encode(Array('success'=>false));
        }

      }

      /**
       * Función que obtiene un comentario por su identificador
       * @param  Int $commentId Identificador
       * @return Array          Datos del comentario
       */
      public function getCommentById($commentId = null){

          return $this->Coment->find('first',Array('conditions'=>Array('Coment.id'=> $commentId)));

      }

      /**
       * Función que obtiene un comentario por su identificador
       * @param  Int $commentId Identificador
       * @return Array          Datos del comentario
       */
      public function ajaxGetCommentById(){

          $this->autoRender = false;

          $commentId = $this->request->data['commentId']; 
          
          $comment = $this->Coment->find('first',Array('conditions'=>Array('Coment.id'=> $commentId)));  

          echo json_encode($comment); 

      }


      /**
       * Función que obtiene los comentarios de un identificador de un post
       * @return [type] [description]
       */
      public function getCommentsFromPostId(){

        $this->autoRender = false;

        /**
         * identificador del comentario
         * @var Int
         */
        $postId = $this->request->data['postId'];
        

        /**
         * Comentarios del post
         * @var Array
         */
        $comments = $this->Coment->PostComment->find('all',Array('recursive'=>2, 'conditions'=> Array('PostComment.post_id'=>$postId)));


        echo json_encode($comments);

      }

       /**
        * Función que elimina un comentario de un post
        * @return JsonString 
        */
       public function deleteComment(){

       	$this->autoRender = false;

        $userId = $this->Auth->user('id');
 
        /**
         * identificador del comentario
         * @var Int
         */
       	$commentId = $this->request->data['commentId'];
        
        /**
         * Buscamos la información del comentario por su identificador
         * @var Array
         */
        $comment = $this->Coment->find('first',Array('conditions'=>Array('Coment.id'=>$commentId)));

        /**
         * Borramos el comentario, si fue borrado devolvemos estado exitoso y el numero de comentarios actual, de lo contrario devolvemos estado sin éxito
         */
       	if($this->Coment->delete($commentId)) {
			   
           /**
            * Variable que contiene el numero de comentarios el cual se obtiene de la función getNumberOfCommentsFromPost
            * @var Int
            */
           
           $this->ResourceManager->deleteResources($userId, 'post_comment', $commentId);


           $numberOfCommentsFromPost = $this->getNumberOfCommentsFromPost($comment['PostComment'][0]['post_id']);
			     
           /**
            * escribimos el String tipo Json
            */
           echo json_encode(Array('success'=>true,'numberComments'=>$numberOfCommentsFromPost));
       	
        }else{

         /**
          * escribimos el estado sin éxito
          */
         	echo json_encode(Array('success'=>false));

       	}

       }


      /**
       * Función que obtiene el numero de comentarios de un post 
       * @param  Int $postId Identificador del post
       * @return Int         numero de comentarios
       */
      public function getNumberOfCommentsFromPost($postId = null){

          $this->autoRender = false;

          /**
           * retornamos el conteo de comentarios por el identificador del post
           */
          return $this->PostComment->find('count',Array('conditions'=>Array('post_id'=>$postId)));
      }

       /**
        * Función que establece la relación entre un post y un comentario
        * @param  Int $postId    identificador del post
        * @param  Int $commentId identificador del comentario
        * @return Boolean        retorna true en caso de éxito, falso en caso de fallo
        */
       public function makePostRelation($postId = null, $commentId = null){

          /**
           * Inicializamos la nueva entidad de relación
           */
       		$this->PostComment->Create();

          /**
           * Guardado de la nueva relación
           */
       		if($this->PostComment->save(Array('post_id'=>$postId, 'comment_id' => $commentId))){

       			return true;

       		}else{

       			return false;

       		}

       }

    }

?>