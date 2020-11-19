<?php
namespace UMA\UmaPublist\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to explode string by glue
 *
 * @package Typo3
 * @subpackage tx_umapublist
 */
class MyExplodeViewHelper extends AbstractViewHelper {

    /**
     * @var string
     */
    protected $method = 'explode';

    /**
     * Initialize
     *
     * @return void
     */
    public function initializeArguments() {
        $this->registerArgument('content', 'string', 'String to be exploded by glue)', FALSE, '');
        $this->registerArgument('glue', 'string', 'String used as glue in the string to be exploded. Use glue value of "constant:NAMEOFCONSTANT" (fx "constant:LF" for linefeed as glue)', FALSE, ',');
        $this->registerArgument('as', 'string', 'Template variable name to assign. If not specified returns the result array instead');
    }

    /**
     * Render method
     *
     * @return mixed
     */
    public function render() {
        $content = $this->arguments['content'];
        $as = $this->arguments['as'];
        $glue = $this->resolveGlue();
        $contentWasSource = FALSE;
        if (TRUE === empty($content)) {
            $content = $this->renderChildren();
            $contentWasSource = TRUE;
        }
        $output = call_user_func_array($this->method, array($glue, $content));
        if (TRUE === empty($as) || TRUE === $contentWasSource) {
            return $output;
        }
        $variables = array($as => $output);
        $content = $this->renderChildrenWithVariables($this, $this->templateVariableContainer, $variables);
        return $content;
    }

    /**
     * Detects the proper glue string to use for implode/explode operation
     *
     * @return string
     */
    protected function resolveGlue() {
        $glue = $this->arguments['glue'];
        if (FALSE !== strpos($glue, ':') && 1 < strlen($glue)) {
            // glue contains a special type identifier, resolve the actual glue
            list ($type, $value) = explode(':', $glue);
            switch ($type) {
                case 'constant':
                    $glue = constant($value);
                    break;
                default:
                    $glue = $value;
            }
        }
        return $glue;
    }


    /**
     * Renders tag content of ViewHelper and inserts variables
     * in $variables into $variableContainer while keeping backups
     * of each existing variable, restoring it after rendering.
     * Returns the output of the renderChildren() method on $viewHelper.
     *
     + Copied from https://github.com/FluidTYPO3/vhs
     */
    private static function renderChildrenWithVariables(\TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper $viewHelper, \TYPO3\CMS\Fluid\Core\ViewHelper\TemplateVariableContainer $variableContainer, array $variables) {
        $backups = array();
        foreach ($variables as $variableName => $variableValue) {
            if (TRUE === $variableContainer->exists($variableName)) {
                $backups[$variableName] = $variableContainer->get($variableName);
                $variableContainer->remove($variableName);
            }
            $variableContainer->add($variableName, $variableValue);
        }
        $content = $viewHelper->renderChildren();
        foreach ($variables as $variableName => $variableValue) {
            $variableContainer->remove($variableName);
            if (TRUE === isset($backups[$variableName])) {
                $variableContainer->add($variableName, $variableValue);
            }
        }
        return $content;
    }
}
