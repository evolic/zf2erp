<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CompanyForm - company form.
 * @package EvlErp\Form
 */
class CompanyForm extends Form implements ServiceLocatorAwareInterface
{
    public function init()
    {
        // we want to ignore the name passed
        parent::__construct('company-form');
        $this->setAttribute('method', 'post');

        // translator
        $translator = $this->getServiceLocator()->get('translator');

        // base fieldset
        $this->add(array(
            'type' => 'EvlErp\Form\Fieldset\CompanyFieldset',
            'options' => array(
                'use_as_base_fieldset' => true
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => $translator->translate('Submit'),
                'id' => 'submit',
                'class' => 'btn btn-primary'
            )
        ));

        $this->add(array(
            'name' => 'cancel',
            'attributes' => array(
                'type'  => 'submit',
                'value' => $translator->translate('Cancel'),
                'id' => 'submit',
                'class' => 'btn btn-default',
            ),
        ));

        $this->setValidationGroup(array(
            'company' => array(
                'name',
                'address',
                'postcode',
                'city',
                'country',
                'vatin',
                'ein',
                'bein',
            )
        ));
    }


    /**
     * Method used to inject ServiceLocator.
     * @param $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Method used to obtain injected ServiceLocator
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator->getServiceLocator();
    }
}
