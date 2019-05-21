<?php

class Like extends AppModel 
{
	var $name = 'Likes';
	 public $actsAs = array('Containable');


	/**
	 * Relación con PostLike
	 * @var array
	 */
	public $hasMany = array(
        'PostLike' => array(
            'className' => 'PostLike',
           	'foreignKey'   => 'likes_id'
        )
    );



}	