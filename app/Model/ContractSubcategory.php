<?php
App::uses('AppModel', 'Model');
/**
 * ContractSubcategory Model
 *
 * @property Categories $Categories
 */
class ContractSubcategory extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Categories' => array(
			'className' => 'ContractCategory',
			'foreignKey' => 'categories_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
