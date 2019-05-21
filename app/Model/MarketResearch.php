<?php
    class MarketResearch extends AppModel 
    {
        var $name = 'MarketResearches';
        //var $belongsTo = array('User', 'Person');
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
         'Organization'
        );
        var $hasMany = array(
        'EstimateResearch'
        );
        
        public $validate = array(
            'user_id' => array(
                'rule' => 'notBlank'
            ),
            'content_research' => array(
                'rule' => 'notBlank'
            ),
            'privacy_id' => array(
                'rule' => 'notBlank'
            )
        );
        
        public function beforeSave($options = array()) {
            
            $this->data[$this->alias]['content_research'] = str_replace("\n", "<br>", $this->data[$this->alias]['content_research']);
            //return true;
            return parent::beforeSave($options);
        }
    }
?>