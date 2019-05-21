<?php
App::uses('AppModel', 'Model');
/**
 * UserInterestContract Model
 *
 * @property Users $Users
 * @property InterestContracts $InterestContracts
 */
class UserInterestContract extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Users' => array(
			'className' => 'Users',
			'foreignKey' => 'users_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'InterestContracts' => array(
			'className' => 'InterestContracts',
			'foreignKey' => 'interest_contracts_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


	/**
	 * Relación con PostComemnt con opcion de ordenamiento
	 * @var array
	 */
	public $hasMany = array(
        
        'NotificationInterestContract' => array(
            'className' => 'NotificationsInterestContract',
           	'foreignKey'   => 'user_interest_contracts_id'
        ),
    );

}
