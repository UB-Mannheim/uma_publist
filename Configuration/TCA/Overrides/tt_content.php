<?php
defined('TYPO3_MODE') or die();

/***************
 * Plugin
 */


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'UMA.uma_publist',
        'Pi1',
	'LLL:EXT:uma_publist/Resources/Private/Language/locallang_be.xlf:pi1_title'
);
//	'EXT:uma_publist/Resources/Public/Icons/ext_icon.gif'


/***************
 * Default TypoScript
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('uma_publist', 'Configuration/TypoScript', 'Default TypoScript');


/***************
 * Flexform
 */
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['umapublist_pi1'] = 'recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['umapublist_pi1'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('umapublist_pi1', 'FILE:EXT:uma_publist/Configuration/FlexForms/flexform_publist_pi1.xml');

