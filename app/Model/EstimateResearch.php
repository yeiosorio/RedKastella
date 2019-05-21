<?php
    class EstimateResearch extends AppModel 
    {
        var $name = 'EstimateResearches';
        
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
        'MarketResearch'
        );
        
        public $validate = array(
            'user_id' => array(
                'rule' => 'notBlank'
            ),
            'content_estimate' => array(
                'rule' => 'notBlank'
            ),
            'market_research_id' => array(
                'rule' => 'notBlank'
            )
        );
        
    }
?>