<?php
namespace Psmb\Newsletter\ViewHelpers\Format;

use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\Fluid\Core\ViewHelper\Facets\CompilableInterface;

/**
 * Class CropViewHelper
 * @package Psmb\Newsletter\ViewHelpers\Format
 */
class HandleVariableViewHelper extends AbstractViewHelper implements CompilableInterface
{
    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * Render the variable
     *
     * @param mixed $value The input value which should be handled.
     * @return string handled text.
     * @api
     */
    public function render($value = null)
    {
        return self::renderStatic(array('value' => $value), $this->buildRenderChildrenClosure(), $this->renderingContext);
    }

    /**
     * @param array $arguments
     * @param callable $renderChildrenClosure
     * @param \TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $value = $arguments['value'];
        if ($value === null) {
            $value = $renderChildrenClosure();
        }

        switch (gettype($value)) {
            case "boolean":
            case "integer":
            case "double":
            case "string":
            case "NULL":
                return $value;
            case "object":
                if ($value instanceof \DateTime) {
                    $format = 'd-m-Y';
                    return $value->format($format);
                }
                return null;
            case "array":
                return null;
            default:
                return NULL;
        }
    }
}
