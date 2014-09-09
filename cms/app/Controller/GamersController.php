<?php
App::uses('AppController', 'Controller');

class GamersController extends AppController {

	public $name = 'Gamers';
	public $paginate = array();

	public function beforeFilter() {
		parent::beforeFilter();
		//$this->Auth->allow(array('get_next', 'get_result'));
	}

	public function admin_index() {

		$this->layout = (!$this->params['isAjax']) ? 'admin' : 'ajax';
		$this->set('title_for_layout', __("Usuarios", true));
		
		$this->set('_nav', "users");
		$this->set('_nav_sub', "index");


		// elimino filtros
		if(isset($this->request->params['named']['search']) && $this->request->params['named']['search'] == 'clear-search'){
			$this->Session->delete('App.Gamer.query');
		}
		if(isset($this->request->params['named']['search']) && $this->request->params['named']['search'] == 'clear-filters'){
			$this->Session->delete('App.Gamer.filters');
		}

		// seteo filtros
		if(isset($this->request->data['Search']['query'])){
			$this->Session->write('App.Gamer.query', $this->request->data['Search']['query']);
		}

		// aplico filtros
		$query = $this->Session->read('App.Gamer.query');
		if(!empty($query)){
			$this->paginate['conditions']['OR'] = array(
				'Gamer.name LIKE' => '%'.$query.'%'
			);
			$this->request->data['Search']['query'] = $query;
		}
		
		$this->paginate['order'] = 'Gamer.created DESC';
		$this->paginate['limit'] = 30;

		$items = $this->paginate() ;

		$this->set( 'items', $items );

		$this->backUrl = true;

		$this->set('model', $this->modelClass);
		$this->set('model_url', $this->modelKey);
		$this->set('controller', $this->request->params['controller']);
		
	}

	public function admin_edit( $id = null ) {
	
		$this->layout = 'admin';
		$this->set('title_for_layout', __("Usuarios", true));

		$this->set('_nav', "users");
		$this->set('_nav_sub', (empty($id)) ? "add" : "index");

		if ($this->request->is('post')||$this->request->is('put')) {

			if( $this->Gamer->save($this->request->data)) {
			    
				$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __("Data has been saved successfully.", true), 'default', array( 'class' => 'alert alert-success') );
				$this->admin_back( array('action' => 'index') );
			}
			
		} else if ( !empty($id) ) {
			$this->request->data = $this->Gamer->read(null, $id);
		}

		$this->set('model', $this->modelClass);
		$this->set('model_url', $this->modelKey);
		$this->set('controller', $this->request->params['controller']);
		
	}

	public function admin_delete( $id = false ) {

		if(!empty($id)){
			$this->Gamer->delete($id);
		} else {
			foreach($this->request->data['Index'] as $id){
				if(!empty($id) && $id != 0 && $id != 'all'){
					$this->Gamer->delete($id);
				}
			}
		}

		$this->admin_back(array('action' => 'index'));

	}

}
