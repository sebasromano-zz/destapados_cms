<?php
class EmailsenderComponent extends Component {

	public $name = 'Emailsender';
	public $components = array('Email');

	public $smtpLimit = 30;
	public $smtpHost;
	public $smtpPort = 25;
	public $smtpTimeout = 30;
	public $smtpAuth = false;
	public $smtpUsername;
	public $smtpPassword;

	public $status = false;
	public $keepLogsFor = 5;

	public $useSmtp = true;

	public function initialize(Controller $controller, $settings = array()) {

		$this->Controller = $controller;

		if(isset($settings['smtpLimit']))
			$this->smtpLimit = $settings['smtpLimit'];

		if(isset($settings['smtpHost']))
			$this->smtpHost = $settings['smtpHost'];

		if(isset($settings['smtpPort']))
			$this->smtpPort = $settings['smtpPort'];

		if(isset($settings['smtpTimeout']))
			$this->smtpTimeout = $settings['smtpTimeout'];

		if(isset($settings['smtpAuth']))
			$this->smtpAuth = $settings['smtpAuth'];

		if(isset($settings['smtpUsername']))
			$this->smtpUsername = $settings['smtpUsername'];

		if(isset($settings['smtpPassword']))
			$this->smtpPassword = $settings['smtpPassword'];

		if(isset($settings['keepLogsFor']))
			$this->keepLogsFor = $settings['keepLogsFor'];

		if(isset($settings['useSmtp']))
			$this->useSmtp = $settings['useSmtp'];

		if( $this->useSmtp ){
			if(isset($this->smtpHost) && isset($this->smtpUsername) && isset($this->smtpPassword))
				$this->status = true;
		} else {
			$this->status = true;
		}

	}

	public function add( $data ) {

		$this->EmailsenderMessage = ClassRegistry::init('Emailsender.Message');

		$data['id'] = '';
		$data['delivery_status'] = 0;

		if(isset($data['data'])) $data['data'] = serialize( $data['data'] );
		if(isset($data['attachments'])) $data['attachments'] = serialize( $data['attachments'] );
		
		if(isset($data['delivery_cc'])) $data['delivery_cc'] = serialize( $data['delivery_cc'] );
		if(isset($data['delivery_bcc'])) $data['delivery_bcc'] = serialize( $data['delivery_bcc'] );

		$this->EmailsenderMessage->create( $data );
		$this->EmailsenderMessage->save(null, false);

	}

	public function send() {

		if( $this->status ){

			$this->EmailsenderMessage = ClassRegistry::init('Emailsender.Message');
	
			$emails = $this->EmailsenderMessage->find('all', array('limit' => $this->smtpLimit, 'conditions' => array('delivery_status' => 0)));
			
			$emailsCount = 0;
			
			if(!empty($emails)){
			
				foreach($emails as $email){
		
					$this->Email->reset();
	
					$this->Email->from = $email['Message']['delivery_from'];
					$this->Email->to = $email['Message']['delivery_to'];
					if(!empty($email['Message']['delivery_cc'])) $this->Email->cc = unserialize( $email['Message']['delivery_cc'] );
					if(!empty($email['Message']['delivery_bcc'])) $this->Email->bcc = unserialize( $email['Message']['delivery_bcc'] );
					$this->Email->subject = $email['Message']['delivery_subject'];
					$this->Email->sendAs = 'html';

					$this->Email->template = null;

					if(!empty($email['Message']['type']) && $email['Message']['type'] != 'default'){
						$this->Email->template = $email['Message']['type'];
					}
	
					$data = unserialize( $email['Message']['data'] );
		
					if( !empty($this->Email->template) ){
						$this->Controller->set('data', $data);
						$data = null;
					}

					if(!empty($email['Message']['attachments'])){
						$this->Email->attachments = unserialize( $email['Message']['attachments'] );
					}
	
					if( $this->useSmtp ){
	
						$this->Email->smtpOptions = array(
							'port' => $this->smtpPort,
							'timeout' => $this->smtpTimeout,
							'host' => $this->smtpHost,
							'username' => $this->smtpUsername,
							'password' => $this->smtpPassword
						);
						$this->Email->delivery = 'smtp';
					
					}
					
					$messageData['id'] = $email['Message']['id'];
					$messageData['delivery_date'] = date('Y-m-d H:i:s');
					
					if ( $this->Email->send( $data ) ) {
		
						$messageData['delivery_status'] = 1;
						$messageData['delivery_error'] = '';
						
						CakeLog::write('emailsender', 'Message successfully sent to: ' . $email['Message']['delivery_to'] );
			
					} else {
			
						$messageData['delivery_status'] = 0;
						$messageData['delivery_error'] = $this->Email->smtpError;
		
						CakeLog::write('emailsender', 'Error sending to: ' . $email['Message']['delivery_to'] . ' - Error: ' . $this->Email->smtpError );
			
					}
		
					$this->EmailsenderMessage->save( $messageData );
				
				}

				return true;

			} else {
				return false;
			}

		} else {

			return false;

		}

	}

	public function clean() {

		$this->EmailsenderMessage = ClassRegistry::init('Emailsender.Message');
		$this->EmailsenderArchive = ClassRegistry::init('Emailsender.Archive');

		$emails = $this->EmailsenderMessage->find('all', array('conditions' => array('delivery_status' => 1, 'delivery_date <' => date('Y-m-d', strtotime(date('Y').'-'.date('m').'-'.date('d')-$this->keepLogsFor)) )));
		
		if( $emails ){
		
			foreach($emails as $email){

				$data = $email['Message'];
				$data['type'] = $email['Message']['type'];
				$data['id'] = '';

				$this->EmailsenderArchive->create( $data );
				if( $this->EmailsenderArchive->save(null, false) ){
					$this->EmailsenderMessage->delete( $email['Message']['id'] );
				}
	
			}
			
			return true;
		
		} else {
			return false;
		}
		
	}


}

?>