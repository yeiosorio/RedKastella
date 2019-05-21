<?php 

    class MarketResearchesController extends AppController
    {
        
        public $name = 'MarketResearches';

        var $uses = array('EstimateResearch', 'Mailbox','Post');

        // Declaracion de helpers que ayudaran en la creacion de formularios
        public $helpers = array ('Html', 'Form', 'Time', 'Js','Session');
        
        
        public $components = array('Session','RequestHandler','Paginator','ResourceManager.ResourceManager');

        
        public function beforeFilter(){

            parent::beforeFilter();

        }
        

        /**
         * Función que muestra la vista de Estudios de Mercado 
         */
        public function marketResearches(){
           

	      }

      
        /**
         * Función que obtiene las publicaciones
         * @return JsonString String con los datos en formato Json
         */
        public function getMarketResearches(){

          $this->autoRender = false;

          $userId = $this->Auth->user('id');

          /**
           * desde que número de resultados devolvera items
           * @var Int
           */
          $from = $this->request->data['from'];

          $searchTerm = $this->request->data['searchTerm'];


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


          // Condicion por termino de busqueda
          if ($searchTerm != '' ) {
              
                $conditions = Array($privacyFilters,'PostType.name'=> 'market_researches', "Post.title LIKE '%".$searchTerm."%'");
          
          }else{
        
                $conditions = Array($privacyFilters,'PostType.name'=> 'market_researches');
          }




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
                         * Configuramos las opciones de privacidad y el tipo de post 
                         */
                        'conditions'  => $conditions
                    )
          );

          /**
           * Se imprimen los resultados en tipo Json
           */
          echo json_encode($posts);

        }


        /**
         * Función que edita una publicación
         */

        public function editPost(){

          $this->autoRender = false;

          $post = $this->request->data;

          $userId = $this->Auth->user('id');

          if($this->Post->save($post)) {
               
               /**
                * Guardamos los recursos usando el componente ResourceManager y lo configuramos
                */
                $this->ResourceManager->saveResources($userId, 'post', 'attachment', $post['id']);
              
              $post = $this->Post->find('first',array('conditions'=>array('Post.id'=>$post['id']),'recursive' => 3 ));

              echo json_encode(array('success'=>true,'post'=>$post));

          }else{
    
              echo json_encode(array('success'=>false));
          }

        }

        /**
         * Función que elimina un recurso fisicamente por su Identificador
         */
        public function deleteResource(){

          $this->autoRender = false;

          $id = $this->request->data['resourceId'];

          $userId = $this->Auth->user('id');

          if($this->ResourceManager->deleteResourceById($id, $userId)){

                echo json_encode(Array('success'=>true));

          }else{
                echo json_encode(Array('success'=>false));
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

            $addedPost = $this->Post->newPost($title, $content, $privacyId, $userId, 'market_researches');

            //si se ha guardado el post correctamente
            if ($addedPost) {
                  
               $postId = $addedPost['Post']['id'];

               /**
                * Guardamos los recursos usando el componente ResourceManager y lo configuramos
                */
                $this->ResourceManager->saveResources($userId, 'post', 'attachment', $postId);

                // $this->set('postId',$postId);
                
                $post = $this->Post->find('first',array('conditions'=>array('Post.id'=>$this->Post->id),'recursive' => 2 ));

                echo json_encode($post);
            }
        } 


               
    }


?>