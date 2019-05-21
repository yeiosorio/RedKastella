<?php

class PermissionRoles extends AclAppModel
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
		'Aros' => array(
			'className' => 'Acl.Aros',
			'foreignKey' => 'aro_id'
		),

		/**
		 * Relación con PermissionRoles de hijos
		 * @var Array
		 */
		'Acos' => array(
			'className' => 'Acl.Acos',
			'foreignKey' => 'aco_id',
			'order' => 'Acos.order ASC'
			
		),

		/**
		 * Relación con PermissionRoles de hijos
		 * @var Array
		 */
		'Roles' => array(
			'className' => 'Acl.Roles',
			'foreignKey' => 'role_id',
			
		)
	);

}
