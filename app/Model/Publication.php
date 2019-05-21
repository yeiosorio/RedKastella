<?php
    class Publication extends AppModel 
    {
        var $name = 'Publications';
        
        //http://book.cakephp.org/2.0/es/models/data-validation.html
        public $validate = array(
            'user_id' => array(
                'rule' => 'notBlank'
            ),
            'title_publication' => array(
                'rule' => 'notBlank'
            ),
            'content_publication' => array(
                'rule' => 'notBlank'
            ),
            'privacy_id' => array(
                'rule' => 'notBlank'
            )
        );
        
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
            )
        );
        
        public function beforeSave($options = array()) {
            
            $this->data[$this->alias]['content_publication'] = str_replace("\n", "<br>", $this->data[$this->alias]['content_publication']);
            //return true;
            return parent::beforeSave($options);
        }
    }
?>