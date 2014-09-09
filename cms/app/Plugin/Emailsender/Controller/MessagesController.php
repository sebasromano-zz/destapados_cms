<?php //Ñandú

class MessagesController extends EmailsqueueAppController {

	var $name = 'Messages';

	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('send', 'clean');
	}

	function send(){
		
		$this->layout = false;
		
		Configure::write('debug', 0);
		
		if( $this->Emailsqueue->send() ){
			$this->Session->setFlash('Se ha procesado la cola de envío de emails.', 'default', array('class' => 'successBoxSmall'));
		} else {
			$this->Session->setFlash('No hay mensajes en la cola de envío de emails.', 'default', array('class' => 'warningBoxSmall'));
		}

		$this->render('message');

	}

	function clean(){

		$this->layout = false;

		Configure::write('debug', 0);
		
		if( $this->Emailsqueue->clean() ){
			$this->Session->setFlash('Se ha limpiado la cola de envío de emails.', 'default', array('class' => 'successBoxSmall'));
		} else {
			$this->Session->setFlash('No hay mensajes en la cola de envío de emails.', 'default', array('class' => 'warningBoxSmall'));
		}

		$this->render('message');

	}


}
?>