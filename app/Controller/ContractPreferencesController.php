<?php 

/**
 * Controlador de preferencias de usuario
 */
class ContractPreferencesController extends AppController
{


    public $name = 'ContractPreferences';

    // var $uses = array('ContractPreferences'); 


    /**
     * Función que guarda los dartos de las preferencias de busqueda de un usuario
     * @return JsonString con el estado de guardado true: exito, false: fallo
     */
    public function saveContractPreference(){

    		$this->autoRender = false;
       	
        /**
         * [$preferences Datos de preferencia a guardar]
         * @var Array
         */
       	$selection = $this->request->data['selection'];
			 
        $departamento = $this->request->data['departamento'];

        $status = true;


        $this->loadModel('ContractSubcategoryPreference');

        $this->loadModel('ContractPreference');

        // $this->ContractPreference->find('all')

        if($this->setContractPreferences()){



            /**
             * [$contractPreference Arreglo con las preferencias del usuario]
             * @var Array
             */
            $contractPreference = $this->getContractPreferences();

            $contractPreferenceId = $contractPreference['ContractPreference']['id'];


            $this->ContractPreference->id = $contractPreferenceId;

            $this->ContractPreference->saveField('departamento', $departamento);

            $preferences = $this->ContractSubcategoryPreference->find('all',array('conditions'=> array('ContractSubcategoryPreference.contract_preferences_id' => $contractPreferenceId)));

            foreach ($preferences as $preference) {

                $this->ContractSubcategoryPreference->id = $preference['ContractSubcategoryPreference']['id'];

                $this->ContractSubcategoryPreference->delete();

                $this->ContractSubcategoryPreference->clear();
            }


            foreach ($selection as $selection) {
                
                $this->ContractSubcategoryPreference->Create();

                $this->ContractSubcategoryPreference->save(
                    Array(
                      'contract_preferences_id'   =>  intval($contractPreferenceId),
                      'contract_subcategories_id' =>  intval($selection['id']),
                      'minvalue'                  =>  $selection['values'][0],
                      'maxvalue'                  =>  $selection['values'][1]
                    )
                );

                $this->ContractSubcategoryPreference->clear();
            }

        }

       /**
        * Mandamos el resultado de la acción
        */
       	echo json_encode(Array( "success" => $status, "selection" => $selection));

    }



    public function getContractCategories(){


        $this->autoRender = false;


        $this->loadModel('ContractCategory');


        /**
         * Categories from secop
         * @var Array
         */
        $contractCategories = $this->ContractCategory->find('all',array('conditions'=>array('ContractCategory.contract_providers_id' => 1)));

        echo json_encode(Array('success'=>true,'contractTypes'=> $contractCategories));


    }


    public function getContractSubCategories(){


        $this->autoRender = false;


        $this->loadModel('ContractSubcategory');


        $contractSubcategoriesId = $selection = $this->request->data['id'];


        $contractSubCategories = $this->ContractSubcategory->find('all',array('recursive' => -1,'conditions'=>array('ContractSubcategory.categories_id' => $contractSubcategoriesId)));


        echo json_encode(Array('success'=>true,'contractTypes'=> $contractSubCategories));


    }



    /**
     * Función que agrega los valores por defecto de preferecias
     * @param Int $minvalue valor mínimo 
     * @param Int $maxvalue valor máximo
     */
    public function setContractPreferences(){

      /**
       * [$contractPreference Arreglo con las preferencias del usuario]
       * @var Array
       */
    	$contractPreference = $this->getContractPreferences();

    	/**
       * Si no hay resultados 
       */
    	if (!$contractPreference) {
	    	
        /**
         * Creamos una nueva preferencia para el usuario
         */
        $this->ContractPreference->create();
	    
      /**
       * Guardamos, en caso de no guardar retornamos true 
       */
			if(! $this->ContractPreference->save(Array('users_id'=> $this->Auth->user('id')))){
				return false;
			}    		
		}

     // retornamos verdadero por defecto
		return true;

    }

    /**
     * Función que actualiza las preferencias del usuario en cuanto a los valores 
     * @param  Int $minvalue valor mínimo 
     * @param  Int $maxvalue valor máximo
     * @return Boolean estado de la función
     */
    public function updateContractPreferences($minvalue = null, $maxvalue = null){

      /**
       * Verificación de valores por defecto
       */
    	if($this->setContractPreferences(0,0)){

        //obtenemos las preferencias del usuario
    		$contractPreference = $this->getContractPreferences();

          //si hay preferencias
		    	if ($contractPreference) {
		        
            
		    		$this->ContractPreference->id = $contractPreference['ContractPreference']['id'];

		    		//si no se guarda retornamos falso
		    		if(!$this->ContractPreference->save(Array('minvalue'=> $minvalue,'maxvalue'=> $maxvalue))){
		    			return false;
		    		}    		
		    	
		    	}

    	}
      
      // retornamos verdadero por defecto
      return true;


    }


    /**
     * Funcion que retorna las preferencias del usuario actual
     * @return Array preferencias del usuario
     */
    public function getContractPreferencesAjax(){

      $this->autoRender = false;

      /**
       * [$contractPreferences Preferencias del usuario]
       * @var Array
       */
      $contractPreferences = $this->ContractPreference->find('first', array('conditions'=> array('ContractPreference.users_id' => $this->Auth->user('id'))));  

      echo json_encode($contractPreferences);
      

    }



    /**
     * Funcion que retorna las preferencias del usuario actual
     * @return Array preferencias del usuario
     */
    public function getDepPref(){

      $this->autoRender = false;

      /**
       * [$contractPreferences Preferencias del usuario]
       * @var Array
       */
      $contractPreferences = $this->ContractPreference->find('first', array('conditions'=> array('ContractPreference.users_id' => $this->Auth->user('id'))));  


      $this->loadModel('Department');

      $departamento = $contractPreferences['ContractPreference']['departamento'];

      if($departamento !== 'Todos'){

        $foundDepartment = $this->Department->find('first', Array('conditions'=> Array('Department.id' => $departamento)));

        $departamento = $foundDepartment['Department']['name'];

      } 


      echo json_encode(Array('departamento' => $departamento));
      

    }


    /**
     * Funcion que retorna las preferencias del usuario actual
     * @return Array preferencias del usuario
     */
    public function getContractPreferences(){

      /**
       * [$contractPreferences Preferencias del usuario]
       * @var Array
       */
    	$contractPreferences = $this->ContractPreference->find('first', array('conditions'=> array('ContractPreference.users_id' => $this->Auth->user('id'))));  

   		return $contractPreferences;
    	

    }

    /**
     * Funcion que actualiza las categorias de preferencia de un usuario
     * @param  Array Preferencias de categorias del usuario
     * @return boolean estado de la función 
     */

    public function updateCategoryPreferences($categoriesPreferences){

      /**
       * [$contractPreference Preferencias del usuario]
       * @var Array
       */
    	$contractPreference = $this->getContractPreferences();
    	   
     /**
       * [$id Identificador de las preferencias de usuario]
       * @var Int
       */
      $id = $contractPreference['ContractPreference']['id'];

      /**
       * [$categoryPreferences Cateogorias de preferencia de un usuario]
       * @var Array
       */
    	$categoryPreferences = $this->getCategoryPreferences($id);

      //cargamos el modelo de categorias de preferencias
      $this->loadModel('CategoryPreference');

    	//eliminamos los registros 
    	$this->CategoryPreference->deleteAll(array('CategoryPreference.contract_preferences_id' => $id), false);

    	//ingresamos los registros actuales
		  foreach ($categoriesPreferences as $categoryPreference) {

      /**
       * Creamos una nueva entidad de preferencias de categoria
       */
			$this->CategoryPreference->create();	
    	 	
        //si no se guarda retornamos falso 
    	 	if(!$this->CategoryPreference->save(Array('contract_preferences_id'=> $id, 'contract_categories_id'=> $categoryPreference['id']))){
    	 		return false;
    	 		break;
    	 	}  

		 	//Método Clear() el cual es necesario llamar cuando se trabajan ciclos con entidades 
		 	$this->CategoryPreference->clear();
		}

    // retornamos verdadero por defecto
		return true;
    }


    /**
     * Función que pregunta por las preferencias definidas por el usuario y las retorna en fomato JsonString
     * @return JsonString con las preferencias definidas por el usuario
     */
    public function getPreferences(){

      //no renederizamos vista
    	$this->autoRender = false;

      $this->loadModel('ContractSubcategoryPreference');

      $this->setContractPreferences();
      
      $contractPreference = $this->getContractPreferences();

      $contractPreferenceId = $contractPreference['ContractPreference']['id'];

      $preferences = $this->ContractSubcategoryPreference->find('all',array('conditions' => Array('ContractSubcategoryPreference.contract_preferences_id' => $contractPreferenceId)));

      $thereAre = false;

      if($preferences){

        $thereAre = true;
      }

      //escribimos los datos
    	echo json_encode(Array('savedPreferences'=> $preferences, 'thereAre' => $thereAre));
    
    }


    /**
     * Función que retorna las categorias de preferencia de un usuario
     * @param  Int 
     * @return Array Categorias de preferencia
     */
    public function getCategoryPreferences($contractPreferenceId = null){

        //cargamos el modelo de categorias de preferencias
        $this->loadModel('CategoryPreference');

        return $this->CategoryPreference->find('all',Array('conditions' => Array('CategoryPreference.contract_preferences_id'=>$contractPreferenceId)));

    }




    public function beforeFilter() {

      parent::beforeFilter();
    
      // Allow users to register and logout.
      $this->Auth->allow('getContractCategories','getContractPreferencesAjax','getPreferences','getContractSubCategories');

    }  


}








