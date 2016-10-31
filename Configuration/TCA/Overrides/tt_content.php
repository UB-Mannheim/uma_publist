<?php
defined('TYPO3_MODE') or die();

/***************
 * Plugin
 */


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Unima.publist4ubma2',
        'Pi1',
	'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_be.xlf:pi1_title'
);
//	'EXT:publist4ubma2/Resources/Public/Icons/ext_icon.gif'


/***************
 * Default TypoScript
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('publist4ubma2', 'Configuration/TypoScript', 'Default TypoScript');


/***************
 * Flexform
 */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['publist4ubma2_pi1'] = 'recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['publist4ubma2_pi1'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('publist4ubma2_pi1', 'FILE:EXT:publist4ubma2/Configuration/FlexForms/flexform_publist_pi1.xml');

