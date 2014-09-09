<?php //Ñandú

class SubmissionsController extends NewsterAppController {

	var $name = 'Submissions';
	var $paginate = array( 'limit' => 15, 'page' => 1 , 'order' => 'SuscriptionsDispatch.created DESC' );
	var $components = array('Email','RequestHandler');
	var $uses = array('Newster.Queue');

	function beforeFilter(){
		$this->Auth->allow( array('queue','send', 'view') );
		parent::beforeFilter();
	}

	function queue(){

		$this->autoRender = false;
		$this->autoLayout = false;

		// limpia la cache de newsletters (para ener una versión fresca)
		Cache::clear( false, 'newster_newsletters');

		// selecciona envíos que deban encolar
		if( $this->loadModel('Newster.Newsletter') && $this->loadModel('Newster.Subscriber') ){

			$options = array(
				'conditions' => array(
					'Newsletter.send_date <' => date('Y-m-d H:i'), 
					'Newsletter.status' => 1
					)
				);
			
			// veo si tengo modelo relacionado
			if( isset($this->newsterSettings['relatedModel']) && !empty( $this->newsterSettings['relatedModel']) ){
				
				$rModel = $this->newsterSettings['relatedModel'];

				$this->Newsletter->Behaviors->attach('Containable');
				
				$this->Newsletter->NewslettersItem->bindModel(array(
					'belongsTo' => array(
						'Related' => array(
							'className' => $rModel
							)
						)
					));

				$options['contain'] = array(
					'NewslettersItem.Related'
					);
				//NewslettersItem
			}

			$newsletters = $this->Newsletter->find('all', $options);

			$queue = array();

			if( $newsletters){

				//pr($newsletters);

				foreach( $newsletters as $newsletter){
					

					if( !empty( $newsletter['Newsletter']['lists'])){

						// si tengo algo programado de este newsletter lo quito porque fue agregado a medias (el estado no alcanzó a cambiar)
						$options = array(
							'conditions' => array(
								'Queue.newsletter_id' => $newsletter['Newsletter']['id']
								)
							);
						$this->Queue->deleteAll( $options['conditions'] );

						$lists = unserialize( $newsletter['Newsletter']['lists']);
						
						$emailTotal = 0;

						foreach( $lists as $list){


							$options = array(
								'conditions' => array(
									'SubscribersList.newsletterlist_id' => $list
									)
								);
							// busco usuarios de la lista
							$subscribers = $this->Subscriber->SubscribersList->find('all', $options);
							

							if( $subscribers){
								// la key del array será email + newsletter, (limita a un email por newsletter, puede ser que uno esté en mas una lista)
								foreach ($subscribers as $subscriber) {
									
									// por mas que ste en varias listas solo lo sumo una vez para ese newsletter
									$k = $subscriber['Subscriber']['email'].'-'.$newsletter['Newsletter']['id'];									// sumo el total de emails
									
									if( !isset($queue[ $k]) ){

										$emailTotal++;
										$queue[ $k] =  array(
												'newsletter_id' => $newsletter['Newsletter']['id'],
												'newsletterlist_id' => $list,
												'attempts' => 0,
												'title' => $newsletter['Newsletter']['newsletter_title'],
												'email' => $subscriber['Subscriber']['email'],
												'values' => serialize( $this->_prepareValues( $newsletter['Newsletter']['id'], $newsletter['NewslettersItem'] ) ),
												'sent' => 0,
												'error' => 0
											);
										$this->Queue->create( $queue[ $k]);
										$this->Queue->save();

									}
								}
							}	

						} // finde for de listas asociadas al newsletter

						// actulizo el estado del newsletter
						$this->Newsletter->save( array( 'id' => $newsletter['Newsletter']['id'], 'status' => 2, 'last_total' => $emailTotal ) );

					}

				} // fin de loop de newsletters

				// actualizo el estado del newsletter
				

			}

		}


	}

	// mezcla los valores del newsletter con el modelo relacionado si hay
	function _prepareValues( $newsletter_id, $items){
		
		$news = array();

		$newsletterCacheKey = 'newsletter_' . $newsletter_id;

		if( Cache::read($newsletterCacheKey)){

			return Cache::read($newsletterCacheKey);

		}else{

			if( count($items) ){
				foreach( $items as $item){
					if( isset( $item['Related']) && !empty( $item['Related'] ) ){
						$item  = array_merge( $item, $item['Related']);
						unset( $item['Related']);
					}
					$news[ $item['id'] ] = $item;

				}
				Cache::write( $newsletterCacheKey, $news, 'newster_newsletters');
			}

		}
		//pr($news);
		return $news;
	}


	function send(){

		Configure::write('debug', 0);

		set_time_limit( 0);


		$this->autoRender = false;
		$this->autoLayout = false;


		App::uses('CakeEmail', 'Network/Email');

		$mailer = new CakeEmail('smtp');

		$mailer->template( 'Newster.newsletter', 'Newster.default');
		
		$mailer->emailFormat('html');    	

		$options = array(
			'order' => 'Queue.created',
			'limit' => (isset( $this->newsterSettings['hourlyLimit']))?$this->newsterSettings['hourlyLimit']:100,
			'conditions' => array(
				'Queue.attempts <' => 3,
				'Queue.sent <>' => 1,
				'Queue.error <>' => 1
				)
			);

		$emails = $this->Queue->find( 'all', $options);

		foreach( $emails as $email){
			
			$this->Queue->id = $email['Queue']['id'];
			// antes de intentar e envío ya sumo un intento
			$this->Queue->saveField('attempts', $email['Queue']['attempts'] + 1 );


			$mailer->to( $email['Queue']['email']);
			$mailer->viewVars( array('items' =>  unserialize( $email['Queue']['values'] ) ) );
			$mailer->subject( $email['Queue']['title'] );

			// marco como enviado
			if( $mailer->send() ){
				echo $email['Queue']['email']."\n";
				$this->Queue->saveField('sent', 1);
			}

		}

		// todos los que tengan 3 o mas intentos los marco con error
		$this->Queue->updateAll( array('error' => 1), array('attempts >=' => 3) );

		// a los muy viejos los limpio
		// un mes para atrás
		$mes = time() - ( 60 * 60 * 24 * 30);
		$this->Queue->deleteAll( array('error' => 1 , 'created <' => date('Y-m-d h:i:s', $mes) ) );

		// se fija si los newsletters abiertos se terminan
		if( $this->loadModel('Newster.Newsletter')  ){
			$options = array(
				'recursive' => -1,
				'conditions' => array(
					'Newsletter.status' => 2
					)
				);
			$newsletters  = $this->Newsletter->find( 'all', $options);
			
			if( $newsletters){
				foreach( $newsletters as $newsletter){
					$options = array(
						'conditions' => array(
							'Queue.newsletter_id' => $newsletter['Newsletter']['id'],
							'OR' => array(
								'Queue.sent <>' => 1,
								'Queue.error <>' => 1
								)
							)
						);
					$total = $this->Queue->find('count', $options);
					$updateNewsletter = array(
						'id' => $newsletter['Newsletter']['id'],
						'last_sent_date' => date('Y-m-d H:i:s'),
						'last_sent' => $total
						);
					if( $newsletter['Newsletter']['last_total'] <= $total ){
						$updateNewsletter['status'] = 3;
					}
					// actualizo
					$this->Newsletter->save( $updateNewsletter);
					// fin de total
				}

				// luego busco si hace mucho que está abierto lo cierro

			}

		}


	}


	function view( $newsletter_id = ''){

		//set_time_limit( 0);

		//$this->autoRender = false;
		$this->autoLayout = false;

		if( $this->loadModel('Newster.Newsletter') ){


			$options =array(
				'conditions' => array(
					'Newsletter.id' => $newsletter_id
					)
				);

			// veo si tengo modelo relacionado
			if( isset($this->newsterSettings['relatedModel']) && !empty( $this->newsterSettings['relatedModel']) ){
				
				$rModel = $this->newsterSettings['relatedModel'];

				$this->Newsletter->Behaviors->attach('Containable');
				
				$this->Newsletter->NewslettersItem->bindModel(array(
					'belongsTo' => array(
						'Related' => array(
							'className' => $rModel
							)
						)
					));

				$options['contain'] = array(
					'NewslettersItem.Related'
					);
				//NewslettersItem
			}

			$newsletter = $this->Newsletter->find( 'first', $options);
			//pr($newsletter);
			$this->set( 'items', $this->_prepareValues( $newsletter_id, $newsletter['NewslettersItem'] ) );

		}

		$path = '../Emails/html/newsletter';

		$this->render( $path);


	}


}
?>