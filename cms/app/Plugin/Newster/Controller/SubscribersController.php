<?php //Ñandú

class SubscribersController extends NewsterAppController {

	var $name = 'Subscribers';
	var $paginate = array( 'limit' => 15, 'page' => 1 , 'order' => 'Subscriber.created DESC' );
	var $components = array('Email','RequestHandler');

	function admin_index() {
	
    	$this->set('title_for_layout', __('Suscriptores', true));

	    //$this->set('_selected_menu', "newsletters");
	    //$this->set('_selected_submenu', "subscribers");
	
		if(!empty($this->request->data['Subscriber']['action'])){
			
			$action = explode('_', $this->data['Subscriber']['action']);
			
			switch( $action[0] ){

				case 'active':
					foreach($this->data['Subscriber']['id'] as $id => $checked ){
						if($checked == 1){
							$this->Subscriber->id = $id;
							$this->Subscriber->saveField('active', 1);
						} 
					}

					$this->Session->setFlash(__('Se han activado los suscriptores seleccionados.', true), 'default', array('class' => 'successBoxSmall'));
					unset( $this->data['Subscriber'] );
					break;

				case 'desactive':
					foreach($this->data['Subscriber']['id'] as $id => $checked ){
						if($checked == 1){
							$this->Subscriber->id = $id;
							$this->Subscriber->saveField('active', 0);
						} 
					}

					$this->Session->setFlash(__('Se han desactivado los suscriptores seleccionados.', true), 'default', array('class' => 'successBoxSmall'));
					unset( $this->data['Subscriber'] );
					break;

				case 'addlist':
				
					$newsletterslist_id = $action[1];
				
					foreach($this->data['Subscriber']['id'] as $id => $checked ){
						if($checked == 1){
							$exist = $this->Subscriber->NewsletterslistsSubscriber->find('first', array('conditions' => array('NewsletterslistsSubscriber.subscriber_id' => $id, 'NewsletterslistsSubscriber.newsletterslist_id' => $newsletterslist_id)));
							if( !$exist ){
								$this->Subscriber->NewsletterslistsSubscriber->create();
								$data = array(
									'subscriber_id' => $id,
									'newsletterslist_id' => $newsletterslist_id
								);
								$this->Subscriber->NewsletterslistsSubscriber->save( $data );
							}
						} 
					}

					$this->Session->setFlash(__('Se han agregado los suscriptores a la lista seleccionada.', true), 'default', array('class' => 'successBoxSmall'));
					unset( $this->data['Subscriber'] );
					break;
				
			}
			

		}

	    // quita filtros
	    if(isset($this->params['named']['search']) && $this->params['named']['search'] == 'clear-search'){
			$this->Session->delete('App.Newster.Subscribers.query');
		}

		// seteo de filtros
		if(!empty($this->request->data['Search']['query'])){
			$this->Session->write('App.Newster.Subscribers.query', $this->data['Search']['query']);
		}
		
		// aplico filtros
		if( $this->Session->check('App.Newster.Subscribers.query')){
			$this->paginate['conditions']['OR'] = array(
				'Subscriber.last_name LIKE' => '%'.$this->Session->read('App.Newster.Subscribers.query').'%',
				'Subscriber.email LIKE' => '%'.$this->Session->read('App.Newster.Subscribers.query').'%'
			);
			$this->request->data['Search']['query'] = $this->Session->read('App.Newster.Subscribers.query');
		}

		// solo trae listas
		$this->paginate['contain'] = array(
				'SubscribersList'
			);

		$this->Subscriber->Behaviors->attach('Containable');

		$items = $this->paginate();
		//pr( $items);

		$this->set( 'items', $items);

		$this->Session->write('App.returnTo', $this->here );
		
		$actions = array('active' => __('Activar', true), 'desactive' => __('Desactivar', true));

		$newsletterlists = $this->Subscriber->Newsletterlist->find('list', array('fields' => array('id','title')));

		$this->set('newsletterlists', $newsletterlists );

		/*foreach ($newsletterslists as $id => $name) {
			$actions['addlist_'.$id] = __('Agregar a lista: ', true).' '.$name;
		}*/
			
		$this->set('actions', $actions); 
	
    }


	function admin_edit( $id = false ) {

        $this->layout = 'admin';
    	$this->set('title_for_layout', __('Suscriptores', true));

	    $this->set('_selected_menu', "newsletters");
	    $this->set('_selected_submenu', "subscribers");

	    // return page handling
		if ( $this->Session->check('App.returnTo') ) : $returnTo = $this->Session->read('App.returnTo'); else: $returnTo = 'index'; endif;
		$this->set( 'returnTo', $returnTo );

		if ( !empty($this->request->data) ) {
			
			if(empty($this->request->data['Subscriber']['id'])){
				$this->request->data['Subscriber']['added_manually'] = 1;
			}

			if($this->Subscriber->save($this->request->data)){
				$this->redirect( array('action' => 'index') );
			}

        } else if ( !empty($id) ) {
			$this->request->data = $this->Subscriber->read(null, $id);
  		}

  		// listas
  		$newsletterlists = $this->Subscriber->Newsletterlist->find('list', array('fields' => array('id', 'title')));
  		$this->set('newsletterlists', $newsletterlists);

    }

	function admin_import($id = '') {
		Configure::write( 'debug', 2);
		ini_set("memory_limit","128M");
		set_time_limit( 600);

        $this->layout = 'admin';
    	$this->set('title_for_layout', __('Suscriptores', true));

	    $this->set('_selected_menu', "newsletters");
	    $this->set('_selected_submenu', "subscribers");

	    // return page handling
		if ( $this->Session->check('App.returnTo') ) : $returnTo = $this->Session->read('App.returnTo'); else: $returnTo = 'index'; endif;
		$this->set( 'returnTo', $returnTo );

	    $this->set('imported', 0);
	
        if ( !empty($this->data)) {
        	
        	$this->Subscriber->validate = array();
        	
		    if( empty($this->data['Subscriber']['import_file']['tmp_name']) ){
			    $this->Subscriber->invalidate('import_file', 'Seleccione el archivo a importar.');
		    }

			//echo $this->data['Subscriber']['import_file']['tmp_name'];
		    
		    if( $this->Subscriber->validates() ){
				//echo $this->data['Subscriber']['import_file']['tmp_name'];
				
				if ( $checkImportFile = $this->isUploadedFile( $this->data['Subscriber']['import_file'] ) ){
					
					
					$fileContents = file_get_contents( $this->data['Subscriber']['import_file']['tmp_name'] );
					//$fileContents = '';
					// si es microsoft outlook
					$imported = $this->Subscriber->importFromFile( $fileContents, array( 'source' => $this->data['Subscriber']['app']) );

					if($imported !== false){						
						$this->set('imported', 1);
					    $this->set('emailsTotal', $imported);
					}
					
				}

		    }

        } elseif (!empty($id)) {
			$this->data = $this->Subscriber->read(null, $id);
  		}

  		// listas
  		$apps = array(
			'outlook-express' => 'Outlook Express',
			'outlook-office' => 'Microsoft Outlook (Office)'
		);
  		$this->set('apps', $apps);
  		
    }

    function admin_delete( $id ) {

    	$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'Item(s) eliminado(s) con éxito'), 'default', array( 'class' => 'alert alert-success') );

		if(!empty($id)){
			$this->Subscriber->delete($id);
		} else {
			foreach($this->request->data['Index'] as $id){
				if(!empty($id) && $id != 0 && $id != 'all'){
					$this->Subscriber->delete($id);
				}
			}
		}
		$this->redirect( array( 'action' => 'index') );

    }

    function admin_active( $id ) {

    	$count = 0;

		if(!empty($id)){
			$this->Subscriber->id = $id;
			$this->Subscriber->saveField( 'active', 1);
			$count++;
		} else {
			foreach($this->request->data['Index'] as $id){
				if(!empty($id) && $id != 0 && $id != 'all'){
					$this->Subscriber->id = $id;
					$this->Subscriber->saveField( 'active', 1);
					$count++;
				}
			}
		}

    	if( $count > 1){
	    	$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'Items marcados como activos'), 'default', array( 'class' => 'alert alert-success') );
	    }elseif( $count == 1){
	    	$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'Item marcado como activo'), 'default', array( 'class' => 'alert alert-success') );
	    }

		$this->redirect( array( 'action' => 'index') );

    }

    function admin_unactive( $id ) {

    	$count = 0;

		if(!empty($id)){
			$this->Subscriber->id = $id;
			$this->Subscriber->saveField( 'active', 0);
			$count++;
		} else {
			foreach($this->request->data['Index'] as $id){
				if(!empty($id) && $id != 0 && $id != 'all'){
					$this->Subscriber->id = $id;
					$this->Subscriber->saveField( 'active', 0);
					$count++;
				}
			}
		}

    	if( $count > 1){
	    	$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'Suscriptor desactivados'), 'default', array( 'class' => 'alert alert-success') );
	    }elseif( $count == 1){
	    	$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'Suscriptor desactivado'), 'default', array( 'class' => 'alert alert-success') );
	    }

		$this->redirect( array( 'action' => 'index') );

    }

    function admin_addtolist( $list_id ) {

    	$count = 0;

		if(!empty($this->request->data['Index'] ) ){
			foreach($this->request->data['Index'] as $id){
				if(!empty($id) && $id != 0 && $id != 'all'){

					// chequeo que no exista
					$options = array(
						'conditions' => array(
							'subscriber_id' => $id,
							'newsletterlist_id' => $list_id						
							)
						);

					$exists = $this->Subscriber->SubscribersList->find('first', $options);

					if( !$exists){

						$data = array(
							'subscriber_id' => $id,
							'newsletterlist_id' => $list_id
							);
						$this->Subscriber->SubscribersList->create($data);
						$this->Subscriber->SubscribersList->save();
						$count++;

					}
				}
			}
		}

    	if( $count > 1){
	    	$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'Suscriptores agregados a la lista'), 'default', array( 'class' => 'alert alert-success') );
	    }elseif( $count == 1){
	    	$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'Suscriptor agregado a la lista'), 'default', array( 'class' => 'alert alert-success') );
	    }

		$this->redirect( array( 'action' => 'index') );

    }


    function admin_removefromlist( $list_id ) {

    	$count = 0;

		if(!empty($this->request->data['Index'] ) ){
			foreach($this->request->data['Index'] as $id){
				if(!empty($id) && $id != 0 && $id != 'all'){

					// chequeo que no exista
					$options = array(
							'subscriber_id' => $id,
							'newsletterlist_id' => $list_id
						);

					$this->Subscriber->SubscribersList->deleteAll( $options);
				}
			}
		}

    	if( $count > 1){
	    	$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'Suscriptores eliminados de la lista'), 'default', array( 'class' => 'alert alert-success') );
	    }elseif( $count == 1){
	    	$this->Session->setFlash('<a class="close" data-dismiss="alert" href="#">×</a> '. __d( 'newster', 'Suscriptor eliminado de la lista'), 'default', array( 'class' => 'alert alert-success') );
	    }

		$this->redirect( array( 'action' => 'index') );

    }

	// PUBLIC

    function optout( $email = '' ) {

        $this->layout = 'subscriber';
    	$this->set('title_for_layout', __('Desuscripción', true));
  		$this->set('email', $email);
    	$this->set('confirm', 0);

        $subscriber = $this->Subscriber->find('first', array('conditions' => array('Subscriber.email' => $email, 'Subscriber.optout <>' => 1)));
    	$this->set('subscriber', $subscriber);
        
        if($subscriber){
        	
        	if(isset($this->params['named']['confirm']) && $this->params['named']['confirm'] == 1){

				$this->Subscriber->id = $subscriber['Subscriber']['id'];
				$this->Subscriber->saveField('optout_date', date('Y-m-d'));
				$this->Subscriber->saveField('optout_ip', $this->RequestHandler->getClientIP());
				$this->Subscriber->saveField('optout', 1);
				
				// envio notificación por email
			    $this->Email->reset();
				$this->Email->to = $this->_options['Newslettersoption']['notifications_email'];
				$this->Email->subject = 'Suscripciones';
				$this->Email->from = '<no-reply@envero.org>';
				$this->Email->sendAs = 'html';
				$mailBody[] = "Suscripciones";
				$mailBody[] = "<br>----------------------------------------------";
				$mailBody[] = "<br>Se ha desuscripto el usuario: <strong>". $subscriber['Subscriber']['name'].' ('.$subscriber['Subscriber']['email'].')</strong>';
				$mailBody = join("\n", $mailBody );

			    $this->Email->send( $mailBody );

		    	$this->set('confirm', 1);

	    	}

        }

    }

    function reportspam( $email = '' ) {

        $this->layout = 'subscriber';
    	$this->set('title_for_layout', __('Reportar SPAM', true));
  		$this->set('email', $email);
    	$this->set('confirm', 0);

        $subscriber = $this->Subscriber->find('first', array('conditions' => array('Subscriber.email' => $email)));
    	$this->set('subscriber', $subscriber);
        
        if($subscriber){
        	
        	if(isset($this->params['named']['confirm']) && $this->params['named']['confirm'] == 1){

				$this->Subscriber->id = $subscriber['Subscriber']['id'];
				$this->Subscriber->saveField('spam_reported', 1);
				$this->Subscriber->saveField('spam_reported_date', date('Y-m-d'));
				$this->Subscriber->saveField('spam_reported_ip', $this->RequestHandler->getClientIP());
				$this->Subscriber->saveField('optout', 1);
				$this->Subscriber->saveField('optout_date', date('Y-m-d'));
				$this->Subscriber->saveField('optout_ip', $this->RequestHandler->getClientIP());
				
				// envio notificación por email
			    $this->Email->reset();
				$this->Email->to = $this->_options['Newslettersoption']['notifications_email'];
				$this->Email->subject = 'Reporte de SPAM';
				$this->Email->from = '<no-reply@envero.org>';
				$this->Email->sendAs = 'html';
				$mailBody[] = "Reporte de SPAM";
				$mailBody[] = "<br>----------------------------------------------";
				$mailBody[] = "<br>Se ha reportado como SPAM al usuario: <strong>". $subscriber['Subscriber']['name'].' ('.$subscriber['Subscriber']['email'].')</strong>';
				$mailBody = join("\n", $mailBody );

			    $this->Email->send( $mailBody );

		    	$this->set('confirm', 1);

	    	}

        }

    }


}
?>
