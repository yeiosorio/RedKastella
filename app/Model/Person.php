<?php
    class Person extends AppModel 
    {
        var $name = 'People';
        
        var $primaryKey = 'user_id';

        var $belongsTo = array('User', 'Organization', 'Role');

        var $hasMany = array(
            
        'MarketResearch' => array(
            'className' => 'MarketResearch',
            'foreignKey' => 'username',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '25',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
            ),
        'EstimateResearch' => array(
            'className' => 'EstimateResearch',
            'foreignKey' => 'username',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '25',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
            ),
        'Document' => array(
            'className' => 'Document',
            'foreignKey' => 'username',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '25',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
            ),
        'Publication' => array(
            'className' => 'Publication',
            'foreignKey' => 'username',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '25',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
            ),
        'Comment' => array(
            'className' => 'Comment',
            'foreignKey' => 'username',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '25',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
            )
        );
        
        public $validate = array(
            
            /*'id' => array(
                'rule' => 'notBlank',
                'message' => 'Identificacion no puede ser vacio'
            ),*/
            
            /* eslogan */
            
            /* occupation */
            
            'username' => array(
                'alphaNumeric' => array(
                    'rule' => 'alphaNumeric',
                    'required' => true,
                    'message' => 'Letters and numbers only'
                    ),
                'between' => array(
                    'rule' => array('between', 5, 20),
                    'message' => 'The username must be between 5 and 20 characters.'
                    ),
                'That username has already been taken'=>array(
                    'rule'=>'isUnique',
                    'message' => 'That username has already been taken.'
                    )
            ),
            
            /*'city' => array(
                'required' => array(
                    'rule' => array('notBlank'),
                    'message' => 'A city is required'
                )
            ),*/
            
            /*'state' => array(
                'required' => array(
                    'rule' => array('notBlank'),
                    'message' => 'A state is required'
                )
            )*/
            
        );

        
        public function beforeSave($options = array()) 
        {
            
        }
    }
?>