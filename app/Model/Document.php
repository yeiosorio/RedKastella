<?php
    class Document extends AppModel 
    {
        var $name = 'Documents';
        
        public $validate = array(
            'user_id' => array(
                'rule' => 'notBlank'
            ),
            'title_document' => array(
                'rule' => 'notBlank'
            ),
            'content_document' => array(
                'rule' => 'notBlank'
            )
        );
        
        var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'dependent' => true,
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
            
            $this->data[$this->alias]['content_document'] = str_replace("\n", "<br>", $this->data[$this->alias]['content_document']);
            //return true;
            return parent::beforeSave($options);
        }
        
        public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
             $qryCond = '1';
             if (isset($conditions['User.id'])) {
                 // ESTA SECCION ES PARA MI CARPETA
                $qryCond = '`User`.`id`='.$conditions['User.id'];
                //$qryCond = 'title LIKE \''.$conditions['Post.title LIKE'].'\'';
                 
                 
                //$sql = 'SELECT COUNT(*) as count FROM users as User WHERE '.$qryCond;  
            /*$sql1 = " SELECT `Publication`.`id` FROM `cdcsambd`.`users` AS `User` LEFT JOIN `cdcsambd`.`people` AS `Person` ON (`Person`.`user_id` = `User`.`id`) INNER JOIN `cdcsambd`.`publications` AS `Publication` ON (`Publication`.`user_id` = `User`.`id`) WHERE ".$qryCond; 
            $sql2 = " SELECT `Document`.`id` FROM `cdcsambd`.`users` AS `User` LEFT JOIN `cdcsambd`.`people` AS `Person` ON (`Person`.`user_id` = `User`.`id`) INNER JOIN `cdcsambd`.`documents` AS `Document` ON (`Document`.`user_id` = `User`.`id`) WHERE ".$qryCond;  
            $sql3 = " SELECT `MarketResearch`.`id` FROM `cdcsambd`.`users` AS `User` LEFT JOIN `cdcsambd`.`people` AS `Person` ON (`Person`.`user_id` = `User`.`id`) INNER JOIN `cdcsambd`.`market_researches` AS `MarketResearch` ON (`MarketResearch`.`user_id` = `User`.`id`) WHERE ".$qryCond;  */
            $sql=  "SELECT `Publication`.`id`, `User`.`id` FROM `publications` AS `Publication` INNER JOIN `users` AS `User` ON (`Publication`.`user_id` = `User`.`id`) WHERE ".$qryCond." AND `Publication`.`path_folder` is not NULL
                    UNION
                    SELECT `Document`.`id`, `User`.`id` FROM `documents` AS `Document` INNER JOIN `users` AS `User` ON (`Document`.`user_id` = `User`.`id`) WHERE ".$qryCond." AND `Document`.`path_folder` is not NULL
                    UNION
                    SELECT `MarketResearch`.`id`, `User`.`id` FROM `market_researches` AS `MarketResearch` INNER JOIN `users` AS `User` ON (`MarketResearch`.`user_id` = `User`.`id`) WHERE ".$qryCond." AND `MarketResearch`.`folder_user` is not NULL
                    UNION
                    SELECT `EstimateResearch`.`id`, `User`.`id` FROM `estimate_researches` AS `EstimateResearch` LEFT JOIN `market_researches` AS `MarketResearch` ON (`EstimateResearch`.`market_research_id` = `MarketResearch`.`id`) INNER JOIN `users` AS `User` ON (`EstimateResearch`.`user_id` = `User`.`id`) WHERE `EstimateResearch`.`user_id` = 50 AND `EstimateResearch`.`path_folder` is not NULL"; 
                 
                 $this->recursive = -1; 
                 
             }  
            else
            {
                // ESTA SECCION ES PARA EL MODULO CONTRATOS
                //echo "hey mira aqui";
                //print_r($conditions);
                /*
                $qryCond = '';
                if (isset($conditions['Document.privacy_id'])) 
                {
                    $qryCond = $qryCond.'`Document`.`privacy_id`='.$conditions['Document.privacy_id'];
                }
                if (isset($conditions['Document.user_id'])) 
                {
                    $qryCond = $qryCond.'`Document`.`user_id`='.$conditions['Document.user_id'];
                }
                if (isset($conditions['Person.organization_id'])) 
                {
                    $qryCond = $qryCond.'`Person`.`organization_id`='.$conditions['Person.organization_id'];
                }*/
                $this->useTable = false;
                $sql = "SELECT `Document`.`privacy_id`, `Document`.`user_id`,  `Person`.`organization_id` FROM `documents` AS `Document` INNER JOIN `people` AS `Person` ON (`Document`.`user_id` = `Person`.`user_id`) WHERE  ".$conditions[0]." ORDER BY `Document`.`modified` DESC"; 
                //$sql = "SELECT `Document`.`privacy_id`, `Document`.`user_id` FROM `cdcsambd`.`documents` AS `Document`"; 
                
                $this->recursive = 0;
            }
             
            
                 
             $results = $this->query($sql);
             /*$results1 = $this->query($sql1);
             $results2 = $this->query($sql2);
             $results3 = $this->query($sql3);*/
             //return $results1['count']+$results2['count']+$results3['count'];
             //return count($results1)+count($results2)+count($results3);
            return count($results);
        }
        
        /*public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
 
            $sql = "SELECT `User`.`id` FROM `usuarios` AS `User` LEFT JOIN `perfiles` AS `Profile` ON (`Profile`.`user_id` = `User`.`id`)
            WHERE 1 = 1"; 
            $results = $this->query($results);
            return count($results);
        }*/
        
        public function paginate($conditions, $fields, $order, $limit, $page=1, $recursive = null, $extra = array()) {
 
            $qryCond = '1';
             if (isset($conditions['User.id'])) {
                // PARA EL MODULO MI CARPETA
                //$qryCond = 'User.id=\''.$conditions['User.id'].'\'';
                $qryCond = 'User.id=\''.$conditions['User.id'].'\'';
                 
                //$qryCond = 'title LIKE \''.$conditions['Post.title LIKE'].'\'';
             
            
            /*$sql = 'SELECT `User`.`id`,`User`.`username`, `User`.`password`,`Profile`.`companyName`, `Profile`.`contactPerson`,`Profile`.`phone`, `Profile`.`fax`, `Profile`.`fullAddress`
            FROM `usuarios` AS `User`
            LEFT JOIN `profiles` AS `Profile` ON (`Profile`.`user_id` = `User`.`id`)
            WHERE 1 = 1 '; */
            
            $recursive = -1;
            
            // Mandatory to have
            $this->useTable = false;
            /*$sql1 = '';
            $sql2 = '';
            $sql3 = '';
            $sql4 = '';*/
            $sql= '';
            
            //$sql .= "Your custom query here just do not include limit portion";
            /*$sql1 .= "SELECT `User`.`id`, `User`.`username`, `Publication`.`id`, `Publication`.`folder_user`, `Publication`.`title_publication`, `Publication`.`content_publication`, `Publication`.`path_folder`, `Publication`.`created`, `Publication`.`modified`, `Publication`.`user_id` FROM `cdcsambd`.`users` AS `User` LEFT JOIN `cdcsambd`.`people` AS `Person` ON (`Person`.`user_id` = `User`.`id`) INNER JOIN `cdcsambd`.`publications` AS `Publication` ON (`Publication`.`user_id` = `User`.`id`) WHERE ".$qryCond."AND `Publication`.`path_folder` is not NULL "; 
            $sql2 .= "SELECT `User`.`id`, `User`.`username`, `Document`.`id`, `Document`.`folder_user`, `Document`.`title_document`, `Document`.`content_document`, `Document`.`path_folder`, `Document`.`created`, `Document`.`modified`, `Document`.`user_id`, `Document`.`link_secop` FROM `cdcsambd`.`users` AS `User` LEFT JOIN `cdcsambd`.`people` AS `Person` ON (`Person`.`user_id` = `User`.`id`) INNER JOIN `cdcsambd`.`documents` AS `Document` ON (`Document`.`user_id` = `User`.`id`) WHERE ".$qryCond."AND `Document`.`path_folder` is not NULL " ;  
            $sql3 .= "SELECT `User`.`id`, `User`.`username`, `MarketResearch`.`id`, `MarketResearch`.`folder_user`, `MarketResearch`.`content_research`, `MarketResearch`.`path_folder`, `MarketResearch`.`created`, `MarketResearch`.`modified`, `MarketResearch`.`user_id` FROM `cdcsambd`.`users` AS `User` LEFT JOIN `cdcsambd`.`people` AS `Person` ON (`Person`.`user_id` = `User`.`id`) INNER JOIN `cdcsambd`.`market_researches` AS `MarketResearch` ON (`MarketResearch`.`user_id` = `User`.`id`) WHERE ".$qryCond;   
            $sql4 .= "SELECT `User`.`id`, `User`.`username`, `EstimateResearch`.`id`, `EstimateResearch`.`content_estimate`, `EstimateResearch`.`path_folder`, `EstimateResearch`.`created`, `EstimateResearch`.`modified`, `EstimateResearch`.`market_research_id`, `EstimateResearch`.`user_id`, `MarketResearch`.`path_folder` FROM `cdcsambd`.`users` AS `User` LEFT JOIN `cdcsambd`.`people` AS `Person` ON (`Person`.`user_id` = `User`.`id`) INNER JOIN `cdcsambd`.`estimate_researches` AS `EstimateResearch` ON (`EstimateResearch`.`user_id` = `User`.`id`) LEFT JOIN `cdcsambd`.`market_researches` AS `MarketResearch` ON (`EstimateResearch`.`market_research_id` = `MarketResearch`.`id`) WHERE ".$qryCond."AND `EstimateResearch`.`path_folder` is not NULL ";*/
            $sql = "SELECT `User`.`id` as `user`, 'Publication' as `type`, `Publication`.`id`, `Publication`.`folder_user`, `Publication`.`title_publication`, `Publication`.`content_publication`, NULL as `title_document`, NULL as `content_document`, NULL as `content_research`, NULL as `content_estimate`, `Publication`.`path_folder`, `Publication`.`created`, `Publication`.`modified`, `Publication`.`user_id`, NULL as `link_secop`, NULL as `market_research_id`, NULL as `Market_path_folder` FROM `publications` AS `Publication` INNER JOIN `users` AS `User` ON (`Publication`.`user_id` = `User`.`id`) WHERE ".$qryCond." AND `Publication`.`path_folder` is not NULL
UNION
SELECT `User`.`id` as `user`, 'Document' as `type`, `Document`.`id`, `Document`.`folder_user`, NULL, NULL, `Document`.`title_document`, `Document`.`content_document`, NULL, NULL, `Document`.`path_folder`, `Document`.`created`, `Document`.`modified`, `Document`.`user_id`, `Document`.`link_secop`, NULL, NULL FROM `documents` AS `Document`  INNER JOIN `users` AS `User` ON (`Document`.`user_id` = `User`.`id`) WHERE ".$qryCond." AND `Document`.`path_folder` is not NULL
UNION
SELECT `User`.`id` as `user`, 'MarketResearch' as `type`, `MarketResearch`.`id`, `MarketResearch`.`folder_user`, NULL, NULL, NULL, NULL, `MarketResearch`.`content_research`, NULL, `MarketResearch`.`path_folder`, `MarketResearch`.`created`, `MarketResearch`.`modified`, `MarketResearch`.`user_id`, NULL, NULL, NULL FROM `market_researches` AS `MarketResearch`  INNER JOIN `users` AS `User` ON (`MarketResearch`.`user_id` = `User`.`id`) WHERE ".$qryCond." AND `MarketResearch`.`folder_user` is not NULL
UNION
SELECT `User`.`id` as `user`, 'EstimateResearch' as `type`, `EstimateResearch`.`id`, NULL, NULL, NULL, NULL, NULL, NULL, `EstimateResearch`.`content_estimate`, `EstimateResearch`.`path_folder`, `EstimateResearch`.`created`, `EstimateResearch`.`modified`, `EstimateResearch`.`user_id`, NULL, `EstimateResearch`.`market_research_id`, `MarketResearch`.`path_folder` FROM `estimate_researches` AS `EstimateResearch` LEFT JOIN `market_researches` AS `MarketResearch` ON (`EstimateResearch`.`market_research_id` = `MarketResearch`.`id`)  INNER JOIN `users` AS `User` ON (`EstimateResearch`.`user_id` = `User`.`id`) WHERE ".$qryCond." AND `EstimateResearch`.`path_folder` is not NULL ";
            
            //$limit = round ($limit / 3);
            //Considerando un limit de 12, 12/4 = 3
            //$limit = 3;
            
            // Echo de datos para verificar esten llegando los valores correctos para los parámetros
            /*echo "<br><br><br>";
            echo "<br>conditions";
            var_dump($conditions);
            echo "<br>fields";
            var_dump($fields);
            echo "<br>order";
            var_dump($order);
            echo "<br>limit";
            var_dump($limit);
            echo "<br>page";
            var_dump($page);
            echo "<br>recursive";
            var_dump($recursive);
            echo "<br>extra";
            var_dump($extra);*/
            
            // Adding LIMIT Clause
            //$page = floor(($page - 1)/4)+1;
            /*$sql1 .= 'LIMIT '.(($page - 1) * $limit) . ', ' . $limit;
            $sql2 .= 'LIMIT '.(($page - 1) * $limit) . ', ' . $limit;
            $sql3 .= 'LIMIT '.(($page - 1) * $limit) . ', ' . $limit;
            $sql4 .= 'LIMIT '.(($page - 1) * $limit) . ', ' . $limit;*/
            $sql .= 'LIMIT '.(($page - 1) * $limit) . ', ' . $limit;
            
            $results = $this->query($sql);
            /*$results1 = $this->query($sql1);
            $results2 = $this->query($sql2);
            $results3 = $this->query($sql3);
            $results4 = $this->query($sql4);*/
            
            // se realiza un tratamiento previo a $results4 para integrar `MarketResearch`.`path_folder`
            /*$i=0;
            foreach ($results4 as $coti)
            {
                $results[$i]['EstimateResearch']['MarketResearch'] = $coti ['MarketResearch'];
                unset($results[$i]['MarketResearch']); // es necesario eliminar este item, para no confundir una cotizacion con un estudio.
                $i++;
            }
            
            $results = array($results1,$results2,$results3,$results4);
            
            $tempo = array();
            
            for($i=0;$i<count($results);$i++)
            {
                foreach ($results[$i] as $item)
                {
                    array_push($tempo, $item);
                }
            }
            //$busqueda = $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group'));
            //return $busqueda;
            
            return $tempo;*/
            
            //reorganizando los resultados
            $tempo = array();
            foreach ($results as $result)
            {
                   array_push($tempo, $result[0]);
            }
            
            
                 
            }
            else
            {
                // PARA EL MODULO CONTRATOS
                $this->useTable = false;
                $sql = "SELECT `Document`.`id`, `Document`.`folder_user`, `Document`.`title_document`, `Document`.`content_document`, `Document`.`path_folder`, `Document`.`created`, `Document`.`modified`, `Document`.`privacy_id`, `Document`.`user_id`, `Document`.`link_secop`, `Person`.`username`, `Person`.`path_avatar`, `Person`.`organization_id` FROM `documents` AS `Document` INNER JOIN `people` AS `Person` ON (`Document`.`user_id` = `Person`.`user_id`) WHERE ".$conditions[0]." ORDER BY `Document`.`modified` DESC "; 
                
                $sql .= 'LIMIT '.(($page - 1) * $limit) . ', ' . $limit;
                
                $this->recursive = 0;     
                $tempo = $this->query($sql);
                
            }
             
            
                 return $tempo;
                
            
        }
        
       
        
        
    }
?>