<?php
namespace UMA\UmaPublist\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * ViewHelper to get pub-types for one year
 *
 * @package TYPO3
 * @subpackage tx_umapublist
 */
class BibTypesThisYearViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('publications', 'array', 'Publications', true);
        $this->registerArgument('thisYear', 'integer', 'This year', true);
        $this->registerArgument('types', 'array', 'Types', true);
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
        $publications = $arguments['publications'];
        $thisYear = $arguments['thisYear'];
        $types = $arguments['types'];
        $typesInYear = array();

        foreach ($types as $type) {
            foreach ($publications as $publication)
            {
                if (($publication->getYear() == $thisYear) && ($publication->getBibType() == $type)) {
                    array_push($typesInYear, $type);
                    break;
                }
                
            }
        }

        return $typesInYear;
    }
}
