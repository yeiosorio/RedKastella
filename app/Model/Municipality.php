<?php
    class Municipality extends AppModel 
    {
        var $name = 'Municipalities';


  //       /**
  //        * Relación con Departments
  //        * @var Array
  //        */
		public $belongsTo = array(
		
			'Department' => array(
				'className' => 'Department',
				'foreignKey' => 'department_id'
			)
		);

    }
?>