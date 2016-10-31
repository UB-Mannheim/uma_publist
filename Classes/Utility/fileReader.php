<?php

namespace Unima\Publist4ubma2\Utility;

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


class fileReader {

        /**
         * Find all ids from given ids and level
         *
         * @param string $url   Link to XML
         * @return string xmlcode
         */
        public static function downloadFile($url)
	{
		$errorHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Unima\\Publist4ubma2\\Service\\ErrorHandler');
		$content = "";
		$fp = fopen($url, "rb");
		if (!$fp) {
			$errorHandler->setError(1, 'Could not download file ' . $url);
		} else {
			// set timeout for $fp to 5s
			stream_set_timeout($fp, 5);
			while (!feof($fp))
				$content .= fread($fp, 2048);
			fclose($fp);
                }

//		print \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($content);
		return $content;
	}

}
