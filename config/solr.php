<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'default' => array(
		'host' => 'localhost:8983/solr/',
		'writer' => 'xml',
		'reader' => 'json',
	),
	'unittest' => array(
		'host' => '127.127.0.1.:8999/solr/products/',
		'writer' => 'xml',
		'reader' => 'json',
	),
);
