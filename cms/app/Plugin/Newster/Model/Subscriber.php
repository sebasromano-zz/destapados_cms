<?php 
class Subscriber extends NewsterAppModel {

    var $name = 'Subscriber';

	var $validate = array(
        'email' => array( 'email' => array( 'rule' => 'email'), 'unique' => array( 'rule' => 'isUnique' ) )
    );

	public $virtualFields = array(
		'full_name' => 'CONCAT(last_name, ", ", first_name)'
	);

	var $hasAndBelongsToMany = array(
		'Newsletterlist' => array(
				'with' => 'Newster.SubscribersList',
				'className' => 'Newster.Newsletterlist',
				'joinTable' => 'subscribers_lists'
			)
	);

	var $hasMany = array(
		'Newster.SubscribersList'
	);
	/*

=> array(
			'with' => 'Newster.NewsletterslistsSubscriber',
			'className' => 'Newster.Newsletterslist',
			'joinTable' => 'newsletterslists_subscribers',
			'foreignKey' => 'subscriber_id',
			'associationForeignKey' => 'newsletterslist_id'
		)


	function importFromFile( $content = '', $options = array() ){
		
		$optionsDefault = array( 
			'source' => 'outlook-express', 
			'newline' => "\n", 
			'separator' => ",", 
			'list' => 0
		);

		$options = array_merge( $optionsDefault, $options);

		// rows			
		$fileRows = explode( $options['newline'], $content);

		if( is_array( $fileRows) && count( $fileRows) > 0){

			$total = 0;
		
			foreach($fileRows as $fileRow){
				set_time_limit( 10);

				//cada campo a un array
				$fileRowData = explode( $options['separator'], $fileRow);

				$info = array();
				$info['Subscriber']['active'] = 1;
				$info['Subscriber']['added_manually'] = 1;
				$info['Subscriber']['optout'] = 0;

				// relaciono el suscriptor a la lista
				$info['Newsletterslist']['id'] = $options['list'];
				$info['Subscriber']['email'] = '';

				switch( $options['source']){
					case 'outlook-express':
						if( !empty($fileRowData[0])) $info['Subscriber']['name'] = utf8_encode(trim($fileRowData[0]));		
						if(!empty($fileRowData[1])) $info['Subscriber']['email'] = $this->cleaner( $fileRowData[1]);
						break;
					case 'outlook-office':
						if(!empty($fileRowData[0])) $info['Subscriber']['name'] = utf8_encode( trim( str_replace( "\"", "", $fileRowData[0]) ));
						if(!empty($fileRowData[1])) $info['Subscriber']['name'] .= ' '.utf8_encode( trim( str_replace( "\"", "", $fileRowData[1]) ));
						if(!empty($fileRowData[2])) $info['Subscriber']['email'] = $this->cleaner(  $fileRowData[2]);
						break;
				}

				if( !empty($info['Subscriber']['email'])){

					$emailExists = $this->find( 'first', array('conditions' => array( 'Subscriber.email' => $info['Subscriber']['email']) ) );
					if( !$emailExists){
						$this->create( $info);
						//echo '<pre>';
						//var_dump( $info);
						//echo '</pre>';
						if( $this->save()){
							$total++; // email importado
						}
					}elseif( !empty($info['Subscriber']['name']) ){
						$this->id = $emailExists['Subscriber']['id'];
						$this->saveField( 'name', $info['Subscriber']['name']);
					}
				}// !empty()
	
			}
			return $total;

		} 
		return false;

	}
	
	// agrega un subscriptor
	//function addSubscriber( array( 'name' => '', 'email' => '', 'list' => 0) ){

//	}

	function cleaner( $str = '', $type = 'email'){
		switch( $type){
			case 'email':
				// limpio un email (solo alfhanumerico y arroba
				$reg = "/[^0-9a-zA-Z_\.\@]{1,}/ims";
				return preg_replace( $reg, '', $str);
				break;
		}
	}*/
    
}