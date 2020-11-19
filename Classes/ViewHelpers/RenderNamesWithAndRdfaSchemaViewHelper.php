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
class RenderNamesWithAndRdfaSchemaViewHelper extends AbstractViewHelper
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
                    $output .= '<span property="schema:author" typeof="schema:Person"><span property="schema:givenName">' . $theName[1] . '</span> <span property="schema:familyName">' . $theName[0] . '</span></span>';
                    if ($peopleNumber >= 3) {
                        if ($i < ($peopleNumber - 1))
                            $output .= ', ';
                        elseif ($i < $peopleNumber)
                            //$output .= ' and ';
                            $output .= " " . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('and', 'uma_publist') . " ";
                    }
                    else {
                        if (($i < $peopleNumber) && ($peopleNumber == 2))
                            //$output .= ' and ';
                            $output .= " " . \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('and', 'uma_publist') . " ";
                    }
                    $i++;
                }
            }

        }
        else {
            if ( $editor = explode(',', $somebody))
                $output = '<span property="schema:author" typeof="schema:Person"><span property="schema:givenName">' . $editor[1] . '</span> <span property="schema:familyName">' . $editor[0] . '</span></span>';
        }
        return $output;    
    }
}
