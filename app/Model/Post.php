<?php

class Post extends AppModel 
{
	var $name = 'Posts';

	
	public $belongsTo = array(
	
		'PostType' => array(
			'className' => 'PostType',
			'foreignKey' => 'post_types_id'
		),

		'Privacy' => array(
			'className' => 'Privacy',
			'foreignKey' => 'privacies_id'
		),

		'User' => array(
			'className' => 'User',
			'foreignKey' => 'users_id'
		),

	);

	/**
	 * Relación con recursos con opcion de ordenamiento
	 * @var array
	 */
	public $hasMany = array(
        'Resource' => array(
            'className' => 'ResourceManager.Resource',
           	'foreignKey'   => 'entity_id',
           	'order' => 'Resource.resource_extensions_id ASC'
        ),
        
        'PostComment'=>Array(
        	'className' => 'PostComment',
        	'foreignKey' => 'post_id'
        ),
        
        'SecopLink' => Array(
        	'className' => 'SecopLink',
        	'foreignKey' => 'posts_id'
        )
    );


    /**
	 * Método constructor de la clase
	 * @param boolean $id    [description]
	 * @param [type]  $table [description]
	 * @param [type]  $ds    [description]
	 */
	public function __construct($id = false, $table = null, $ds = null) {
		/**
		 * Campo virtual personalizado que contiene el número de likes de un post 
		 * @var String
		 */
	    $this->virtualFields['likes'] = $this->getLikes();
		
		/**
		 *  cmapo que define si al usuario actual le gusta el post consultado 
		 */
	    $this->virtualFields['ilike'] = $this->getIlike(CakeSession::read("Auth.User.id"));

	    parent::__construct($id, $table, $ds);
	}


	/**
	 * Campo virtual que define si el usuario logueado le gusta o no un post
	 * retorna 1 si el usuario actual le gusta el post actual de lo contrario 0
	 */
	public function getIlike($userId){
		
		return 'SELECT COUNT(likes.id) FROM likes WHERE likes.users_id = '.$userId.' AND likes.id in(SELECT post_likes.likes_id FROM post_likes WHERE post_likes.posts_id = Post.id )';

	}
	/**
	 * Función que retorna el número de likes
	 * @return Int número de likes
	 */
	public function getLikes(){

		return 'SELECT COUNT(post_likes.id) as likes FROM post_likes where post_likes.posts_id = Post.id';

	}


	/**
	 * Función que agrega un nuevo post
	 * @param  String 	$title        Título del post
	 * @param  String 	$content      Contenido del post
	 * @param  Int 		$privacyId    Identificador de la visibilidad
	 * @param  Int 		$userId       Identificador del usuario
	 * @param  String 	$postTypeName Nombre del tipo de post
	 * @return Boolean             	  Resultado
	 */
	public function newPost($title = null, $content = null, $privacyId = null, $userId = null, $postTypeName = null){
		

		   $this->create();
           /**
            * Si se guardo con éxito retornamos verdadero
            */
           if($this->save(Array(
           			'title'			=> $title,
           			'content'		=> $content,
           			'privacies_id'	=> $privacyId,
           			'users_id'		=> $userId,
           			'post_types_id'	=> $this->getPostTypeId($postTypeName))
           )){
           		
           	return  $this->find('first',Array('conditions'=>array('Post.users_id'=> $userId),'order' => array('Post.id' => 'DESC')));
           	
           
           }else{
           
           		return false;
     	    }
	}

	/**
	 * Función que retorna el indentificador de tipo de post por nombre
	 * @param  String $name nombre del post
	 * @return Int       Identificador
	 */
	public function getPostTypeId($name = null){

		$postType = $this->PostType->find('first',Array('conditions'=>array('name'=> $name)));

		return $postType['PostType']['id'];

	}

}

