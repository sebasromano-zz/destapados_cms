<?php
App::uses('Controller', 'Controller');

class AppController extends Controller {

	public $components = array('Auth', 'Session', 'Cookie', 'RequestHandler' );
	public $helpers = array('Session', 'Html', 'Form', 'Time', 'Text', 'Paginator', 'Js' => array('Jquery'), 'Util', 'Envero');

	public function beforeFilter(){ 

		// cache
		Cache::config('default', array(
			'engine' => 'File',
			'duration' => '+1 month'
		));

		$this->lang = 'spa';
		Configure::write('Config.language', $this->lang);
		$this->Session->write('Config.language', $this->lang);

		// auth
		$this->Auth->authenticate = array(
			'Form' => array(
				'userModel' => 'User',
				'fields' => array('username' => 'username', 'password' => 'password'),
				'scope' => array('User.active' => 1)
			)
		);
		//$this->Auth->loginRedirect = array('admin' => true, 'controller' => 'pages', 'action' => 'index');
		$this->Auth->loginRedirect = array('admin' => true, 'controller' => 'questions', 'action' => 'index');
		$this->Auth->logoutRedirect = array('admin' => false, 'controller' => 'users', 'action' => 'login');
		$this->Auth->loginAction = array('admin' => false, 'controller' => 'users', 'action' => 'login');
		$this->Auth->loginError = __('Username or password incorrect', true);
		$this->Auth->authError = __('Username or password incorrect', true);


	}


	// back url
	function afterFilter() {
		if(isset($this->backUrl) && $this->backUrl && isset($this->request->params['controller']) && isset($this->request->params['action'])) {
			$this->Session->write('Back.'.mb_strtolower($this->request->params['controller']).'.url', $this->here);
		}
	}

	function admin_back($default_url = '/') {
		if(isset($this->request->params['controller']) && $this->Session->check('Back.'.mb_strtolower($this->request->params['controller']).'.url') ) {
			$url = $this->Session->read('Back.'.mb_strtolower($this->request->params['controller']).'.url');
			$url = str_replace( 'cms/', '', $url);
			$this->redirect($url);
		} else {
			$this->redirect($default_url);
		}
	}

	// set language
	function setLanguage() {
		if( $this->Cookie->read('lang') && !$this->Session->check('Config.language') ) {
			$this->Session->write('Config.language', $this->Cookie->read('lang'));
		} else if( isset($this->request->params['language']) && ($this->request->params['language'] != $this->Session->read('Config.language')) ) {
			$this->Session->write('Config.language', $this->request->params['language']);
			$this->Cookie->write('lang', $this->request->params['language'], false, '20 days');
		} else if( !$this->Session->check('Config.language') ) {
			$this->Session->write('Config.language', $this->lang);
		}
		$this->lang = $this->Session->read('Config.language');
		$this->set('lang', $this->lang);
	}


}
