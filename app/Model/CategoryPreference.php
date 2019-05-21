<?php
    
    class CategoryPreference extends AppModel 
    {
        var $name = 'CategoryPreferences';
    

        public $belongsTo = array(
        'ContractCategory' => array(
            'className' => 'ContractCategory',
            'foreignKey' => 'contract_categories_id'
	       )
	    );


    }