<?php

class SubscriberGhostBehavior extends ModelBehavior {
	/**
	 * Model-specific settings
	 * @var array
	 */
	var $settings = array();
	
	function setup(&$model, $settings = array()) {
		// no special setup required
		$defaultSettings = array(
			'id_field' => 'id',
			'email_field' => 'email',
			'first_name_field' => 'first_name',
			'last_name_field' => 'last_name'
			);
		$this->settings[$model->name] = array_merge( $defaultSettings, $settings);

		// bindeo modelos fantasmas
		$bindArray = array(
			'hasMany' => array(
				'Subscriber' => array(
					'className' => 'Newster.Subscriber',
					'foreignKey' => 'related_id'
					)
				
				)
			);
		$model->bindModel( $bindArray, false);
	}

	function afterValidate( &$model, $options = array() ){
		

	}

	function afterSave( &$model, $created = false,  $options = array()){

		
		// verifico si el email existe en la base de subscriptores
		if( isset( $model->data[ $model->name ][ $this->settings[$model->name]['email_field']  ] ) && !empty( $model->data[ $model->name ][ $this->settings[$model->name]['email_field']  ] ) ){
			$relatedId = $model->data[ $model->name ][ $this->settings[$model->name]['id_field']  ];
			$email = trim( $model->data[ $model->name ][ $this->settings[$model->name]['email_field']  ] );
			$options = array(
				'conditions' => array(
					'related_id' => $relatedId
					)
				);
			$subscriber = $model->Subscriber->find('first', $options);
			if( $subscriber){
				$subscriber_id = $subscriber['Subscriber']['id'];
				// actualiza el mail si ha cambiado
				if( $subscriber['Subscriber']['email'] != $email){
					$model->Subscriber->id = $subscriber_id;
					$model->Subscriber->saveField('email', $email);
				}
			}else{
				// si no existe lo creo
				$data = array(
					'related_id' => $relatedId,
					'first_name' => $model->data[ $model->name ][ $this->settings[$model->name]['first_name_field']  ],
					'last_name' => $model->data[ $model->name ][ $this->settings[$model->name]['last_name_field']  ],
					'email' => $email
					);
				$model->Subscriber->create( $data);
				$model->Subscriber->save();
				$subscriber_id = $model->Subscriber->getID();
			}

			// borro las relaciones anteriores

			// $model->Subscriber->SubscribersList->
			//pr( $model->data);
			//die();

		}

	}

}