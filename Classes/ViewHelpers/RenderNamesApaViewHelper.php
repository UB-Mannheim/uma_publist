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
 * ViewHelper print Names with rdf schema and "AND"
 *
 * @package TYPO3
 * @subpackage publist4ubma2
 */
class RenderNamesApaViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

        /**
         * Render the viewhelper
         *
         * @param string $somebody with people
         * @return string with output
         */
	public function render($somebody)
	{
		$output = '';
		
		if ($somebody=='') {
			return '';
		}

		$peopleList = explode(';', $somebody);
		$peopleNumber = count($peopleList);
		for ($i = 0; $i < $peopleNumber; $i++) {
			if ($theName = explode(',', $peopleList[$i])) {
				$output .= $theName[0] . ', ' . preg_replace('/[^A-Z\s\-]+/', '.', $theName[1]);
				# The regexp above misses firstnames starting with non-ascii letter.
				if ($i < $peopleNumber-1) {
					if ($i < $peopleNumber-2) {
						$output .= ', ';
					}
					if ($i == $peopleNumber-2) {
						$output .= ', & ';
					}
					# Add '(Hrsg.)' for editors at the end of the string
					
				}
			}
		}

		return $output;
	}
}

?>

