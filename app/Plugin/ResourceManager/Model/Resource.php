<?php 

class Resource extends ResourceManagerAppModel {



	/**
	 * Relaciones 
	 * @var Array
	 */
	public $belongsTo = array(

		/**
		 * Relación con Post
		 * @var Array
		 */
		'Post' => array(
			'className' => 'PostType',
			'foreignKey' => 'entity_id'
		),

		/**
		 * Relación con ResourceExtension
		 * @var Array
		 */
		'ResourceExtension' => array(
			'className' => 'ResourceManager.ResourceExtension',
			'foreignKey' => 'resource_extensions_id',
			
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
		 * Campo virtual personalizado que contiene la ruta del archivo 
		 * @var String
		 */

	    $this->virtualFields['filePath'] = $this->getFilePath();

	    /**
	     * Campo vistual personalizado que contiene el nombre del archivo original con su extensión
	     */
	    $this->virtualFields['fileName'] = $this->getFileName();
	    
	    parent::__construct($id, $table, $ds);
	}


	/**
	 * Función que retorna el nombre del archivo original con su extensión
	 * @return String nombre
	 */
	public function getFileName(){

		return 'CONCAT(Resource.name,".",(SELECT resource_extensions.extension from resource_extensions where resource_extensions.id = Resource.resource_extensions_id))';

	}

	/**
	 * Función que obtiene la ruta fisica completa del archivo asociado
	 * @return String Ruta 
	 */
	public function getFilePath(){

		$serverUrl = Router::url('/', true);
		return 'CONCAT("'.$serverUrl.'", "resourcesFolder/" , CONCAT((SELECT users.user_folder FROM users where users.id =  Resource.users_id), "/", Resource.stored_file_name))';

	}

}