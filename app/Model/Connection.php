<?php
    class Connection extends AppModel 
    {
        var $name = 'Connection';
        
        var $primaryKey = 'id';
        var $belongsTo = array('User');
        
        public $validate = array(
            
            
        );

        
        public function beforeSave($options = array()) 
        {
            
        }
    }
?>