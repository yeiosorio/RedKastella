<?php

App::uses('Component', 'Controller');

class ResourceManagerComponent extends Component
{
    
    public function __construct() {
       	
    	//carga de modelos
        $this->Resource 			= ClassRegistry::init('ResourceManager.Resource');
        $this->ResourceFileType 	= ClassRegistry::init('ResourceManager.ResourceFileType');
        $this->ResourceType 		= ClassRegistry::init('ResourceManager.ResourceType');
        $this->ResourceExtension 	= ClassRegistry::init('ResourceManager.ResourceExtension');
        $this->ResourceParentEntity = ClassRegistry::init('ResourceManager.ResourceParentEntity');
        $this->User 				= ClassRegistry::init('User');
    }
    

    /**
     * Función que obtiene recursos
     * @param  [type] $user_id      [description]
     * @param  [type] $parentEntity [description]
     * @param  [type] $resourceType [description]
     * @param  [type] $entity_id    [description]
     * @return [type]               [description]
     */
    public function getResources($user_id = null, $parentEntity = null, $resourceType = null, $entity_id = null){
    
        /**
         * identificador de la entidad padre
         * @var Int
         */
        $parentEntityId = $this->getParentEntityId($parentEntity);

        /**
         * Ruta del la carpeta del usuario
         * @var String
         */
        $userFolder = $this->getUserFolder($user_id, true);

        /**
         * identificador de tipo de recurso
         * @var Int
         */
        $resourceTypeId =  $this->getResourceTypeId($resourceType); 

        /**
         * Recursos encontrados
         * @var Array
         */
        $resources = $this->Resource->find('all',array('conditions'=>array('Resource.users_id'=> $user_id,'Resource.resource_parent_entities_id'=> $parentEntityId, 'Resource.resource_types_id'=> $resourceTypeId, 'Resource.entity_id'=> $entity_id)));  

        $paths = Array();

        /**
         * Ruta principal de la aplicación
         * @var String
         */
        $url = Router::url('/', true);

        /**
         * recorrido del arreglo de recursos
         */
        foreach ($resources as $resource) {
            
            /**
             * Rutas 
             */
            $paths[] = Array('id'=> $resource['Resource']['id'], 'file' => $url.$userFolder."/".$resource['Resource']['stored_file_name']);                

        }     

        /**
         * retornamos el arreglo con los resultados
         */
        return $paths;

    }

    /**
     * Función que usa la función saveFiles que guarda los archivos recividos en el servidor y en las relaciones de la base de datos de recursos
     * se ha implementado asi para hacer alguna logica adicional o filtrar algunos datos de ser necesario
     * @param  Int $user_id         Identificador del usuario
     * @param  String $parentEntity Nombre de la entidad padre
     * @param  String $resourceType Tipo de recurso
     * @param  Int $entity_id       Identificador de la entidad hija
     * @return boolean true en caso de exito, falso en caso de errores
     */
    public function saveResources($user_id = null, $parentEntity = null, $resourceType = null, $entity_id = null){

       /**
        * Se usa el método saveFiles
    	*/
    	
        if(count($_FILES)){
        
        	if($this->saveFiles($user_id, $parentEntity, $resourceType, $entity_id)){	
    			return true;
        	}else{
        		return false;
        	}
        }

    }

    /**
     * Función que guarda los archivos recividos en el servidor y en las relaciones de la base de datos de recursos
     * @param  Int $user_id         Identificador del usuario
     * @param  String $parentEntity Nombre de la entidad padre
     * @param  String $resourceType Tipo de recurso
     * @param  Int $entity_id       Identificador de la entidad hija
     * @return boolean true en caso de exito, falso en caso de errores
     */
    public function saveFiles($user_id = null, $parentEntity = null, $resourceType = null,$entity_id = null){

        /**
         * Variable con el nombre de la carpeta del usuario
         * @var String con el nombre de la carpeta, Boolean False si hubieron errores comprobando la ruta del usuario
         */
        $userPathFolder = $this->getUserFolder($user_id);    

        /**
         * Identificador de la entidad padre
         * @var Int
         */
        $parentEntityId = $this->getParentEntityId($parentEntity);


        /**
         * Variable que contiene el identificador del tipo de recurso
         * @var Int
         */
        $resourceTypeId = $this->getResourceTypeId($resourceType); 

        /**
         * chequeo de la carpeta del usuario y la subida de archivos
         */
        if($userPathFolder !== false && $this->checkUploadedFiles()){

            /**
             * usamos diverse_array para formatear el arreglo de archivos
             * @var Array
             */
            $files = $this->diverse_array($_FILES['files']);
            
            foreach ($files as $file){  

               $fileInfo = $this->getFileInfo($file);

                
                /**
                 * variable que contiene el resultado de guardar el archivo en disco
                 * @var String Nombre del archivo guardado, False en caso de error
                 */
                $savedFileToDisk = $this->saveFileToDisk($file, $userPathFolder);

                /**
                 * Si se ha guardado el archivo con éxito, insertamos su relación en la base de datos
                 */
                if($savedFileToDisk){
                    
                    /**
                     * Inserción en recursos
                     */
                    $this->insertIntoResources($user_id, $savedFileToDisk, $fileInfo['name'], $this->getExtensionId($fileInfo['extension']), $parentEntityId, $resourceTypeId, $entity_id, $fileInfo['sizeBytes'], $fileInfo['sizeFormat']);

                }else{
                    return false;
                }
            }

        }else{
            
            return false;
        
        }

        /**
         * Retornamos verdadero por defecto
         */
        return true;

    }


    /**
     * Función que convierte un arreglo de archivos subidos de una manera mas legible y fácil de manejar
     * @param  Array $vector Arreglo de archivos
     * @return Array Arreglo formateado
     */
    function diverse_array($vector) { 
        $result = array(); 
            foreach($vector as $key1 => $value1) 
                foreach($value1 as $key2 => $value2) 
                    $result[$key2][$key1] = $value2; 
            return $result; 
    } 


    /**
     * Función que guarda un archivo fisicamente
     * @param  File     $file           Archivo
     * @param  String   $userPathFolder Ruta completa del directorio del usuario
     * @return Boolean                  true o false en caso de errores
     */
    public function saveFileToDisk($file = null, $userPathFolder = null ){
            
        /**
         * Obtenemos el TimesTamp Actual
         * @var String
         */
        $microtime = $this->getMicroTime();

        /**
         * Información del archivo
         * @var Array
         */
        $fileInfo = $this->getFileInfo($file);

        /**
         * Subimos el archivo y retornamos su nombre
         */
        if(move_uploaded_file($file['tmp_name'], $userPathFolder.'/'.$microtime.'.'.$fileInfo['extension'])) {
            return $microtime.'.'.$fileInfo['extension'];

        }
        /**
         * Retornamos false por defecto
         */
        return false;

    }


    /**
     * Función que comprueba si un usuario tiene una carpeta definida, si no la tiene, se crea una y se asigna a la entidad User
     * @param $user_id identificador del usuario
     * @return String con el nombre de la carpeta, Boolean False si hubieron errores comprobando la ruta del usuario
     */
    public function getUserFolder($user_id = null,$baseUrlPath = null){

        $state = true;

        $user =  $this->User->find('first',Array('conditions'=>array('User.id' => $user_id)));
        
        $userFolder = $user['User']['user_folder'];
        
        /**
         * Si no hay una carpeta asignada
         */
        if($userFolder == ""){

            /**
             * configuramos la identificación del usuario
             * @var Int
             */
            $this->User->id = $user_id;  

            /**
             * microtime actual
             * @var String
             */
            $microtime = $this->getMicroTime();

            /**
             * Guardado del nombre de la carpeta
             */
            $this->User->saveField("user_folder", $microtime);

            /**
             * configuramos el nombre de la carpeta con el microtime
             * @var String
             */
            $userFolder = $microtime;

        }

        /**
         * Llamamos a la función getResourcesPath, que contiene la Ruta donde se crean las carpetas y se suben los archivos
         * y la asignamos $userFolderPath  
         * @var String
         */

        $userFolderPath = $this->getResourcesPath($baseUrlPath).$userFolder;
           
 
        /**
         * Si la carpeta no existe la creamos
         */
        if ( !file_exists($userFolderPath))
        {
            /**
             * si no se ha creado
             */
            if(!mkdir($userFolderPath, 0777)){
                $state = false;
            }   
        }

        if ($state) {

            return $userFolderPath;
        }

        /**
         * retornamos falso por defecto
         */
        return false;
    }

    /**
     * Función que obtiene información de un archivo
     * @param  File $file Archivo
     * @return Array Arreglo con la información del archivo
     */
    public function getFileInfo($file){

        $path_parts = pathinfo($file["name"]);

        $filename = trim($path_parts['filename']);
        $extension = $path_parts['extension'];
        $sizeBytes = $file['size'];

        $fileInfo = Array('name'=> $filename,'extension'=> $extension,'sizeBytes'=> $sizeBytes ,'sizeFormat'=> $this->formatSizeUnits($sizeBytes));
        return $fileInfo;

    }


     function formatSizeUnits($bytes)
        {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }


    /**
     * Función que chequea si los archivos se han subido correctamente al servidor
     * @return Boolean true en caso de exito, falso en caso de fallar
     */
    public function checkUploadedFiles(){


       /**
        * usamos diverse_array para formatear el arreglo de archivos
        * @var Array
        */
        $files = $this->diverse_array($_FILES['files']);

        /**
         * Recorrido para chequear que no hallan errores al momento de la subida 
         */
        foreach ($files as $file) {

            /**
             * Si hay errores retornamos false
             */
            if (!(file_exists($file['tmp_name']) && is_uploaded_file($file['tmp_name']) && $file['error'] === UPLOAD_ERR_OK)) { 
                return false;
            }
        }
        /**
         * retornamos true por defecto
         */
        return true;
    }

    /**
     * Función usada para insertar un nuevo recurso en la base de datos
     * @param  Int $users_id                    	Id del usuario
     * @param  String $stored_file_name             nombre del aruchiv guardado en disco
     * @param  String $name                        	Nombre del archivo
     * @param  Int $resource_extensions_id      	Identificador de la extensión
     * @param  Int $resource_parent_entities_id 	Identificador de la entidad padre
     * @return Boolean                              True si fue exitoso, false si ocurrio un error
     */
    public function insertIntoResources($users_id = null, $stored_file_name = null, $name = null, $resource_extensions_id = null, $resource_parent_entities_id = null, $resourceTypeId = null, $entity_id = null, $sizeBytes = null, $sizeFormat = null){

		$this->Resource->create();	
    	 	
		$newResourceData = Array(
			'users_id' 	            => 	$users_id,
			'stored_file_name' 		=> 	$stored_file_name,
			'name' 		            => 	$name,
			'resource_extensions_id'=> $resource_extensions_id,
            'resource_types_id'     => $resourceTypeId,
			'resource_parent_entities_id' => $resource_parent_entities_id,
			'entity_id' => $entity_id,
            'size_format' => $sizeFormat,
            'bytes' => $sizeBytes
		);


    	if(!$this->Resource->save($newResourceData)){
    		return false;
    	} 

    	return true; 
    }

    /**
     * Función que obtiene el identificador de un tipo de recurso
     * @param  String $resourceType Nombre del tipo de recurso
     * @return Int                  Identificador del recurso, false en caso de no encontrarlo
     */
    public function getResourceTypeId($resourceType = null){

        $this->autoRender = false;

        $ResourceType = $this->ResourceType->find('first', Array('conditions'=>array('ResourceType.name' => $resourceType)));
    
        if ($ResourceType) {
            return $ResourceType['ResourceType']['id'];
        }

        return false;

    }

    /**
     * Función que obtiene el identificador de una extensión 
     * @param  String $extension nombre de la exrensión
     * @return Int identificador de la extensión, boolean false en caso de no entrado 
     */
    public function getExtensionId($extension = null){

        $resourceExtension = $this->ResourceExtension->find('first', Array('conditions'=>array('ResourceExtension.extension' => $extension)));
    
        if ($resourceExtension) {
            return $resourceExtension['ResourceExtension']['id'];
        }

        return false;
    }


    /**
     * Función que obtiene el identificador de una entidad padre
     * @param  String $parentEntityName nombre de la entidad padre
     * @return Int  Identificador de la entidad, boolean False si no existe
     */
    public function getParentEntityId($parentEntityName = null){

        $this->autoRender = false;

        $parentEntity =  $this->ResourceParentEntity->find('first',Array('conditions'=>array('ResourceParentEntity.name'=> $parentEntityName)));

        if ($parentEntity) {
            
           return $parentEntity['ResourceParentEntity']['id'];
        }

         return false;
    }

    /**
     * Función que obtiene la fecha y hora actuales de un objeto DateTime
     * @return String Fecha Actual en formato numerico
     */
    public function getMicroTime(){
        
        ini_set('precision', 25);

		return  str_replace('.', '', microtime(true));

    }

    /**
     * Función que devuelve la ruta del servidor donde se suben los archivos
     * @return String ruta
     */
    public function getResourcesPath($baseUrlPath = null){
            
    
        if ($baseUrlPath == true) {
            
            return "resourcesFolder/";

        }else{
    
            return WWW_ROOT.'resourcesFolder/';           
        }

    }



    /**
     * Función que elimina un recurso por su identificador 
     * @param  Int $id Identificador
     */
    public function deleteResourceById($id = null, $userId = null){

        $state = true;
        $userFolder = $this->getUserFolder($userId);

        $resources = $this->Resource->find('all',array('conditions'=>array('Resource.id'=> $id)));  

        foreach ($resources as $resource) {

            $path = $userFolder."/".$resource['Resource']['stored_file_name'];

            /**
             * Borrado físico del archivo
             */
            
            if(unlink($path)){

               if($this->Resource->delete($resource['Resource']['id'])){

               }else{
                    $state = false;
                    break;
               }

           }else{
                $state = false;
                break;
           }


       }

       return $state;

    }


    public function deleteResources($userId = null, $parentEntity = null, $entityId = null){


        $parentEntityId = $this->getParentEntityId($parentEntity);

        $userFolder = $this->getUserFolder($userId);



        $resources = $this->Resource->find('all',array('conditions'=>array('Resource.users_id'=> $userId,'Resource.resource_parent_entities_id'=> $parentEntityId ,'Resource.entity_id'=> $entityId)));  

        foreach ($resources as $resource) {
            

            $path = $userFolder."/".$resource['Resource']['stored_file_name'];

            /**
             * Borrado físico del archivo
             */
            
            if(unlink($path)){

                 $this->Resource->delete($resource['Resource']['id']);
            }



        }

    }



    public function askForPostThumbnail(){


    }


}
