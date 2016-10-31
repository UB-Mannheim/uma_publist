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
class ErrorHandler implements \TYPO3\CMS\Core\SingletonInterface {

	protected static $error = 0;
	protected static $errorMsg = "";

	public function __construct() {
		$this->error = 0;
		$this->errorMsg = "";
	}

	public function getError() {
		return $this->error;
	}
	public function getErrorMsg() {
		return $this->errorMsg;
	}
	public function setError($error, $text) {
		$this->error = $error;
		$this->errorMsg = $text;
	}
}
