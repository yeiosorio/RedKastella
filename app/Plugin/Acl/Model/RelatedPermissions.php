<?php

class RelatedPermissions extends AclAppModel
{



	/**
	 * Relaciones 
	 * @var Array
	 */
	public $belongsTo = array(

		/**
		 * Relación con PermissionRoles de Padres
		 * @var Array
		 */
		'PermissionRolesParent' => array(
			'className' => 'Acl.PermissionRoles',
			'foreignKey' => 'parent_permission_id'
		),

		/**
		 * Relación con PermissionRoles de hijos
		 * @var Array
		 */
		'PermissionRolesChild' => array(
			'className' => 'Acl.PermissionRoles',
			'foreignKey' => 'child_permission_id',
			
		)
	);



    
}

