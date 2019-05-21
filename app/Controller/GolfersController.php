<?php
App::uses('AppController', 'Controller');
/**
 * Golfers Controller
 *
 * @property Golfer $Golfer
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 */
class GolfersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash');

/**
 * index method
 *
 * @return void
 */
	public function index() {

		$this->autoRender = false;

		$golfersArr  = Array();

		$golfers = $this->Golfer->find('all');

		foreach ($golfers as $golfer) {
			
			$golfersArr[] = $golfer['Golfer'];
		}


		echo  json_encode($golfersArr);


		// $this->Golfer->recursive = 0;
		// $this->set('golfers', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Golfer->exists($id)) {
			throw new NotFoundException(__('Invalid golfer'));
		}
		$options = array('conditions' => array('Golfer.' . $this->Golfer->primaryKey => $id));
		$this->set('golfer', $this->Golfer->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Golfer->create();
			if ($this->Golfer->save($this->request->data)) {
				return $this->flash(__('The golfer has been saved.'), array('action' => 'index'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Golfer->exists($id)) {
			throw new NotFoundException(__('Invalid golfer'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Golfer->save($this->request->data)) {
				return $this->flash(__('The golfer has been saved.'), array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('Golfer.' . $this->Golfer->primaryKey => $id));
			$this->request->data = $this->Golfer->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Golfer->id = $id;
		if (!$this->Golfer->exists()) {
			throw new NotFoundException(__('Invalid golfer'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Golfer->delete()) {
			return $this->flash(__('The golfer has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The golfer could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}
}
