<?php
App::uses('AppModel', 'Model');
/**
 * Friend Model
 *
 * @property FromFriend $FromFriend
 * @property ToFriend $ToFriend
 */
class Friend extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed


	     public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'to_friend_id'
           )
        );

}
