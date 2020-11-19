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
class RenderNamesApaViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('somebody', 'string', 'Somebody', true);
        $this->registerArgument('nonInverted', 'bool', 'Do not invert first and last name?', false, false);
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
        $nonInverted = $arguments['nonInverted'];
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
                    $theName[1] = substr($theName[1], 0, strlen($predicate[0]));
                }
                if ($nonInverted) {
                    $output .= preg_replace('/[^A-Z\s\-]+/', '.', $theName[1]) . ' ' . $theName[0];
                }
                else {
                    $output .= $theName[0] . ', ' . preg_replace('/[^A-Z\s\-]+/', '.', $theName[1]);
                }
                # The regexp above misses firstnames starting with non-ascii letter.
                if ($i < $peopleNumber-1) {
                    if ($i < $peopleNumber-2) {
                        $output .= ', ';
                    }
                    if ($i == $peopleNumber-2) {
                        $output .= ' & ';
                    }
                    # Add '(Hrsg.)' for editors at the end of the string
                    
                }
            }
        }

        return $output;
    }
}
