<?php
App::uses('AppModel', 'Model');
/**
 * ContractHistorial Model
 *
 * @property InterestContracts $InterestContracts
 */
class ContractHistorial extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'InterestContracts' => array(
			'className' => 'InterestContracts',
			'foreignKey' => 'interest_contracts_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
