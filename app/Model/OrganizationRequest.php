<?php
App::uses('AppModel', 'Model');
/**
 * OrganizationRequest Model
 *
 * @property Organizations $Organizations
 * @property Users $Users
 */
class OrganizationRequest extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Organizations' => array(
			'className' => 'Organizations',
			'foreignKey' => 'organizations_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'users_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
