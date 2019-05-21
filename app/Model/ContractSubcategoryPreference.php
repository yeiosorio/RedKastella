<?php
App::uses('AppModel', 'Model');
/**
 * ContractSubcategoryPreference Model
 *
 * @property ContractPreferences $ContractPreferences
 * @property ContractSubcategories $ContractSubcategories
 */
class ContractSubcategoryPreference extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ContractPreferences' => array(
			'className' => 'ContractPreferences',
			'foreignKey' => 'contract_preferences_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ContractSubcategories' => array(
			'className' => 'ContractSubcategories',
			'foreignKey' => 'contract_subcategories_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
