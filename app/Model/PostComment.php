<?php
    class PostComment extends AppModel 
    {
        var $name = 'PostComments';
    

	    /**
		 * Relaciones 
		 * @var Array
		 */
		public $belongsTo = array(

			/**
			 * Relación con Post
			 * @var Array
			 */
			'Post' => array(
				'className' => 'Post',
				'foreignKey' => 'post_id'
			),

			/**
			 * Relación con coment
			 * @var Array
			 */
			'Coment' => array(
				'className' => 'Coment',
				'foreignKey' => 'comment_id',
				
			)
		);


    }




