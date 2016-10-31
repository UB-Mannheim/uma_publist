<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}




\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Unima.publist4ubma2',
	'Pi1',
	array('Publist' => 'list'),
	array()
);

// Adding Default Page TS-Config - (default Output Templates)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:publist4ubma2/Configuration/TypoScript/tsconfig.ts">');

// Adding the CleanUp Task for Scheduler
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Unima\Publist4ubma2\Task\Cleanup'] = array(
   'extension' => $_EXTKEY,
   'title' => 'Auto Update + Cleanup DB',
   'description' => 'This task updates the Publication Lists and cleanups the Database for publistubma2',
   'additionalFields' => 'Unima\Publist4ubma2\Command\FileCommandControllerAdditionalFieldProvider'
);







/*
// For Backen (Scheduler Task)
if(TYPO3_MODE === 'BE') {
 
    // Constants
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY,'constants',' <INCLUDE_TYPOSCRIPT: source="FILE:EXT:'. $_EXTKEY .'/Configuration/TypoScript/constants.txt">');
 
    // Setup     
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY,'setup',' <INCLUDE_TYPOSCRIPT: source="FILE:EXT:'. $_EXTKEY .'/Configuration/TypoScript/setup.txt">');
 
    // CommandController registrieren
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Vendor\Foo\Command\FooCommandController';
 
}
*/

/*
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'publist4ubma2');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_publist4ubma2_domain_model_institute', 'EXT:publist4ubma2/Resources/Private/Language/locallang_csh_tx_publist4ubma2_domain_model_institute.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_publist4ubma2_domain_model_institute');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_publist4ubma2_domain_model_chair', 'EXT:publist4ubma2/Resources/Private/Language/locallang_csh_tx_publist4ubma2_domain_model_chair.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_publist4ubma2_domain_model_chair');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_publist4ubma2_domain_model_publication', 'EXT:publist4ubma2/Resources/Private/Language/locallang_csh_tx_publist4ubma2_domain_model_publication.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_publist4ubma2_domain_model_publication');


*/
