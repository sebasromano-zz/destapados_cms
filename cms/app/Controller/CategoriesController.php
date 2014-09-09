<?php
App::uses('AppController', 'Controller');
class CategoriesController extends AppController {

	public $name = 'Categories';
	public $paginate = array();

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow( array('get_list') );
	}

	public function admin_index() {

		$this->layout = (!$this->params['isAjax']) ? 'admin' : 'ajax';
		$this->set('title_for_layout', __("Pages", true));
		
		$this->set('_nav', "zones");
		$this->set('_nav_sub', "index");

		// quito registros temporales
		//$this->paginate['conditions']['Page.robinson <>'] = 1;

		// elimino filtros
		if(isset($this->request->params['named']['search']) && $this->request->params['named']['search'] == 'clear-search'){
			$this->Session->delete('App.Zone.query');
		}

		// seteo filtros
		if(isset($this->request->data['Search']['query'])){
			$this->Session->write('App.Zone.query', $this->request->data['Search']['query']);
		}

		// aplico filtros
		$query = $this->Session->read('App.Zone.query');
		if(!empty($query)){
			$this->paginate['conditions']['OR'] = array(
				'Zone.title LIKE' => '%'.$query.'%'
			);
			$this->request->data['Search']['query'] = $query;
		}
		
		$this->paginate['order'] = 'Zone.title';
		$this->paginate['limit'] = 20;

		$items = $this->paginate();
		$this->set( 'items', $items );
		//$items = $this->Page->generateTreeList($this->paginate['conditions'], null, null, '→');

		$this->set('uneditable', array(2,3));

		$this->backUrl = true;

		$this->set('model', $this->modelClass);
		$this->set('model_url', $this->modelKey);
		$this->set('controller', $this->request->params['controller']);
	}
	
	public function admin_edit( $id = null ) {

		$this->helpers[] = 'Uploader';
	
		$this->layout = 'admin';
		$this->set('title_for_layout', __("Pages", true));

		$this->set('_nav', "zones");
		$this->set('_nav_sub', (empty($id)) ? "add" : "index");

		if ($this->request->is('post')||$this->request->is('put')) {

			// desmarco el registro como temporal
			//$this->request->data['Page']['robinson'] = 0;

			if( $this->Zone->save($this->request->data)) {
				$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __("Data has been saved successfully.", true), 'default', array( 'class' => 'alert alert-success') );
				$this->admin_back( array('action' => 'index') );
			}
			
		} else if ( !empty($id) ) {
			$this->request->data = $this->Zone->read(null, $id);
		}

		//$this->set('pages', $this->Page->generateTreeList(array('Page.id <>' => $id), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;'));

		$this->set('model', $this->modelClass);
		$this->set('model_url', $this->modelKey);
		$this->set('controller', $this->request->params['controller']);
		
	}

	public function admin_delete( $id = false ) {

		if(!empty($id)){
			$this->Zone->delete($id);
		} else {
			foreach($this->request->data['Index'] as $id){
				if(!empty($id) && $id != 0 && $id != 'all'){
					$this->Zone->delete( $id );
				}
			}
		}

		$this->admin_back(array('action' => 'index'));

	}

	public function get_list(){

		header('Content-Type: application/json');

		$this->autoRender = false;
		$this->autoLayout = false;

		$returnJSON = array(
			'status' => 0
			);

		$categories = $this->Category->find('list', array('order' => 'Category.order_by, Category.title'));


		if( $categories){
			
			$data = array();

			foreach( $categories as $cat_id => $value){
				$data[] = array(
					'id' => $cat_id,
					'name' => $value,
					);
			}

			$returnJSON['status'] = 1;
			$returnJSON['data'] = $data;

		}

		die( json_encode( $returnJSON) );
	}

}