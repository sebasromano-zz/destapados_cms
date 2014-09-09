<?php
// cache propia del plugin
Cache::config('newster_queue', array(
	'engine' => 'File',
	'duration' => '+1 year',
	'path' => CACHE,
	'prefix' => 'newster_'
));

Cache::config('newster_newsletters', array(
	'engine' => 'File',
	'duration' => '+1 year',
	'path' => CACHE,
	'prefix' => 'newster_'
));

class EmailConfig {

    public $smtp = array(
		'transport' => 'Smtp',
		'sender' => 'infociudadano@mendoza.gov.ar',
		'from' => 'infociudadano@mendoza.gov.ar',
		'host' => 'smtp3.mendoza.gov.ar',
		'port' => 25,
		'username' => 'infociudadano',
		'password' => 'pacoperezmza',
		'timeout' => 30,
		'debug' => false,
		'charset' => 'utf-8',
		'headerCharset' => 'utf-8'
	);

}