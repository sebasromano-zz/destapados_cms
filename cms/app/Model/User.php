<?php
App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {
	
	public $name = 'User';

	public $virtualFields = array(
		'name' => 'CONCAT(User.first_name, " ", User.last_name)'
	);

	
	public $validate = array(
		'role' => array( 'required' => array('rule' => 'notEmpty')),
		'first_name' => array( 'required' => array('rule' => 'notEmpty')),
		'last_name' => array( 'required' => array('rule' => 'notEmpty')),
		'email' => array( 'email' => array( 'rule' => 'email', 'allowEmpty' => true)),
		'username' => array( 'required' => array('rule' => 'notEmpty'), 'unique' => array('rule' => 'isUnique')),
		'new_password' => array('confirmPassword' => array('rule' => 'confirmPassword'))
	);

	public $actsAs = array(
		'Upload.Upload' => array(
			'avatar' => array(
				'styles' => array(
					'thumb' => '60x45',
					'medium' => '700x500'
				),
				'path' => ':webroot/files/:model/:style_:basename.:extension'
			)
		)
	);	
	
	public function confirmPassword( $data ) {
		if (empty($data['id']) || !empty( $data['new_password'])){
			if ($data['new_password'] == $this->data['User']['new_password_confirm']){
				return true;
			} else {
				$this->invalidate('new_password_confirm');
				return true;
			}
		} else {
			return true;
		}
	}

	public function beforeSave() {
		if (!empty($this->data[$this->alias]['new_password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['new_password']);
		}
		return true;
	}
	
}
