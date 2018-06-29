<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}




\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'UMA.uma_publist',
	'Pi1',
	array('Publist' => 'list'),
	array()
);

// Adding Default Page TS-Config - (default Output Templates)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:uma_publist/Configuration/TypoScript/tsconfig.ts">');

// Adding the CleanUp Task for Scheduler
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['UMA\UmaPublist\Task\Cleanup'] = array(
   'extension' => $_EXTKEY,
   'title' => 'Auto Update + Cleanup DB',
   'description' => 'This task updates the Publication Lists and cleanups the Database for uma_publist',
   'additionalFields' => 'UMA\UmaPublist\Command\FileCommandControllerAdditionalFieldProvider'
);

// Register plugin icon
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'tx-umapublist-pi1',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:uma_publist/Resources/Public/Icons/ext_icon.png']
);

// Add wizard for plugin
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
  'mod {
    wizards.newContentElement.wizardItems.plugins {
      elements {
        uma_publist {
          iconIdentifier = tx-umapublist-pi1
          title = LLL:EXT:uma_publist/Resources/Private/Language/locallang_be.xlf:module.publicationLists
          description = LLL:EXT:uma_publist/Resources/Private/Language/locallang_be.xlf:pi1_title
          tt_content_defValues {
            CType = list
            list_type = umapublist_pi1
          }
        }
      }
      show := addToList(umapublist_pi1)
    }
  }'
);



/*
// For Backend (Scheduler Task)
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
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'uma_publist');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_umapublist_domain_model_institute', 'EXT:uma_publist/Resources/Private/Language/locallang_csh_tx_umapublist_domain_model_institute.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_umapublist_domain_model_institute');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_umapublist_domain_model_chair', 'EXT:uma_publist/Resources/Private/Language/locallang_csh_tx_umapublist_domain_model_chair.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_umapublist_domain_model_chair');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_umapublist_domain_model_publication', 'EXT:uma_publist/Resources/Private/Language/locallang_csh_tx_umapublist_domain_model_publication.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_umapublist_domain_model_publication');


*/
