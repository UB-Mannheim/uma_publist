<?php
namespace UMA\UmaPublist\ViewHelpers;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * ViewHelper to include a css/js file
 *
 * # Example: Basic example
 * <code>
 * <n:includeFile path="{settings.cssFile}" />
 * </code>
 * <output>
 * This will include the file provided by {settings} in the header
 * </output>
 *
 */
class IncludeFileViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('path', 'string', 'Path', true);
        $this->registerArgument('compress', 'bool', 'Compress?', false, false);
    }

    /**
     * Include a CSS/JS file
     * 
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
        $path = $arguments['path'];
        $compress = $arguments['compress'];
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        if (TYPO3_MODE === 'FE') {
            $path = $GLOBALS['TSFE']->tmpl->getFileName($path);
            // JS
            if (strtolower(substr($path, -3)) === '.js') {
                $pageRenderer->addJsFile($path, null, $compress);
                // CSS
            } elseif (strtolower(substr($path, -4)) === '.css') {
                $pageRenderer->addCssFile($path, 'stylesheet', 'all', '', $compress);
            }
        } else {
            // JS
            if (strtolower(substr($path, -3)) === '.js') {
                $pageRenderer->addJsFile($path, null, $compress);
                // CSS
            } elseif (strtolower(substr($path, -4)) === '.css') {
                $pageRenderer->addCssFile($path, 'stylesheet', 'all', '', $compress);
            }
        }
    }
}
