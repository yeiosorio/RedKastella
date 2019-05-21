<?php
    
    class ContractCategory extends AppModel 
    {
        var $name = 'ContractCategories';
    

        public $belongsTo = array(
        'ContractProvider' => array(
            'className' => 'ContractProvider',
            'foreignKey' => 'contract_providers_id'
	       )
	    );


    }