<?php

App::uses('AppController', 'Controller');

class NewsterAppController extends AppController {
	// ...	

	function beforeFilter(){
		
		$this->layout = 'admin';

		$this->newsterSettings = array(
			'relatedModel' => 'News', // modelo relacionado a newsletter
			//'relatedSubscribersModel' => 'Customer',
			'hourlyLimit' => 100, 
			'resizes' => array(
				'thumb' => '65x50',
				'mini' => '191x94',
				'medium' => '700x500'
			),
			'listOptions' => array(
				'fields' =>
					array( 'id', 'title_spa'), 
				'order' => 'News.date_of DESC',
				'limit' => 250
				)
			);

		$this->set( 'newsterSettings', $this->newsterSettings);

		$this->newsletter_status = array(
			1 => 'Pendiente de envío',
			2 => 'En proceso de envío',
			3 => 'Enviado',
			4 => 'Enviado [parcial]'
			);
		$this->set('newsletter_status', $this->newsletter_status);

		parent::beforeFilter();

	}
}