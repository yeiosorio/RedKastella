<?php
App::uses('AppController', 'Controller');
/**
 * UserPreferences Controller
 *
 * @property UserPreference $UserPreference
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property SessionComponent $Session
 */
class UserPreferencesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->UserPreference->recursive = 0;
		$this->set('userPreferences', $this->Paginator->paginate());
	}

	/**
	 * Función que obtiene las preferencias de color del usuario
	 */
	public function getUserPreferences(){

		$this->autoRender = false;

		$preference = $this->UserPreference->find('first',array('conditions'=>array('UserPreference.users_id'=> $this->Auth->user('id'))));

		if($preference){


			return $preference['UserPreference'];


		}else{


			return null;


		}


	}

	/**
	 * Función que obtiene las preferencias de color del usuario
	 */
	public function getUserPreferencesAjax(){

		$this->autoRender = false;

		$preference = $this->UserPreference->find('first',array('conditions'=>array('UserPreference.users_id'=> $this->Auth->user('id'))));

		if($preference){

			echo json_encode($preference['UserPreference']);

		}else{

			echo json_encode(Array());

		}



	}



	public function savePreference(){

		$this->autoRender = false;

		$data = $this->request->data;
	
		$data['users_id'] = $this->Auth->user('id');

		$userPreference = $this->getUserPreferences();


		if ($userPreference) {
				
			$this->UserPreference->id = $userPreference['id'];

			$this->UserPreference->save($data);
			
		}else{
			
			$this->UserPreference->create();

			$this->UserPreference->save($data);

		}


		$userPreference = $this->getUserPreferences();

        /**
         * asignacion de valor del tema del usuario
         */
        $this->Session->write('Auth.User.skin', $userPreference);

	}



	// public function updatePreference(){

	// 	$this->autoRender = false;

	// 	$data = $this->request->data;

	// 	$this->UserPreference->create();

	// 	$this->UserPreference->id = $data['id'];

	// 	$this->UserPreference->save($data);
		
	// }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->UserPreference->exists($id)) {
			throw new NotFoundException(__('Invalid user preference'));
		}
		$options = array('conditions' => array('UserPreference.' . $this->UserPreference->primaryKey => $id));
		$this->set('userPreference', $this->UserPreference->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->UserPreference->create();
			if ($this->UserPreference->save($this->request->data)) {
				$this->Session->setFlash(__('The user preference has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user preference could not be saved. Please, try again.'));
			}
		}
		$users = $this->UserPreference->User->find('list');
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
		if (!$this->UserPreference->exists($id)) {
			throw new NotFoundException(__('Invalid user preference'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->UserPreference->save($this->request->data)) {
				$this->Session->setFlash(__('The user preference has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user preference could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('UserPreference.' . $this->UserPreference->primaryKey => $id));
			$this->request->data = $this->UserPreference->find('first', $options);
		}
		$users = $this->UserPreference->User->find('list');
		$this->set(compact('users'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->UserPreference->id = $id;
		if (!$this->UserPreference->exists()) {
			throw new NotFoundException(__('Invalid user preference'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->UserPreference->delete()) {
			$this->Session->setFlash(__('The user preference has been deleted.'));
		} else {
			$this->Session->setFlash(__('The user preference could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
