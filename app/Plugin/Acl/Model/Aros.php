<?php

class Aros extends AclAppModel
{


	/**
	 * Relaciones 
	 * @var Array
	 */
	public $hasMany = array(

		/**
		 * Relación con PermissionRoles
		 * @var Array
		 */
		'PermissionRoles' => array(
			'className' => 'Acl.PermissionRoles',
			'foreignKey' => 'aro_id'
		),
	);
    
}
