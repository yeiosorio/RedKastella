<?php 
      
    /**
     * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
     * Publicaciones
     */
    class PublicationsController extends AppController
    {

        var $uses = array('Publication', 'Comment', 'User', 'Person', 'Chapter', 'Privacy', 'Post');
        
        // Declaracion de helpers que ayudaran en la creacion de formularios
        public $helpers = array ('Html', 'Form', 'Time', 'Js','Session');
        
        // Declaracion de los componentes para inicio de sesion
        public $components = array('Session','RequestHandler','Paginator','ResourceManager.ResourceManager');
        
        function beforeFilter(){     
            
            $this->Auth->allow('add', 'logout','viewPublic','allPublications','getPostById','getPublications','getPublicationsPublic','getPostPublicById');
            
        } 

       /**
        * Función que retornara los contratos de interes actuales 
        */
        public function interestContracts(){


        }

        /**
         * Función que acciona la vista de las publicaciones definidas como novedades
         */
        public function allPublications($sharedPostId = null){

            /**
             * Si el usuario no esta logeado lo llevamos a la parte publica
             * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
             * 2016-09-27 11:44:05
             */
             
            if(!$this->Session->check('Auth.User')){
                

              $this->redirect($this->Auth->redirect(array('controller'=>'Publications', 'action' => 'viewPublic',$sharedPostId)));

            }       
            

            if($sharedPostId != null){



              $post = $this->getPostByIdForLogged($sharedPostId);

              $post = $post['Post'];

              $words = explode(" ", $post['content']);

              $post['content'] = implode(" ",array_splice($words,0,15));

              $post['content'] = $post['content'].'...';

              $sharedInfo = $post['title']. ' - ' . $post['content'];

              $this->set(compact('sharedPostId','sharedInfo'));            

            }

        }

        /**
         * Función que acciona la vista de las publicaciones definidas 
         * como novedades de manera publica sin restriccion de autenticacion
         */
        public function viewPublic($sharedPostId = null){
        
          
          /**
           * Si el usuario esta logeado lo llevamos a la parte privada
           * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
           * 2016-09-27 09:08:20
           */
          if($this->Session->check('Auth.User')){


            $this->redirect($this->Auth->redirect(array('controller'=>'Publications', 'action' => 'allPublications',$sharedPostId)));

          }                



          $this->layout = 'no_login';

          if($sharedPostId != null){


            $post = $this->getPostByIdForWhts($sharedPostId);

            $post = $post['PostPublic'];


            $words = explode(" ", $post['content']);

            $post['content'] = implode(" ",array_splice($words,0,15));

            $post['content'] = $post['content'].'...';

            $sharedInfo = $post['title']. ' - ' . $post['content'];

            $this->set(compact('sharedPostId','sharedInfo'));            
          }

        }


        
        /**
         * Función que obtiene las publicaciones
         * @return JsonString String con los datos en formato Json
         */
        public function getPublicationsPublic(){

          $this->autoRender = false;

          $this->loadModel('PostPublic');

          $userId = 421;

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


          /**
           * Variable que contiene los filtros de privacidad
           * @var String
           */
          $privacyFilters = $this->requestAction(array('controller'=>'Posts', 'action'=>'getPrivacyFiltersPublic',$userId));


          /**
           * variable que contiene el resultado de la consulta de posts
           * @var Array
           */
          $posts = $this->PostPublic->find('all',
                  Array(
                        'order'       =>  Array('PostPublic.modified ASC'),
                        'limit'       =>  $number, 
                        'offset'      =>  $from,
                        'recursive'   => 3,
                        /**
                         * Configuramos las opciones de privacidad y el tipo de post 
                         */
                        'conditions'  => Array($privacyFilters,'PostType.name'=> 'publications')
                    )
          );

          /**
           * Se imprimen los resultados en tipo Json
           */
          echo json_encode($posts);

        }


        
        /**
         * Función que obtiene las publicaciones
         * @return JsonString String con los datos en formato Json
         */
        public function getPublications(){

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
                        'order'       =>  Array('Post.created DESC'),
                        'limit'       =>  $number, 
                        'offset'      =>  $from,
                        'recursive'   => 3,
                        /**
                         * Configuramos las opciones de privacidad y el tipo de post 
                         */
                        'conditions'  => Array($privacyFilters,'PostType.name'=> 'publications')
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
          $post['content'] = htmlentities( $post['content'] );
          $post['title'] = htmlentities( $post['title'] );

          // $this->Post->id = $post['postId'];

          // $editInfo = array('title'=>$post['title'], 'content'=>$post['content']); 
          
          if($this->Post->save($post)) {
              
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
        public function getPostByIdForLogged($id){
            
            $postId = $id;

            $this->loadModel('Post');

            $post = $this->Post->find('first',array('conditions'=>array('Post.id'=>$postId),'recursive' => 2 ));

            return $post;
        }


        /**
         * Función que obtiene un post por su id
         * @return JsonString post
         */
        public function getPostByIdForWhts($id){

       
            
            $postId = $id;

            $this->loadModel('PostPublic');

            $post = $this->PostPublic->find('first',array('conditions'=>array('PostPublic.id'=>$postId),'recursive' => 2 ));

            return $post;
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
         * Función que obtiene un post por su id
         * @return JsonString post
         */
        public function getPostPublicById(){

            $this->autoRender = false;

            $this->loadModel('PostPublic');
            
            
            $postId = $this->request->data['postId'];

            $post = $this->PostPublic->find('first',array('conditions'=>array('PostPublic.id'=>$postId),'recursive' => 2 ));

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
         * Julián Andrés Muñoz Cardozo
         * 2016-09-01 17:33:21
         * @param [type] $title     [description]
         * @param [type] $content   [description]
         * @param [type] $privacyId [description]
         */
        public function addPost($title, $content, $privacyId){
 
            /**
             * Identificador del usuario
             * @var Int
             */
            $userId = $this->Auth->user('id');

            /**
             * Uso de la función newPost del modelo de posts
             */

            $addedPost = $this->Post->newPost($title, $content, $privacyId, $userId, 'publications');

            return $addedPost;

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
            $title = htmlentities( $newPostData['title'] );
            
            /**
             * Contenido del post
             * @var Srtring
             */
            $content = htmlentities( $newPostData['content'] );
            
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

            $addedPost = $this->Post->newPost($title, $content, $privacyId, $userId, 'publications');

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