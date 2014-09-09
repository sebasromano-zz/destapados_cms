<?php
class Question extends AppModel {

	public $name = 'Question';
	public $displayField = 'title';
	public $recursive = -1;

	public $validate = array(
		'title' => array( 'required' => array('rule' => 'notEmpty')),
		'answer_1' => array( 'required' => array('rule' => 'notEmpty')),
		'answer_2' => array( 'required' => array('rule' => 'notEmpty')),
		'answer_3' => array( 'required' => array('rule' => 'notEmpty')),
		'answer_4' => array( 'required' => array('rule' => 'notEmpty')),
		'answer_ok' => array( 
			'required' => array('rule' => array('comparison', '>', 0) ),
			'required1' => array('rule' => array('comparison', '<', 5) ),
			)
	);

	public $belongsTo = array('Category');
	public $hasMany = array('Answer');

}