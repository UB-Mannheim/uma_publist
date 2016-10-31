<?php

namespace Unima\Publist4ubma2\Service;

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
 * lets do this as singelton - it is some kind of service
 */
class DebugCollector implements \TYPO3\CMS\Core\SingletonInterface {

	protected static $debugData;

	public function __construct() {
		$this->debugData = [];
	}

	public function get() {
		return $this->debugData;
	}
	public function add($line) {
		array_push($this->debugData, $line);
	}
}

