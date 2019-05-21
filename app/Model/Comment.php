<?php
    class Comment extends AppModel 
    {
        var $name = 'Comments';
        
        var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
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
        'Person' => array(
            'className' => 'Person',
            'foreignKey' => 'user_id',
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
        'Publication'
        );
        
        public $validate = array(
            
            'content_comment' => array(
                'required' => array(
                    'rule' => array('notBlank'),
                    'message' => 'A comment is required'
                )
            )
            
        );
        
        public function beforeSave($options = array()) {
            if ($this->isNewRecord) {
                //$nuevafecha = strtotime ( '-5 hour' , strtotime ( $this->created ) ) ;
                //$this->created = date ('Y-m-d (H:i:s)', $nuevafecha);
                //$this->created = new CDbExpression('NOW()');
            }

            //$this->modified = new CDbExpression('NOW()');

            return parent::beforeSave();
        }
    }
?>