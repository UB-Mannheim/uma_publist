<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "uma_publist"
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Publication List Module',
	'description' => 'Synchronizes and displays publication lists',
	'category' => 'plugin',
	'author' => 'Sebastian Kotthoff',
	'author_email' => 'sebastian.kotthoff@rz.uni-mannheim.de',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '0.1.1',
	'constraints' => array(
		'depends' => array(
			'typo3' => '7.6.0-8.9.99',
			'vhs' => '3.0.0-5.9.99'
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);
