<?php

namespace UMA\UmaPublist\Service;

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

use TYPO3\CMS\Core\Core;

/**
 * let us do this as singleton - it is some kind of service
 */
class SettingsManager implements \TYPO3\CMS\Core\SingletonInterface {

	protected static $allConfig;

	public function __construct() {
		$this->allConfig['extMgn'] = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['uma_publist'];
	}


	// TypoScript Config we have to set by parameter, because this Class don't know about $this->settings
/*	public function giveTypoScript($typoScript) {
		$this->allConfig['typoScript'] = $typoScript;
	}
*/

	public function configValue($pathStr) {
		$paths = explode("/", $pathStr); 
		$tmpConf = $this->allConfig;
		foreach($paths as $nextIndex)
			$tmpConf = $tmpConf[$nextIndex];

		return $tmpConf;
	}

}
