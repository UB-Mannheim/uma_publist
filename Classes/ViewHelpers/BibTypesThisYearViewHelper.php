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
class BibTypesThisYearViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

        /**
         * Render the viewhelper
         *
         * @param array $publications all Publicationa
         * @param integer $thisYear current year
         * @param array $types list of types
         * @return array with bibtypes
         */
	public function render($publications, $thisYear, $types)
	{
		$typesInYear = array();

		foreach ($types as $type) {
			foreach ($publications as $publication)
			{
//				\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($publication);
				if (($publication->getYear() == $thisYear) && ($publication->getBibType() == $type)) {
					array_push($typesInYear, $type);
					break;
				}
				
			}
		}

		return $typesInYear;
	}
}

?>
