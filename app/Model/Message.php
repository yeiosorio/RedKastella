<?php
App::uses('AppModel', 'Model');
/**
 * Message Model
 *
 * @property Users $Users
 * @property MessagesTypes $MessagesTypes
 */
class Message extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'users_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

	 public $hasMany = array(
        'MessagesUsers' => array(
            'className' => 'MessagesUser',
			'foreignKey' => 'messages_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
        )
    );
}
