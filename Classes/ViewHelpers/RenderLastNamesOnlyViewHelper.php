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
 * ViewHelper print last names only
 *
 * @package TYPO3
 * @subpackage tx_umapublist
 */
class RenderLastNamesOnlyViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
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
				// move particles like "von", "zu" from the end of the firstName to the beginning of the lastName
				if (preg_match('/(?<= )(da|dalla|de|de la|deglia|del|der|ter|van|van den|vom|vom und zum|von|von dem|von der|von und zu|zu|zum)$/', $theName[1], $predicate)) {
					$theName[0] = $predicate[0] . ' ' . $theName[0];
				}
				$output .= $theName[0];
				if ($i < $peopleNumber-1) {
					if ($i < $peopleNumber-1) {
						$output .= '/';
					}
					# Add '(Hrsg.)' for editors at the end of the string
					
				}
			}
		}

		return $output;
	}
}

?>

