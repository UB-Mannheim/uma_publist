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
class YearsThisBibTypeViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('publications', 'array', 'Publications', true);
        $this->registerArgument('thisType', 'string', 'This type', true);
        $this->registerArgument('years', 'array', 'Years', true);
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
        $thisType = $arguments['thisType'];
        $years = $arguments['years'];
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
