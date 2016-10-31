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


class xmlUtil {
        public static function xmlToArray ( $xmlObj, $output = array () )
	{      
		foreach ( (array) $xmlObj as $index => $node )
		{
			if (is_object($node))
				$output[$index] = self::xmlToArray($node);
			else
				$output[$index] = $node;
		}
		return $output;
	}

}


