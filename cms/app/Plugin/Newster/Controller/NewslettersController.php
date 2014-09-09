<?php
App::uses('AppController', 'Controller');

class NewslettersController extends NewsterAppController {

	var $name = 'Newsletters';
	var $paginate = array( 'limit' => 15, 'page' => 1 , 'order' => 'Newsletter.created DESC' );

	function admin_index() {

    	$this->set('title_for_layout', __d( 'newster', 'Boletines') );

	    //$this->set('_selected_menu', "newsletters");
	    //$this->set('_selected_submenu', "index");

	    // quita filtros
	    if(isset($this->params['named']['search']) && $this->params['named']['search'] == 'clear-search'){
			$this->Session->delete('App.Newster.Newsletter');
		}
		
		// seteo de filtros
		if(!empty($this->data)){
			if(!empty($this->data['Search']['query'])){
				$this->Session->write('App.Newster.Newsletter.query', $this->data['Search']['query']);
			} else {
				$this->Session->delete('App.Newster.Newsletter.query');
			}
		}
		
		// aplico filtros
		$q = $this->Session->read('App.Newster.Newsletter.query');
		if( !empty($q)){
			$this->paginate['conditions']['OR'] = array(
				'Newsletter.newsletter_title LIKE' => '%'.$q.'%'
			);
			$this->request->data['Search']['query'] = $q;
		}

		$this->set( 'items', $this->paginate() );
	
    }


	function admin_edit( $id = false ) {

    	$this->set('title_for_layout', __d( 'newster', 'Boletines') );

		if ( !empty($this->request->data) ) {

			// el estado por defecto
			if( empty($this->request->data['Newsletter']['id']) ){
				$this->request->data['Newsletter']['status'] = 1;
			}

			if( !empty($this->request->data['Newsletter']['send_date']) ){
				$finalDayAndHour = '';
				$dayAndHour = explode( ' ', $this->request->data['Newsletter']['send_date']);
				//corrijo fecha
				if( !empty( $dayAndHour[0])){
					$day = explode( '/', $dayAndHour[0]);
				}
				// junto las dos cosas
				$finalDayAndHour = $day[2].'-'.$day[1].'-'.$day[0].' '.$dayAndHour[1];
				$this->request->data['Newsletter']['send_date'] = $finalDayAndHour;
			}

			if(isset($this->request->data['Newsletter']['Newsletterlist']) && !empty($this->request->data['Newsletter']['Newsletterlist'])){
				$this->request->data['Newsletter']['lists'] = serialize( $this->request->data['Newsletter']['Newsletterlist']);
			}else{
				$this->request->data['Newsletter']['lists'] = '';
			}

			if($this->Newsletter->save($this->request->data)){
				$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'Datos guardados con éxito'), 'default', array( 'class' => 'alert alert-success') );
				$this->redirect( array( 'action' => 'index') );
			}

        } else if ( !empty($id) ) {

			$this->request->data = $this->Newsletter->read(null, $id);

			// IMPORTANTE !!
			// Si se está enviado o es enviado no pueden editar
			if( in_array( $this->request->data['Newsletter']['status'], array( 2,3,4) ) ){
				$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'No es posible editar un boletín que está en proceso o ha sido enviado'), 'default', array( 'class' => 'alert alert-success') );
				$this->redirect( array( 'action' => 'index') );
			}
			// --------------


			if( !empty( $this->request->data['Newsletter']['lists']) ){
				$this->request->data['Newsletter']['Newsletterlist'] = unserialize( $this->request->data['Newsletter']['lists']);
			}
			if( !empty( $this->request->data['Newsletter']['send_date']) ){
				$finalDayAndHour = '';
				$dayAndHour = explode( ' ', $this->request->data['Newsletter']['send_date']);
				//corrijo fecha
				if( !empty( $dayAndHour[0])){
					$day = explode( '-', $dayAndHour[0]);
				}
				// junto las dos cosas
				$finalDayAndHour = $day[2].'/'.$day[1].'/'.$day[0].' '.$dayAndHour[1];
				$this->request->data['Newsletter']['send_date'] = $finalDayAndHour;				
			}
  		}

		// tags relacionados
		$this->loadModel('Newster.Newsletterlist');
		$this->set( 'newsletterlists', $this->Newsletterlist->find('list') );

    }

    function admin_delete( $id ) {

    	$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'Item eliminado con éxito'), 'default', array( 'class' => 'alert alert-success') );

		if(!empty($id)){
			$this->Newsletter->delete($id);
		} else {
			foreach($this->request->data['Index'] as $id){
				if(!empty($id) && $id != 0 && $id != 'all'){
					$this->Newsletter->delete($id);
				}
			}
		}
		$this->redirect( array( 'action' => 'index') );

    }

	function view( $id = false ) {

		//pr( $this->Newsletter->NewslettersNews );

        $this->layout = 'email/html/default';
    	$this->set('title_for_layout', __('Newsletters', true));

		$this->Newsletter->Behaviors->attach('Containable');
		$this->Newsletter->contain(array('NewslettersNews' => array('order' => 'NewslettersNews.order_by'), 'NewslettersNews.News.Newsimage'));
		//$this->Newsletter->contain(array('News.Newsimage'));

		$newsletter = $this->Newsletter->find('first', array('conditions' => array('Newsletter.permalink' => $permalink))); //pr( $newsletter );
		$this->set('id', $newsletter['Newsletter']['id']);
  		$this->set('title', $newsletter['Newsletter']['title']);
		$this->set('date', date('F Y', strtotime($newsletter['Newsletter']['created'])));
  		$this->set('lng', $newsletter['Newsletter']['language']);
  		
  		$items = array();
  		foreach ($newsletter['NewslettersNews'] as $newslettersnews) {
			$items[] = $newslettersnews['News'];
  		}
  		$this->set('items', $items);

    	$this->render('../elements/email/html/newsletter');

    }

    // News

    function admin_news($newsletter_id = '') {
    	
    	$this->set('title_for_layout', __d( 'newster', 'Boletines') );

        //$this->set('_selected_menu', "newsletters");
	    //$this->set('_selected_submenu', "index");




        // guardo el newsletter seleccionado
        if(!empty($newsletter_id)){
			$this->Session->write('App.Newster.Newsletter.id', $newsletter_id);
		}

	    $this->paginate = array(
	    	'limit' => 20, 
	    	'page' => 1, 
	    	'order' => 'NewslettersItem.order_by', 
	    	'conditions' => array('NewslettersItem.newsletter_id' => $this->Session->read('App.Newster.Newsletter.id')) 
	    	);

		// veo si tengo modelo relacionado
		if( isset($this->newsterSettings['relatedModel']) && !empty( $this->newsterSettings['relatedModel']) ){
			
			$rModel = $this->newsterSettings['relatedModel'];

			$this->Newsletter->NewslettersItem->Behaviors->attach('Containable');
			$this->Newsletter->NewslettersItem->bindModel(array(
				'belongsTo' => array(
					'Related' => array(
						'className' => $rModel,
						'foreignKey' => 'related_id'
						)
					)
				), false);

			$this->paginate['contain'] = array('Related');
			//NewslettersItem
		}

		// IMPORTANTE !!
		// Si se está enviado o es enviado no pueden editar
		$newsletter = $this->Newsletter->read( null, $this->Session->read('App.Newster.Newsletter.id') );
		if( in_array( $newsletter['Newsletter']['status'], array( 2,3,4) ) ){
			$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'No es posible editar un boletín que está en proceso o ha sido enviado'), 'default', array( 'class' => 'alert alert-success') );
			$this->redirect( array( 'action' => 'index') );
		}
		// --------------

        $this->set('items', $this->paginate($this->Newsletter->NewslettersItem));

    }

	function admin_newsedit($id = '') {

    	$this->set('title_for_layout', __d( 'newster', 'Boletines') );

        //$this->set('_selected_menu', "newsletters");
	    //$this->set('_selected_submenu', "index");

	    $this->helpers[] = 'Uploader';

		//para el breadcrumbs
		$breadcrumbs = $this->Newsletter->find('first', array('conditions' => array('Newsletter.id' => $this->Session->read('Newsletter.id'))));
		$this->set('breadcrumbs', $breadcrumbs);
		
        if (!empty($this->request->data)) {

        	// campos obligatorios
        	$this->Newsletter->NewslettersItem->validate = array(
        			'order_by' => array('required' => array('rule' => 'notEmpty')),
        			//'title' => array('required' => array('rule' => 'notEmpty'))
        			);

        	// modelo relacionado
  			if( isset($this->newsterSettings['relatedModel']) && !empty( $this->newsterSettings['relatedModel']) ){
  				$_model = $this->newsterSettings['relatedModel'];
        		$this->request->data['NewslettersItem']['related_model'] = $_model;
        	}

			// veo si tengo tamaño personalizado
			if( isset($this->newsterSettings['resizes']) && !empty( $this->newsterSettings['resizes']) ){

				// piso los settings		
				$uploadSettings = array(
					'pic' => array(
						'styles' => $this->newsterSettings['resizes'],
						'path' => ':webroot/files/newslettersitems/:style_:basename.:extension',
                		'quality' => 90
						)
					);
				$this->Newsletter->NewslettersItem->Behaviors->load('Upload.Upload', $uploadSettings );
			}

        	$this->request->data['NewslettersItem']['newsletter_id'] = $this->Session->read('App.Newster.Newsletter.id');

			$this->Newsletter->NewslettersItem->set( $this->request->data);
		    //$this->data['NewslettersNews']['newsletter_id'] = $this->Session->read('Newsletter.id');

			if ( $this->Newsletter->NewslettersItem->save() ){
				$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'Datos guardados con éxito'), 'default', array( 'class' => 'alert alert-success') );
				$this->redirect( array('action' => 'news') );
            }

        } elseif (!empty($id)) {
			$this->data = $this->Newsletter->NewslettersItem->read(null, $id);
  		}
  		
  		// items relacionados
  		if( isset($this->newsterSettings['relatedModel']) && !empty( $this->newsterSettings['relatedModel']) ){

  			$_model = $this->newsterSettings['relatedModel'];

  			if( $this->loadModel( $_model) ){

  				$defaultOptions = array(
  					'limit' => 100
  					);

  				if( isset($this->newsterSettings['listOptions']) ){

  					$options = array_merge( $defaultOptions, $this->newsterSettings['listOptions']);

  				}else{

  					$options = $defaultOptions;

  				}

  				$this->set('items', $this->{$_model}->find('list', $options) );

  			}else{
  				//echo 'problem';
  			}

  		}
  		//$news = $this->Newsletter->NewslettersNews->News->find('list', array('fields' => array('News.id', 'News.title_spa')));
  		//$this->set('news', $news);
  		
    }

    function admin_newsdelete($id) {

    	$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'Item eliminado con éxito'), 'default', array( 'class' => 'alert alert-success') );

		if(!empty($id)){
			$this->Newsletter->NewslettersItem->delete($id);
		} else {
			foreach($this->request->data['Index'] as $id){
				if(!empty($id) && $id != 0 && $id != 'all'){
					$this->Newsletter->NewslettersItem->delete($id);
				}
			}
		}        
	    $this->redirect( array('action' => 'news') );

    }

}