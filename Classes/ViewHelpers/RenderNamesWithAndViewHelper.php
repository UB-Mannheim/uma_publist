<?php
namespace UMA\UmaPublist\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * ViewHelper print Names with rdf schema and "AND"
 *
 * @package TYPO3
 * @subpackage tx_umapublist
 */
class RenderNamesWithAndViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('somebody', 'string', 'Somebody', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    )
    {
        $somebody = $arguments['somebody'];
        $output = '';

        if (strpos($somebody, ";") >= 0) {
            $peopleList = explode( ';', $somebody);
            $peopleNumber = count($peopleList);
            $i = 1;
            foreach ($peopleList as $guy) {
                if ($theName = explode( ',', $guy)) {
                    $output .= $theName[1] . ' ' . $theName[0];
                    if ($peopleNumber >= 3) {
                        if ($i < ($peopleNumber - 1))
                            $output .= ', ';
                        elseif ($i < $peopleNumber)
                            $output .= " " . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('and', 'uma_publist') . " ";
                    }
                    else {
                        if (($i < $peopleNumber) && ($peopleNumber == 2))
                            $output .= " " . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('and', 'uma_publist') . " ";
                    }
                    $i++;
                }
            }

        }
        else {
            if ( $editor = explode(',', $somebody))
                $output .= $theName[1] . ' ' . $theName[0];
        }
        return $output;    
    }
}
