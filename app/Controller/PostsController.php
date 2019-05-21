
<?php  
/**
 * Controlador de posts
 */
class PostsController extends AppController
{


    public function beforeFilter() {
        parent::beforeFilter();

        // Allow users to register and logout.
        $this->Auth->allow('getPrivacyFilters','getPrivacyFiltersPublic');
        

    }

    /**
     * Función que obtendra las condiciones de búsqueda de los post de acuerdo a sus opciones de privacidad
     * @return String Query con Opciones de privacidad
     */
    public function getPrivacyFilters($userId = null){


    	 /**
    	  * Cadena con la consulta de filtro de provacidad
    	  * Retornamos los identificadores de post que 
    	  */
    	 
    	$filter = 'Post.id in (SELECT cpost.id from posts as cpost WHERE '; 

	    /**
	     * Si el usuario pertenece a la entidad del usuario actual y la privacidad es 2 (compañeros de entidad)
	     */
	    $filter .='(cpost.users_id = 
	                 
	                 	(select organization_users.user_id from organization_users where organization_users.organization_id = 
	                    (select orgu.organization_id from organization_users as orgu where orgu.user_id = '.$userId.')
	                 	AND organization_users.user_id = cpost.users_id) AND cpost.privacies_id = 2) ';
		
		/**
		 * si el post es propio
		 */
	    $filter .='OR ((cpost.users_id = '.$userId.') ';


	    /**
	     * Si el post no es propio pero la privacidad es 1 (Todos los usuarios)
	     */
	    $filter .='OR (cpost.users_id != '.$userId.' AND cpost.privacies_id = 1) '; 

	    /**
	     * Si el post no es propio y la privacidad es 3 (solo yo) usuario quien creo el post 
	     */
	    $filter .='OR! (cpost.users_id != '.$userId.' AND cpost.privacies_id = 3))) ';
		
		return $filter;

    }


   /**
     * Función que obtendra las condiciones de búsqueda de los post de acuerdo a sus opciones de privacidad
     * @return String Query con Opciones de privacidad
     */
    public function getPrivacyFiltersPublic($userId = null){


    	 /**
    	  * Cadena con la consulta de filtro de provacidad
    	  * Retornamos los identificadores de post que 
    	  */
    	 
    	$filter = 'PostPublic.id in (SELECT cpost.id from posts as cpost WHERE '; 

	    /**
	     * Si el usuario pertenece a la entidad del usuario actual y la privacidad es 2 (compañeros de entidad)
	     */
	    $filter .='(cpost.users_id = 
	                 
	                 	(select organization_users.user_id from organization_users where organization_users.organization_id = 
	                    (select orgu.organization_id from organization_users as orgu where orgu.user_id = '.$userId.')
	                 	AND organization_users.user_id = cpost.users_id) AND cpost.privacies_id = 2) ';
		
		/**
		 * si el post es propio
		 */
	    $filter .='OR ((cpost.users_id = '.$userId.') ';


	    /**
	     * Si el post no es propio pero la privacidad es 1 (Todos los usuarios)
	     */
	    $filter .='OR (cpost.users_id != '.$userId.' AND cpost.privacies_id = 1) '; 

	    /**
	     * Si el post no es propio y la privacidad es 3 (solo yo) usuario quien creo el post 
	     */
	    $filter .='OR! (cpost.users_id != '.$userId.' AND cpost.privacies_id = 3))) ';
		
		return $filter;

    }


}