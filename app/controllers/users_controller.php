<?php
class UsersController extends AppController {

	var $name = 'Users';

	function beforeFilter() {
		parent::beforeFilter();
		// On successful login redirect the user to the 'logLogin' action
		$this->Auth->loginRedirect = array('action' => 'logLogin');
		$this->User->recursive = 0;
	}

	/**
	 *  The AuthComponent provides the needed functionality
	 *  for login, so you can leave this function blank.
	 */
	function login() {
	}

	/**
	 *	Used for logging when a user logs in, then redirecting the user.
	 */
	function logLogin() {
		if ($this->Auth->user()) {
			$this->User->customLog('login', $this->Auth->user('id'), array('title' => $this->Auth->user('username'), 'description' => 'Login'));
			$this->redirect(array('controller' => 'nodes','action' => 'index'));
		} else {
			$this->redirect($this->Auth->logout());
		}
	}

	function logout() {
		$this->User->customLog('logout', $this->Auth->user('id'), array('title' => $this->Auth->user('username'), 'description' => 'Logout'));
		$this->redirect($this->Auth->logout());
	}

	function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect($this->referer());
		}
		$user = $this->User->read(null, $id);
		$logs = $this->paginate('Log',array('user_id'=>$user['User']['id']));
		if (empty($user)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect($this->referer());
		}
		$this->set(compact('user','logs'));
	}

	function add() {
		if (!empty($this->data)) {
			$this->User->create();
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$user = $this->User->read(null, $id);
			if (empty($user)) {
				$this->Session->setFlash(__('Invalid user', true));
				$this->redirect($this->referer());
			}
			$this->data = $user;
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>
