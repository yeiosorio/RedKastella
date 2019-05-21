<?php 

class ResourceFileType extends ResourceManagerAppModel {


	/**
	 * Relación con recursos con opcion de ordenamiento
	 * @var array
	 */
	public $hasMany = array(
        'ResourceExtension' => array(
            'className' => 'ResourceExtension',
           	'foreignKey'   => 'resource_file_types_id'
        )
    );

}

