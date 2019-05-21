<?php

class PostLike extends AppModel 
{
	var $name = 'PostLikes';


	/**
	 * Relación con Likes
	 * @var array
	 */
	public $belongsTo = array(
        'Like' => array(
            'className' => 'Like',
           	'foreignKey'   => 'likes_id'
        )
    );


}	