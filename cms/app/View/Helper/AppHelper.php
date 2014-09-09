<?php
App::uses('Helper', 'View');

class AppHelper extends Helper {

	function url($url = null, $full = false) {
		if(!isset($url['language']) && isset($this->params['language'])) {
			$url['language'] = $this->params['language'];
		}
		/*if( !isset( $url['plugin']) || empty($url['plugin'])){
			$url['plugin'] = null;
		}*/
		return parent::url($url, $full);
	}

}
