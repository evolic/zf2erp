<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Form;

use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use EvlErp\Doctrine\Repository\ProductsRepository;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\ValidatorChain;

class ProductPriceForm extends Form implements ServiceLocatorAwareInterface
{
    public function init()
    {
        // we want to ignore the name passed
        parent::__construct('product-price-form');
        $this->setAttribute('method', 'post');

        // translator
        $translator = $this->getServiceLocator()->get('translator');

        // base fieldset
        $this->add(array(
            'type' => 'EvlErp\Form\Fieldset\ProductPriceFieldset',
            'options' => array(
                'use_as_base_fieldset' => true
            )
        ));

        $this->setValidationGroup(array(
            'product' => array(
                'vat_rate',
                'price_netto',
                'price_brutto',
            )
        ));
    }


    /**
     * Method used to inject ServiceLocator.
     *
     * @param $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Method used to obtain injected ServiceLocator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
      return $this->serviceLocator->getServiceLocator();
    }
}
