<?php 
class Newsletterlist extends NewsterAppModel{

    var $name = 'Newsletterlist';

	var $validate = array(
		'title' => array( 'required' => array('rule' => 'notEmpty') )
    );


    public function getLists(){
    	$options = array(
    		'fields' => array('Newsletterlist.id', 'Newsletterlist.title'),
    		'conditions' => array(
    			'Newsletterlist.active' => 1
    			)
    		);
    	return $this->find('list', $options);
    }
	
}