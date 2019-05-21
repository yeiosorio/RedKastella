<?php
App::uses('AppModel', 'Model');
/**
 * NotificationsInterestContract Model
 *
 * @property ContractHistorials $ContractHistorials
 * @property UserInterestContracts $UserInterestContracts
 */
class NotificationsInterestContract extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ContractHistorials' => array(
			'className' => 'ContractHistorials',
			'foreignKey' => 'contract_historials_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'UserInterestContracts' => array(
			'className' => 'UserInterestContracts',
			'foreignKey' => 'user_interest_contracts_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
