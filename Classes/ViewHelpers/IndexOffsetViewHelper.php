<?php
namespace UMA\UmaPublist\ViewHelpers;

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

/**
 * ViewHelper to get pub-types for one year
 *
 * @package TYPO3
 * @subpackage tx_umapublist
 */
class IndexOffsetViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

        /**
         * Render the viewhelper
         *
	 * @param array $settings the flexform
         * @param array $content all Publications
	 * @param array $years list of years
	 * @param array $types list of Types
         * @param integer $curYear current year
         * @param string $curType current type
         * @return array with bibtypes
         */
	public function render($settings, $content, $years, $types, $curYear, $curType)
	{
		$index = 1;

		// not needed but make it clear for you
		if ((!$curYear) || ($settings['splityears'] == 0))
			$curYear = 0;
		if ((!$curType) || ($settings['splittypes'] == 0))
			$curType = "";

		switch ($settings['splityears']) {
		case 0:
			if ($settings['splittypes'] == 1)
				$index = $this->countOverTypes($content, $types, 0, $curType, $index);
			break;
		case 1:
			if ($settings['splittypes'] == 2)
				$index = $this->countOverYears($content, $years, NULL, $curYear, $curType, $index);
			else
				$index = $this->countOverYears($content, $years, $types, $curYear, $curType, $index);
			break;
		case 2:
			if ($settings['splittypes'] == 2)
				$index = $this->countOverTypes($content, NULL, $curYear, $curType, $index);
			else
				$index = $this->countOverTypes($content, $types, $curYear, $curType, $index);
			break;
		}
		return $index;
	}


	private function countOverYears($content, $years, $types, $curYear, $curType, $index) {
		$tmpIndex = $index;
		foreach ($years as $year) {
			if ($year == $curYear) {
				if ($curType != "")
					$tmpIndex = $this->countOverTypes($content, $types, $year, $curType, $tmpIndex);
				return $tmpIndex;
			} else {
				foreach ($content as $pub) {
					if ($pub->getYear() == $year) {
						if (!$types && ($curType != "")) {
							if ($pub->getBibType() == $curType) {
								$tmpIndex ++;
							}
						} else
							$tmpIndex ++;
					}
				}
			}
		}

		return $tmpIndex;
	}


	private function countOverTypes($content, $types, $curYear, $curType, $index) {
		$tmpIndex = $index;

	// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($types);
		if ($types) {
			foreach ($types as $type) {
				if ($type == $curType)
					return $tmpIndex;
				else {
					foreach ($content as $pub) {
						if ($pub->getBibType() == $type) {
							if ($curYear != 0) {
								if ($curYear == $pub->getYear())
									$tmpIndex ++;
							}
							else
								$tmpIndex ++;
						}
					}
				}
			}
		} else {
			foreach ($content as $pub) {
				if ($pub->getBibType() == $type) {
					if ($curYear != 0) {
						if ($curYear == $pub->getYear())
							$tmpIndex ++;
					}
					else
						$tmpIndex ++;
				}
			}
		}
		return $tmpIndex;
	}

}


?>
