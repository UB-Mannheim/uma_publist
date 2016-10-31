<?php
namespace Unima\Publist4ubma2\ViewHelpers;

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
 * @subpackage publist4ubma2
 */
class YearsThisBibTypeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

        /**
         * Render the viewhelper
         *
         * @param array $publications all Publicationa
         * @param stringr $thisType current type
         * @param array $years list of years
         * @return array with bibtypes
         */
	public function render($publications, $thisType, $years)
	{
		$yearsInType = array();

		foreach ($years as $year) {
			foreach ($publications as $publication)
			{
				if (($publication->getBibType() == $thisType) && ($publication->getYear() == $year)) {
					array_push($yearsInType, $year);
					break;
				}
			}
		}

		return $yearsInType;
	}
}

?>
