<?php
App::uses('AppModel', 'Model');
/**
 * FriendRequest Model
 *
 * @property RequestUser $RequestUser
 * @property RequestedUser $RequestedUser
 */
class FriendRequest extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'request_user_id',
	
		),

		'RequestedUser' => array(
			'className' => 'RequestedUser',
			'foreignKey' => 'requested_user_id',
		)
	);
}
