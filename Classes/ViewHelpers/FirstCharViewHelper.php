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
class FirstCharViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

        /**
         * Returns the first character of a string
         *
         * @param string text the text
         * @return string first character
         */
	public function render($text)
	{
		return $text[0];
	}
}

?>
