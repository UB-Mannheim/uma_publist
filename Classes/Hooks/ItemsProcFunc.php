<?php

namespace Unima\Publist4ubma2\Hooks;

	/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Userfunc to render select-boxes for institutes and chairs from db
 *
 * @package TYPO3
 * @subpackage tx_publist4ubma2
 */
class ItemsProcFunc {


        /**
         * Itemsproc function to extend the selection of templateLayouts in the plugin
         *
         * @param array &$config configuration array
         * @return void
         */
        public function user_templateLayout(array &$config) {
                /** @var \GeorgRinger\News\Utility\TemplateLayout $templateLayoutsUtility */
                $templateLayoutsUtility = GeneralUtility::makeInstance('Unima\\Publist4ubma2\\Utility\\TemplateLayout');
                $templateLayouts = $templateLayoutsUtility->getAvailableTemplateLayouts($config['row']['pid']);
                foreach ($templateLayouts as $layout) {
                        $additionalLayout = array(
                                $GLOBALS['LANG']->sL($layout[0], TRUE),
                                $layout[1]
                        );
                        array_push($config['items'], $additionalLayout);
                }
        }



	/**
	 * Itemsproc function to redner selectbox for institutes
	 *
	 * @param array &$config configuration array
	 * @return void
	 */
/*
	public function renderInstitutes(array &$config) {

//		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($config);

		// get the institute repository
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$repository = $objectManager->get('Unima\\Publist4ubma2\\Domain\\Repository\\InstituteRepository');

		$result = $repository->findAll();

		// copy to config and sort imidily
		foreach ($result as $index => $institute) {
			$config['items'][$index]['0'] = $institute->getNameDe();
			$config['items'][$index]['1'] = $institute->getId();
		}

		return $config;
	}
*/



	/**
	 * Itemsproc function to render selectbox for institutes
	 *
	 * @param array &$config configuration array
	 * @return config
	 */
	public function renderChairs(array &$config) {

//		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($config['row']['pi_flexform']);

		// get the institute repository
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$repository = $objectManager->get('Unima\\Publist4ubma2\\Domain\\Repository\\ChairRepository');
		$configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');
		$typoscript = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'publist4ubma2');
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($typoscript);
		$result = $repository->findAllByInst($config['config']['my_inst'], $typoscript['storagePid']);

		// copy to config and sort imidily
		foreach ($result as $index => $institute) {
			$config['items'][$index]['0'] = $institute->getNameDe();
			$config['items'][$index]['1'] = $institute->getId();
		}

		return $config;
	}

}
