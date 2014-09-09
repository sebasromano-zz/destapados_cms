<?php // 
class Newsletter extends NewsterAppModel {

    var $name = 'Newsletter';
	var $hasMany = array(
		'Newster.NewslettersItem'
		);

	var $validate = array(
		'newsletter_title' => array( 'required' => array('rule' => 'notEmpty') )
    );

}
?>