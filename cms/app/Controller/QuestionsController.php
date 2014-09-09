<?php
App::uses('AppController', 'Controller');

class QuestionsController extends AppController {

	public $name = 'Questions';
	public $paginate = array();

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('get_next', 'get_result'));
	}

	public function admin_index() {

		$this->layout = (!$this->params['isAjax']) ? 'admin' : 'ajax';
		$this->set('title_for_layout', __("Preguntas Frecuentes", true));
		
		$this->set('_nav', "questions");
		$this->set('_nav_sub', "index");

		// quito registros temporales
		//$this->paginate['conditions']['Question.robinson <>'] = 1;

		// elimino filtros
		if(isset($this->request->params['named']['search']) && $this->request->params['named']['search'] == 'clear-search'){
			$this->Session->delete('App.Question.query');
		}
		if(isset($this->request->params['named']['search']) && $this->request->params['named']['search'] == 'clear-filters'){
			$this->Session->delete('App.Question.filters');
		}

		// seteo filtros
		if(isset($this->request->data['Search']['query'])){
			$this->Session->write('App.Question.query', $this->request->data['Search']['query']);
		}

		// aplico filtros
		$query = $this->Session->read('App.Question.query');
		if(!empty($query)){
			$this->paginate['conditions']['OR'] = array(
				'Question.title LIKE' => '%'.$query.'%'
			);
			$this->request->data['Search']['query'] = $query;
		}
		
		$this->paginate['order'] = 'Category.title, Question.title';
		$this->paginate['limit'] = 30;

		$this->paginate['contain'] = array('Category');

		$this->Question->Behaviors->attach('Containable');

		$items = $this->paginate() ;

		$this->set( 'items', $items );

		$this->backUrl = true;

		$this->set('model', $this->modelClass);
		$this->set('model_url', $this->modelKey);
		$this->set('controller', $this->request->params['controller']);
		
	}

	public function admin_edit( $id = null ) {
		$this->helpers[] = 'Uploader';
	
		$this->layout = 'admin';
		$this->set('title_for_layout', __("Preguntas Frecuentes", true));

		$this->set('_nav', "questions");
		$this->set('_nav_sub', (empty($id)) ? "add" : "index");

		if ($this->request->is('post')||$this->request->is('put')) {

			// desmarco el registro como temporal
			$this->request->data['Question']['robinson'] = 0;

			if( $this->Question->save($this->request->data)) {
			    
				$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __("Data has been saved successfully.", true), 'default', array( 'class' => 'alert alert-success') );
				$this->admin_back( array('action' => 'index') );
			}
			
		} else if ( !empty($id) ) {
			$this->request->data = $this->Question->read(null, $id);
		}

		$this->set('categories', $this->Question->Category->find('list', array('order' => 'Category.title') ) );

		$this->set('model', $this->modelClass);
		$this->set('model_url', $this->modelKey);
		$this->set('controller', $this->request->params['controller']);
		
	}

	public function admin_delete( $id = false ) {

		if(!empty($id)){
			$this->Question->delete($id);
		} else {
			foreach($this->request->data['Index'] as $id){
				if(!empty($id) && $id != 0 && $id != 'all'){
					$this->Question->delete($id);
				}
			}
		}

		$this->admin_back(array('action' => 'index'));

	}

	//PUBLIC

	public function get_next(){

		header('Content-Type: application/json');

		$this->autoRender = false;
		$this->autoLayout = false;

		$returnJSON = array(
			'status' => 0
			);
		
		//pr($this->params);

		$token = (isset( $this->params->query['token']))?$this->params->query['token']:'';
		$cat_id = (isset( $this->params->query['cat_id']))?$this->params->query['cat_id']:'';

		// { "question_id": "23", "question_title": "Vino don pepito?", "question_source": "Diario", "answers": [ { "id":2,"name": "Si"},{ "id":3,"name": "No"}]}

		// busco preguntas ya resueltas por el usuario
		$options = array(
			'fields' => array('Answer.question_id', 'Answer.question_id'),
			'conditions' => array(
				'Answer.token' => $token
				)
			);

		$prevent = $this->Question->Answer->find('list', $options);

		// busco pregunta 
		$options = array(
			'conditions' => array(
				'Question.category_id' => $cat_id
				),
			'order' => 'RAND()'
			);

		if($prevent){
			$options['conditions']['Question.id NOT IN'] = $prevent;
		}

		$this->Question->recursive = 1;
		$question = $this->Question->find('first', $options);

		if( $question){
			
			$data = array(
				'category_title' => $question['Category']['title'],
				'question_id' => $question['Question']['id'],
				'question_title' => $question['Question']['title'],
				'answers' => array(
						array( 'id' => 1, 'name' => $question['Question']['answer_1']),
						array( 'id' => 2, 'name' => $question['Question']['answer_2']),
						array( 'id' => 3, 'name' => $question['Question']['answer_3']),
						array( 'id' => 4, 'name' => $question['Question']['answer_4'])
					) 
				);

			$returnJSON['status'] = 1;
			$returnJSON['data'] = $data;

		}

		die( json_encode( $returnJSON) );
	}

	public function get_result(){

		header('Content-Type: application/json');

		$this->autoRender = false;
		$this->autoLayout = false;

		$returnJSON = array(
			'status' => 0,
			'data' => array()
			);
		
		//pr($this->params);
		// { "answer_status": "ok", "answer_ok": 2, "score": 110 }

		$token = (isset( $this->params->query['token']))?$this->params->query['token']:'';
		$cat_id = (isset( $this->params->query['cat_id']))?$this->params->query['cat_id']:'';
		$question_id = (isset( $this->params->query['question_id']))?$this->params->query['question_id']:'';
		$answer_id = (isset( $this->params->query['answer_id']))?$this->params->query['answer_id']:'';

		// busco pregunta para verificar resultado
		$question = $this->Question->read( null, $question_id);

		if( $question){
			
			if( $question['Question']['answer_ok'] == $answer_id){
				$returnJSON['data']['answer_status'] = 'ok';
				$returnJSON['data']['answer_ok'] = $question['Question']['answer_ok'];
			}else{
				$returnJSON['data']['answer_status'] = 'ko';
				$returnJSON['data']['answer_ok'] = $question['Question']['answer_ok'];

			}

			$returnJSON['data']['source'] = $question['Question']['source'];
			$returnJSON['status'] = 1;
			$returnJSON['data']['score'] = 0;

		}else{
			// TODO: manejo de errores
		}

		die( json_encode( $returnJSON) );
	}

}
