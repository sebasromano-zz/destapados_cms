<?php 

class ListsController extends AppController {

	var $name = 'Lists';
	var $paginate = array( 'limit' => 15, 'page' => 1);

	function admin_index() {
		
        $this->layout = 'admin';
    	$this->set('title_for_layout', __d( 'newster', 'Listas'));

	    //$this->set('_selected_menu', "newsletters");
	    //$this->set('_selected_submenu', "newsletterslists");

	    // quita filtros
	    if(isset($this->params['named']['search']) && $this->params['named']['search'] == 'clear'){
			$this->Session->delete('App.Newsletterslist.query');
		}
		
		// seteo de filtros
		if(!empty($this->data)){
			if(!empty($this->data['Search']['query'])){
				$this->Session->write('App.Newsletterslist.query', $this->data['Search']['query']);
			} else {
				$this->Session->delete('App.Newsletterslist.query');
			}
		}
		
		// aplico filtros
		if($this->Session->check('App.Newsletterslist.query')){
			$this->paginate['conditions']['OR'] = array(
				'Newsletterslist.name LIKE' => '%'.$this->data['Search']['query'].'%',
			);
			$this->data['Search']['query'] = $this->Session->read('App.Newsletterslist.query');
		}

		$this->set( 'items', $this->paginate() );

		/*
		$this->Session->write('App.returnTo', $this->here );

		// opciones para newsletters
		if( App::import( 'Model', 'Newster.Newslettersoption' ) ){
			$this->Newslettersoption = new Newslettersoption();
		}
		$this->_options = $this->Newslettersoption->read( null, 1 );
		$this->set('_options', $this->_options['Newslettersoption']);*/
	
    }

	function admin_edit( $id = false ) {

        $this->layout = 'admin';
    	$this->set('title_for_layout', __('Listas', true));

	    $this->set('_selected_menu', "newsletters");
	    $this->set('_selected_submenu', "newsletterslists");

	    // return page handling
		if ( $this->Session->check('App.returnTo') ) : $returnTo = $this->Session->read('App.returnTo'); else: $returnTo = 'index'; endif;
		$this->set( 'returnTo', $returnTo );

		if ( !empty($this->data) ) {

			if($this->Newsletterslist->save($this->data)){
				$this->redirect( $returnTo );
			}

        } else if ( !empty($id) ) {
			$this->data = $this->Newsletterslist->read(null, $id);
  		}

    }


    function admin_delete( $id = false ) {

        $this->layout = 'admin';
    	$this->set('title_for_layout', __('Listas', true));

	    $this->set('_selected_menu', "newsletters");
	    $this->set('_selected_submenu', "newsletterslists");

	    // return page handling
		if ( $this->Session->check('App.returnTo') ) : $returnTo = $this->Session->read('App.returnTo'); else: $returnTo = 'index'; endif;
		$this->set( 'returnTo', $returnTo );
	    
		if ( !empty($this->data) ) {
			
			if($this->data['Newsletterslist']['confirm'] == 1){

		        $this->Newsletterslist->delete( $id );
				$this->redirect( $returnTo );

			}

        } else if ( !empty($id) ) {
			$this->data = $this->Newsletterslist->read(null, $id);
  		}

    }
    
    // Subscribers

    function admin_subscribers($newsletterslist_id = '') {

        $this->layout = 'admin';
    	$this->set('title_for_layout', __('Listas', true));

        $this->set('_selected_menu', "newsletters");
	    $this->set('_selected_submenu', "newsletterslists");

        // guardo la lista seleccionada
        if(!empty($newsletterslist_id)){
			$this->Session->write('Newsletterslist.id', $newsletterslist_id);
		}

		//para el breadcrumbs
		$breadcrumbs = $this->Newsletterslist->find('first', array('conditions' => array('Newsletterslist.id' => $this->Session->read('Newsletterslist.id'))));
		$this->set('breadcrumbs', $breadcrumbs);

	    $this->paginate = array('limit' => 15, 'page' => 1, 'conditions' => array('NewsletterslistsSubscriber.newsletterslist_id' => $this->Session->read('Newsletterslist.id')) );
        $this->set('items', $this->paginate($this->Newsletterslist->NewsletterslistsSubscriber));
        
        //pr( $this->paginate($this->Newsletterslist->NewsletterslistsSubscriber) );
        
		$this->Session->write('App.returnTo', $this->here );

    }

	function admin_subscribersedit($id = '') {

        $this->layout = 'admin';
    	$this->set('title_for_layout', __('Listas', true));

        $this->set('_selected_menu', "newsletters");
	    $this->set('_selected_submenu', "newsletterslists");

		//para el breadcrumbs
		$breadcrumbs = $this->Newsletterslist->find('first', array('conditions' => array('Newsletterslist.id' => $this->Session->read('Newsletterslist.id'))));
		$this->set('breadcrumbs', $breadcrumbs);

	    // return page handling
		if ( $this->Session->check('App.returnTo') ) : $returnTo = $this->Session->read('App.returnTo'); else: $returnTo = 'index'; endif;
		$this->set( 'returnTo', $returnTo );
		
        if (!empty($this->data)) {


			$this->NewsletterslistsSubscriber = $this->Newsletterslist->NewsletterslistsSubscriber;

			$this->NewsletterslistsSubscriber->set( $this->data );

			// relaciono el suscriptor a la lista que pertenece
		    $this->data['NewsletterslistsSubscriber']['newsletterslist_id'] = $this->Session->read('Newsletterslist.id');

			if ( $this->NewsletterslistsSubscriber->validates() ){
	            if($this->NewsletterslistsSubscriber->save( $this->data )){
					$this->redirect( $returnTo );
	            }
            }

        } elseif (!empty($id)) {
			$this->data = $this->Newsletterslist->NewsletterslistsSubscriber->read(null, $id);
  		}

  		// listas
  		$subscribers = $this->Newsletterslist->Subscriber->find('list', array('fields' => array('id', 'name')));
  		$this->set('subscribers', $subscribers);
  		
    }

	function admin_subscribersimport($id = '') {

        $this->layout = 'admin';
    	$this->set('title_for_layout', __('Listas', true));

        $this->set('_selected_menu', "newsletters");
	    $this->set('_selected_submenu', "newsletterslists");

		//para el breadcrumbs
		$breadcrumbs = $this->Newsletterslist->find('first', array('conditions' => array('Newsletterslist.id' => $this->Session->read('Newsletterslist.id'))));
		$this->set('breadcrumbs', $breadcrumbs);

	    // return page handling
		if ( $this->Session->check('App.returnTo') ) : $returnTo = $this->Session->read('App.returnTo'); else: $returnTo = 'index'; endif;
		$this->set( 'returnTo', $returnTo );

	    $this->set('imported', 0);
	
        if (!empty($this->data)) {
        	
		    if( empty($this->data['Newsletterslist']['import_file']['tmp_name']) ){
			    $this->Newsletterslist->invalidate('import_file', 'Seleccione el archivo a importar.');
		    }
		    
		    if( $this->Newsletterslist->validates() ){

				if ( $checkImportFile = $this->isUploadedFile( $this->data['Newsletterslist']['import_file'] ) ){
					
					$fileContents = file_get_contents( $this->data['Newsletterslist']['import_file']['tmp_name'] );
					
					// si es microsoft outlook
					$imported = $this->Newsletterslist->Subscriber->importFromFile( $fileContents, array( 'source' => $this->data['Newsletterslist']['app'], 'list' => $this->Session->read('Newsletterslist.id') ) );

					if($imported !== false){						
						$this->set('imported', 1);
					    $this->set('emailsTotal', $imported);
					}
					
				}

		    }

        } elseif (!empty($id)) {
			$this->data = $this->Newsletterslist->Subscriber->read(null, $id);
  		}

  		// listas
  		$apps = array(
			'outlook-express' => 'Outlook Express',
			'outlook-office' => 'Microsoft Outlook (Office)'
		);
  		$this->set('apps', $apps);
  		
    }

    function admin_subscribersdelete($id) {

        $this->Newsletterslist->NewsletterslistsSubscriber->delete($id);

	    // return page handling
		if ( $this->Session->check('App.returnTo') ) : $returnTo = $this->Session->read('App.returnTo'); else: $returnTo = 'index'; endif;
		$this->redirect( $returnTo );

    }


}
?>
