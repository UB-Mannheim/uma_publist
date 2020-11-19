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
class RenderNamesShortViewHelper extends AbstractViewHelper
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
        if (strpos($somebody, ";") !== FALSE) {
            $peopleList = explode( ';', $somebody);
            $peopleNumber = count($peopleList);
            $i = 1;
            foreach ($peopleList as $guy) {
                if ($theName = explode( ',', $guy)) {
                    // move particles like "von", "zu" from the end of the firstName to the beginning of the lastName
                    if (preg_match('/(?<= )(da|dalla|de|de la|deglia|del|der|ter|van|van den|vom|vom und zum|von|von dem|von der|von und zu|zu|zum)$/', $theName[1], $predicate)) {
                        $theName[0] = $predicate[0] . ' ' . $theName[0];
                        $theName[1] = substr($theName[1], 0, strlen($predicate[0]));
                    }
                    $output .= $theName[0] . ', ' . str_replace('-.', '', preg_replace('/[^A-Z\s\-]+/', '.', $theName[1]));
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
            if ( trim($somebody) && ($theName = explode(',', $somebody)))
                $output .= $theName[0] . ', ' . substr($theName[1], 0, 1) . '.';
        }
        return $output;
    }
}
