<?php

class Acos extends AclAppModel
{

    	/**
	 * Relaciones 
	 * @var Array
	 */
	public $hasMany = array(

		/**
		 * Relación con PermissionRoles de Padres
		 * @var Array
		 */
		'PermissionRoles' => array(
			'className' => 'Acl.PermissionRoles',
			'foreignKey' => 'aco_id'
		),
	);
    
}

