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
class IndexOffsetViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('settings', 'array', 'Settings', true);
        $this->registerArgument('content', 'array', 'Content', true);
        $this->registerArgument('years', 'array', 'Years', true);
        $this->registerArgument('types', 'array', 'Types', true);
        $this->registerArgument('curYear', 'integer', 'Current year', true);
        $this->registerArgument('curType', 'string', 'Current type', true);
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
        $settings = $arguments['settings'];
        $content = $arguments['content'];
        $years = $arguments['years'];
        $types = $arguments['types'];
        $curYear = $arguments['curYear'];
        $curType = $arguments['curType'];
        $index = 1;

        // not needed but make it clear for you
        if ((!$curYear) || ($settings['splityears'] == 0))
            $curYear = 0;
        if ((!$curType) || ($settings['splittypes'] == 0))
            $curType = "";

        switch ($settings['splityears']) {
        case 0:
            if ($settings['splittypes'] == 1)
                $index = self::countOverTypes($content, $types, 0, $curType, $index);
            break;
        case 1:
            if ($settings['splittypes'] == 2)
                $index = self::countOverYears($content, $years, NULL, $curYear, $curType, $index);
            else
                $index = self::countOverYears($content, $years, $types, $curYear, $curType, $index);
            break;
        case 2:
            if ($settings['splittypes'] == 2)
                $index = self::countOverTypes($content, NULL, $curYear, $curType, $index);
            else
                $index = self::countOverTypes($content, $types, $curYear, $curType, $index);
            break;
        }
        return $index;
    }

    protected static function countOverYears($content, $years, $types, $curYear, $curType, $index) {
        $tmpIndex = $index;
        foreach ($years as $year) {
            if ($year == $curYear) {
                if ($curType != "")
                    $tmpIndex = self::countOverTypes($content, $types, $year, $curType, $tmpIndex);
                return $tmpIndex;
            } else {
                foreach ($content as $pub) {
                    if ($pub->getYear() == $year) {
                        if (!$types && ($curType != "")) {
                            if ($pub->getBibType() == $curType) {
                                $tmpIndex ++;
                            }
                        } else
                            $tmpIndex ++;
                    }
                }
            }
        }

        return $tmpIndex;
    }

    protected static function countOverTypes($content, $types, $curYear, $curType, $index) {
        $tmpIndex = $index;
        if ($types) {
            foreach ($types as $type) {
                if ($type == $curType)
                    return $tmpIndex;
                else {
                    foreach ($content as $pub) {
                        if ($pub->getBibType() == $type) {
                            if ($curYear != 0) {
                                if ($curYear == $pub->getYear())
                                    $tmpIndex ++;
                            }
                            else
                                $tmpIndex ++;
                        }
                    }
                }
            }
        } else {
            foreach ($content as $pub) {
                if ($pub->getBibType() == $type) {
                    if ($curYear != 0) {
                        if ($curYear == $pub->getYear())
                            $tmpIndex ++;
                    }
                    else
                        $tmpIndex ++;
                }
            }
        }
        return $tmpIndex;
    }
}