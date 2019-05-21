<?php
    class Role extends AppModel 
    {
        var $name = 'Roles';
        
        var $hasMany = array('Person');
    }
?>