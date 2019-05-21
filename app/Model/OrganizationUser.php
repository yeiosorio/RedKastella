<?php
    class OrganizationUser extends AppModel 
    {

        public $name = 'OrganizationUsers';

        /**
         * Relación
         * @var Array
         */
        public $belongsTo = array(

        	/**
        	 * Relación con usuarios
        	 */
        	'User' => array (
                    'className' => 'User',
                    'foreignKey'   => 'user_id'
        	), 

        	/**
        	 * Relación con Organización
        	 */
        	'Organization' => array (
                    'className' => 'Organization',
                    'foreignKey'   => 'organization_id'
        	)

        );

    }
?>