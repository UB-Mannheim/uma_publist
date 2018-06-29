<?php
namespace UMA\UmaPublist\Controller;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Sebastian Kotthoff <sebastian.kotthoff@rz.uni-mannheim.de>, Uni Mannheim
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


/**
 * AdministrationController
 */
class BasicPublistController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {


	/**
	 * The Singelton Services
	 * 
	 * have to be created with "makeInstance" to work correctly
	 */
	public $settingsManager = NULL;
	public $errorHandler = NULL;
	public $debugger = NULL;
	public function __construct() {
		$this->errorHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('UMA\\UmaPublist\\Service\\ErrorHandler');
		$this->settingsManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('UMA\\UmaPublist\\Service\\SettingsManager');
		$this->debugger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('UMA\\UmaPublist\\Service\\DebugCollector');
		// TypoScript Config we have to set by parameter, because class SettingsManager don't know about $this->settings
//		$this->settingsManager->giveTypoScript($this->settings);
	}
	
}


