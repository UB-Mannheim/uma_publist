<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}


if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'UMA.uma_publist',
		'tools',	 // Make module a submodule of 'Admin tools'
		'mod1',	// Submodule key
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
			'icon'   => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/ext_icon.png',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_be.xlf',
		)
	);

}

// Register for hook to show preview of tt_content element of list_type "umapublist_pi1" in page module
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['umapublist_pi1'] = \UMA\UmaPublist\Hooks\DrawItem::class;