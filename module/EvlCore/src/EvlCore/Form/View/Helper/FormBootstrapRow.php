<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlCore\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\Exception;
use Zend\Form\View\Helper\FormRow;

/**
 * Form element helper class, which add container, in which element is rendered.
 * Made to use with Bootstrap 3.x
 *
 * @author Tomasz Kuter
 *
 */
class FormBootstrapRow extends FormRow
{

    /**
     * The class that is added to container in which element is rendered
     *
     * @var string
     */
    protected $containerClass = 'form-group';

    /**
     * The class that is added to container in which element having errors is rendered
     *
     * @var string
     */
    protected $containerErrorClass = 'has-error';

    /**
     * The class that is added to element's label
     *
     * @var string
     */
    protected $labelClass = 'control-label';


    /**
     * Utility form helper that renders a label (if it exists), an element and errors, all in special Bootstrap 3.x's container
     *
     * @param  ElementInterface $element
     * @throws \Zend\Form\Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $inputErrorClass = $this->getInputErrorClass();

        $labelAttributes = $element->getLabelAttributes();
        if (is_array($labelAttributes) && $labelAttributes && array_key_exists('class', $labelAttributes)) {
            $labelAttributes['class'] .= ' ' . $this->labelClass;
        } else if (is_array($labelAttributes) && $labelAttributes) {
            $labelAttributes['class'] = $this->labelClass;
        } else {
            $labelAttributes = array(
                'class' => $this->labelClass
            );
        }
        $element->setLabelAttributes($labelAttributes);

        $markup = parent::render($element);

        // Does this element have errors ?
        $hasErrors = false;

        if (count($element->getMessages()) > 0 && !empty($inputErrorClass)) {
            $hasErrors = true;
        }

        $classes = $this->containerClass . ' ' . ($hasErrors ? $this->containerErrorClass : '' );
        $containerOpeningTag = '<div class="' . $classes .'">';
        $containerClosingTag = '</div>';

        $container = $containerOpeningTag . $markup . $containerClosingTag;

        return $container;
    }
}
