<?php

class Roles extends AclAppModel
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
			'foreignKey' => 'role_id'
		),
	);


    
}
