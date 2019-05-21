<?php
    
    class ContractProvider extends AppModel 
    {
        var $name = 'ContractProviders';
    

        public $hasMany = array(
        'ContractCategory' => array(
            'className' => 'ContractCategory',
            'foreignKey' => 'contract_providers_id'
       )
    );


    }