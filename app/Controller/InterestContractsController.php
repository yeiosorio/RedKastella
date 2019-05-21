<?php

App::uses('AppController', 'Controller');

/**
 * InterestContracts Controller
 *
 * @property InterestContract $InterestContract
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class InterestContractsController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Session', 'RequestHandler', 'Paginator');
    public $helpers = array('StringUtil', 'Session');

    /**
     * index method
     *
     * @return void
     */
    public function index() {


        // $this->autoRender = false;

        /**
         * Configuración de la consulta de posts
         * @var Array
         */
        $this->Paginator->settings = Array(
            'order' => 'InterestContract.id DESC',
            'limit' => 10,
                // 'conditions' =>Array('User.id'=> $this->Auth->user('id')),
                // 'recursive' => 2
        );


        $this->set('interestContracts', $this->Paginator->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->InterestContract->exists($id)) {
            throw new NotFoundException(__('Invalid interest contract'));
        }
        $options = array('conditions' => array('InterestContract.' . $this->InterestContract->primaryKey => $id));
        $this->set('interestContract', $this->InterestContract->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {

        if ($this->request->is('post')) {

            $this->InterestContract->create();

            $data = $this->request->data;

            $data['InterestContract']['users_id'] = $this->Auth->user('id');

            if ($this->InterestContract->save($this->request->data)) {

                $this->Session->setFlash(__('The interest contract has been saved.'));

                return $this->redirect(array('action' => 'index'));
            } else {

                $this->Session->setFlash(__('The interest contract could not be saved. Please, try again.'));
            }
        }

        $users = $this->InterestContract->Users->find('list');

        $this->set(compact('users'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {

        if (!$this->InterestContract->exists($id)) {

            throw new NotFoundException(__('Invalid interest contract'));
        }

        if ($this->request->is(array('post', 'put'))) {

            if ($this->InterestContract->save($this->request->data)) {

                $this->Session->setFlash(__('The interest contract has been saved.'));

                return $this->redirect(array('action' => 'index'));
            } else {

                $this->Session->setFlash(__('The interest contract could not be saved. Please, try again.'));
            }
        } else {

            $options = array('conditions' => array('InterestContract.' . $this->InterestContract->primaryKey => $id));

            $this->request->data = $this->InterestContract->find('first', $options);
        }


        $this->set(compact('users'));
    }

    /**
     * Función que guarda un contrato de interes por el usuario logueado
     */
    public function saveInterestContract() {

        $this->autoRender = false;

        $data = $this->request->data['interest'];

        $data['users_id'] = $this->Auth->user('id');

        $this->InterestContract->create();

        $state = true;

        $contract = null;

        /**
         * Modificar la pregunta de si ya se ha inscrito el contrato
         */
        if (!$this->findContract($data['num_constancia'])) {

            if (!$this->InterestContract->save($data)) {

                $state = false;
            }
        }


        $contract = $this->findContract($data['num_constancia']);

        if (!$this->userRelatedContract($this->Auth->user('id'), $contract['InterestContract']['id'])) {

            $this->saveRelatedInterestContract($this->Auth->user('id'), $contract['InterestContract']['id']);
        }

        echo json_encode(Array('success' => $state, 'contract' => $contract['InterestContract']));
    }

    /**
     * Guardado de Contrato de interes
     */
    public function saveRelatedInterestContract($userId, $contractId) {

        $this->loadModel('UserInterestContract');

        $this->UserInterestContract->create();

        $saved = $this->UserInterestContract->save(Array('users_id' => $userId, 'interest_contracts_id' => $contractId));

        return $saved;
    }

    /**
     * Función que pregunta si hay relacionado un contrato a un usuario
     */
    public function userRelatedContract($userId, $contractId) {

        $this->loadModel('UserInterestContract');

        $userInterestContract = $this->UserInterestContract->find('first', array('conditions' => array('UserInterestContract.users_id' => $userId, 'UserInterestContract.interest_contracts_id' => $contractId)));

        return $userInterestContract;
    }

    public function findContract($numConstancia) {

        $contract = $this->InterestContract->find('first', array('conditions' => array('InterestContract.num_constancia' => $numConstancia)));

        return $contract;
    }

    /**
     * obtiene un contrato por su numero de constancia
     * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
     * @date     2016-09-27
     * @datetime 2016-09-27T14:28:53-0500
     * @return   [type]                   [description]
     */
    public function getContractByNumConstancia() {

        $this->autoRender = false;

        $numConstancia = $this->request->data['numConstancia'];

        $contract = $this->InterestContract->find('first', Array('conditions' => Array('InterestContract.num_constancia' => $numConstancia)
                )
        );

        echo json_encode($contract);
    }

    /**
     * obtiene un contrato por su numero de constancia
     * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
     * @date     2016-09-27
     * @datetime 2016-09-27T14:28:53-0500
     * @return   [type]                   [description]
     */
    public function getPublicContractByNumConstancia() {

        $this->autoRender = false;

        $numConstancia = $this->request->data['numConstancia'];

        $contract = $this->InterestContract->find('first', Array('conditions' => Array('InterestContract.num_constancia' => $numConstancia)
                )
        );

        echo json_encode($contract);
    }

    /**
     * borrar un contrato de interes
     * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
     * @return   [type]                   [description]
     */
    public function delInterestContract() {

        $this->autoRender = false;

        $data = $this->request->data['interest'];

        $contractId = intval($data['id']);

        $state = true;


        $this->loadModel('UserInterestContract');

        $relatedContract = $this->userRelatedContract($this->Auth->user('id'), $contractId);

        if ($relatedContract) {

            $this->UserInterestContract->id = $relatedContract['UserInterestContract']['id'];

            $this->UserInterestContract->delete();
        }

        echo json_encode(Array('success' => $state));
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->InterestContract->id = $id;
        if (!$this->InterestContract->exists()) {
            throw new NotFoundException(__('Invalid interest contract'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->InterestContract->delete()) {
            $this->Session->setFlash(__('The interest contract has been deleted.'));
        } else {
            $this->Session->setFlash(__('The interest contract could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    /**
     * Contratos de interes
     * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
     * @date     2016-09-27
     * @datetime 2016-09-27T12:09:43-0500
     * @param    [type]                   $numConstancia [description]
     * @return   [type]                                  [description]
     */
    public function interestedInContracts($numConstancia = null) {


        /**
         * Si el usuario esta logeado lo llevamos a la parte publica
         * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
         * 2016-09-27 09:08:20
         */
        if (!$this->Session->check('Auth.User')) {


            $this->redirect($this->Auth->redirect(array('action' => 'publicContracts', $numConstancia)));
        }

        if ($numConstancia != null) {

            $contract = $this->findContract($numConstancia);


            $contract = $contract['InterestContract'];

            $words = explode(" ", $contract['contenido']);

            $contract['contenido'] = implode(" ", array_splice($words, 0, 15));

            $contract['contenido'] = $contract['contenido'] . '...';

            $sharedInfo = $contract['title'] . ' - ' . $contract['contenido'];

            $this->set(compact('numConstancia', 'sharedInfo'));
        }
    }

    /**
     * contratos publicos
     * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
     * @date     2016-09-27
     * @datetime 2016-09-27T14:31:01-0500
     * @param    [type]                   $numConstancia [description]
     * @return   [type]                                  [description]
     */
    public function publicContracts($numConstancia = null) {

        /**
         * Si el usuario esta logeado lo llevamos a la parte privada
         * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
         * 2016-09-27 09:08:20
         */
        if ($this->Session->check('Auth.User')) {

            $this->redirect($this->Auth->redirect(array('action' => 'interestedInContracts', $numConstancia)));
        }

        $this->layout = 'no_login';

        if ($numConstancia != null) {


            $contract = $this->findContract($numConstancia);


            $contract = $contract['InterestContract'];


            $words = explode(" ", $contract['contenido']);

            $contract['contenido'] = implode(" ", array_splice($words, 0, 15));

            $contract['contenido'] = $contract['contenido'] . '...';


            $sharedInfo = $contract['title'] . ' - ' . $contract['contenido'];


            $this->set(compact('numConstancia', 'sharedInfo'));
        }
    }

    public function contractPreferences() {


        $this->loadModel('User');

        $id = $this->Auth->user('id');

        if ($this->request->is('get')) {

            $user = $this->User->find('first', Array('conditions' => Array('User.id' => $id)));
            $this->request->data = $user;
        }


        if ($this->request->is(array('post', 'put'))) {

            $userInfo = $this->request->data['User'];


            $this->User->validator()->remove('password');

            $user = $this->User->find('first', Array('conditions' => Array('User.id' => $id)));


            $this->User->id = $id;


            if ($this->User->save(Array('User' => $userInfo))) {
                
            }



            $user = $this->User->find('first', Array('conditions' => Array('User.id' => $id)));
        }

        $this->set('user', $user);
    }

    /**
     * Función que obtiene las publicaciones
     * @return JsonString String con los datos en formato Json
     */
    public function getContractsInterested() {
        Cache::clear();
        $this->autoRender = false;

        $userId = $this->Auth->user('id');

        /**
         * desde que número de resultados devolvera items
         * @var Int
         */
        $from = $this->request->data['from'];

        /**
         * Número de resultados
         * @var Int
         */
        $number = 20;

        $this->loadModel('UserInterestContract');


        /**
         * variable que contiene el resultado de la consulta de posts
         * @var Array
         */
        $posts = $this->UserInterestContract->find('all', Array(
            'order' => Array('UserInterestContract.id DESC'),
            'limit' => $number,
            'offset' => $from,
            'recursive' => 2,
            /**
             * Configuramos las opciones de privacidad y el tipo de post 
             */
            'conditions' => Array('UserInterestContract.users_id' => $userId)
                )
        );
      
        /**
         * Se imprimen los resultados en tipo Json
         */
        echo json_encode($posts);
        
    }
    
    /**
     * Función que obtiene todos los contratos
     * @return JsonString String con los datos en formato Json
     */
    public function getContractsNotifications() {

        $this->autoRender = false;

        $userId = $this->Auth->user('id');

        $this->loadModel('UserInterestContract');


        /**
         * variable que contiene el resultado de la consulta de posts
         * @var Array
         */
        $posts = $this->UserInterestContract->find('all',
                Array('order' => Array('UserInterestContract.id DESC'),
                      'conditions' => Array('UserInterestContract.users_id' => $userId)
                )
        );
        
        /**
         * Se imprimen los resultados en tipo Json
         */
        echo json_encode($posts);
    }

    /**
     * Función que obtiene las publicaciones
     * @return JsonString String con los datos en formato Json
     */
    public function getPublicContractsInterested() {



        $this->autoRender = false;

        /**
         * usuario por defecto
         * @var integer
         */
        $userId = 421;

        /**
         * desde que número de resultados devolvera items
         * @var Int
         */
        $from = $this->request->data['from'];

        /**
         * Número de resultados
         * @var Int
         */
        $number = 20;

        $this->loadModel('UserInterestContract');


        /**
         * variable que contiene el resultado de la consulta de posts
         * @var Array
         */
        $posts = $this->UserInterestContract->find('all', Array(
            'order' => Array('UserInterestContract.id DESC'),
            'limit' => $number,
            'offset' => $from,
            'recursive' => 2,
                /**
                 * Configuramos las opciones de privacidad y el tipo de post 
                 */
                // 'conditions'  => Array('UserInterestContract.users_id'=> $userId)
                )
        );

        /**
         * Se imprimen los resultados en tipo Json
         */
        echo json_encode($posts);
    }

    public function beforeFilter() {

        parent::beforeFilter();

        // Diferente Layout
        $this->Auth->allow('publicContracts', 'getPublicContractsInterested', 'getPublicContractByNumConstancia');
    }

    /**
    * Carlos Felipe Aguirre Taborda GL STUDIOS S.A.S 2017-06-28 09:17:53
    * @param int indice 
    * @return  void
    * Descripción: Lista los contratos que se han marcado como demi intereres recibe un parametro que sera la paginacion
    */
    public function updateContractsInterested( $indice = 0 ){

        $contracts = $this->InterestContract->query(
            'SELECT * FROM interest_contracts GROUP BY interest_contracts.num_constancia'
        );
        $this->set( compact( 'contracts' ) );

    }


    /**
    * Carlos Felipe Aguirre Taborda GL STUDIOS S.A.S 2017-06-28 15:31:04
    * @param  null
    * @return  void
    * Descripción: Actualiza un registro en la tabla interest_contracts
    */
    public function editInterestContracts(){
        $data                  = $this->request->data;
        $data['modified']      = 1;
        $data['date_modified'] = date( 'Y-m-d' );

        $this->InterestContract->read( null, $data['id'] );
        $this->InterestContract->set( $data );
        $this->InterestContract->save( $data );

        // Obtiene la lista de los emails de los usuarios que maracaron el email como me interesa
        $emailList = $this->getEmailsList( $data['id'] );

        echo "{ success: true }";
        exit();
        

    }

    /**
    * Carlos Felipe Aguirre Taborda GL STUDIOS S.A.S 2017-06-28 15:42:52
    * @param  null
    * @return  void
    * Descripción: Obtiene una lista de los emails de los usuarios que le han dado me interesa a un contrato en especifico
    */
    private function getEmailsList( $id ){
        $emails    = $this->InterestContract->query("SELECT email FROM users JOIN user_interest_contracts ON users.id = user_interest_contracts.users_id WHERE user_interest_contracts.interest_contracts_id =" . $id );
        $emailList = [];

        if( empty( $emails ) ){

            return [];

        }

        foreach( $emails as $email ){

            $emailList[] = $email['users']['email'];

        }
        return $emailList;

    }

}
