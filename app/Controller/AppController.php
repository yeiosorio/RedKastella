<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    //...

    // public $uses = array ('Connection');
    
    public $components = array(

        'DebugKit.Toolbar',
        'Session',
        'Auth' => array(
            
            // 'Auth' => array(
            // 'http://redkastella.com/'
            // 
            'loginRedirect' => array('http://redkastella.com/'),

            // 'loginAction' => Array('controller'=>'Users','action'=>'login'),
                                    
            'logoutRedirect' => array(
              'http://redkastella.com/'
            ),
            
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish',
                    'fields' => array('username'=>'username', 'password'=>'password'),
                )
            ),
            // 'authorize' => array('Controller') // Added this line
        ),

        //'Acl.Acl'
    
    );


    /**
     * Método que limita el acceso de un usuario, éste usa el componente de Acl para condicionar los permisos
     * @param  Array  $user Información del usuario logueado
     * @return boolean, redirection Boolean true si tiene acceso, de lo contrario redirije a una página especifica 
     */
    
    public function isAuthorized($user) {

        // /**
        //  * Si el usuario esta autorizado 
        //  */
        // if($this->Acl->authorize($this, $user)){ 
        
        //      return true; 

        //  }else{ 

        //     /**
        //      * Redirigimos a una página en caso de no tener permisos
        //      */
        //     $this->redirect(array('controller'=>'Users', 'action' => 'index'));
        // }
        
         return true; 
        

        // // Admin can access every action
        // if (isset($user['role']) && $user['role'] === 'admin') {
        //     return true;
        // }

        // // Default deny
        // //return false;
        // return true;
    }

    public function beforeFilter() {
 
        // parent::beforeFilter();

        // $this->Auth->allow('display'); // aqui esta permitiendo que sin login todas las acciones de index y view sean ejecutadas (ver contenido sin loguearse, esto puede no ser deseado
        
        // $this->Session->write('Config.language', 'esp');
        
        // if ($this->Session->check('Config.language')) {
        //     Configure::write('Config.language', $this->Session->read('Config.language'));
        // }
        // setlocale(LC_TIME, 'Spanish');
        
        // // Escanea constantemente, si la id almacenada en la sesión corresponde a la que está en base de datos
        // $id_connection = $this->Session->read('User.conexion_id');
        // if((!$this->Connection->findById($id_connection))&&($this->Session->read('User.id')))
        // {
        //     $this->Session->destroy();
        //     $this->redirect($this->Auth->logout());
        // }

        // $my_rol=$this->Session->read('User.rol_id');
    
    }

      /**
        * Función que configura el juego de caracteres en la cabecera http donde sea necesario llamarlo
        */
        public function setCharset(){

             header('Content-Type: text/html; charset=utf-8');
            
        }
}
