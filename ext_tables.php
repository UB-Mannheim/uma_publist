<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}


if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'Unima.publist4ubma2',
		'tools',	 // Make module a submodule of 'Admin tools'
		'publist4ubma_m1',	// Submodule key
		'',						// Position
		array(
		/** only the first matching Controller is run
		 * And then we are in the Controller!
		 */
			'Administration' => 'listChairs,syncChairs,showCleanup,listPublists,listPublications',
			'Institute' => 'list,add,delete',
			'Chair' => 'list,add,delete',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_be.xlf',
		)
	);

}


