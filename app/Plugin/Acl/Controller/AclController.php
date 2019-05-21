<?php



/**
 * Las rutas de acceso a un plugin deben ser en minuscula separados por guion ejemplo:
 * /acl/acl/getRoleActions
 */
class AclController extends AclAppController {

	
	/**
     * Carga de modelos
     * @var array
     */
    public $uses = array('Acl.Acos','Acl.Aros','Acl.PermissionRoles','Acl.Roles','Acl.RelatedPermissions');


    public function beforeFilter() {
        parent::beforeFilter();

        // Allow users to register and logout.
        $this->Auth->allow('getRoleActions');
        

    }

    /**
     * Carga de componentes
     * @var array
     */
    // public $components = array('ResourceManager.ResourceManager');



	public function getRoleActions($roleId = null){


		return $this->PermissionRoles->find('all',array('conditions'=>array('Roles.id'=>$roleId,'access'=>1)));


	}

}