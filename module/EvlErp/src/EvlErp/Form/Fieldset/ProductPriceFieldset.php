<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Form\Fieldset;

use EvlCore\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use EvlErp\Entity\Product;
use EvlErp\Entity\VatRate;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\I18n\Filter\NumberFormat;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\Between as BetweenValidator;
use Zend\Validator\NotEmpty as NotEmptyValidator;
use Zend\Validator\StringLength as StringLengthValidator;

/**
 * Class ProductPriceFieldset - product fieldset
 * @package EvlErp\Form\Fieldset
 */
class ProductPriceFieldset extends Fieldset
  implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;


    /**
     * Fieldset building
     */
    public function init()
    {
        parent::__construct('product');

        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        // Doctrine hydrator
        $hydrator = new DoctrineHydrator($entityManager, 'EvlErp\Entity\Product');
        // Fix setter and getter names in DoctrineModule\Stdlib\Hydrator\DoctrineObject
        $hydrator->setFixUnderscoreGetters(true);
        $hydrator->setFixUnderscoreSetters(true);
        $this->setHydrator($hydrator)->setObject(new Product());

        // translator
        $translator = $this->getServiceLocator()->get('translator');


        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'vat_rate',
            'options' => array(
                'label' => $translator->translate('VAT rate', 'evl-erp'),
                'object_manager' => $entityManager,
                'target_class' => 'EvlErp\Entity\VatRate',
                'label_generator' => function(VatRate $targetEntity) {
                    return $targetEntity->getValue() . '%';
                },
                'empty_option' => $translator->translate('Choose VAT rate', 'evl-erp'),
            ),
            'attributes' => array(
                'id' => 'vat_rate',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'price_netto',
            'attributes' => array(
                'type' => 'text',
                'id' => 'price_netto',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => $translator->translate('Price (netto)'),
                'label_attributes' => array(
                    'class' => 'price_netto'
                ),
            ),
        ));

        $this->add(array(
            'name' => 'price_brutto',
            'attributes' => array(
                'type' => 'text',
                'id' => 'price_brutto',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => $translator->translate('Price (brutto)'),
                'label_attributes' => array(
                    'class' => 'price_brutto'
                ),
            ),
        ));
    }


    /**
     * Input filter and converter specification.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        // translator
        $translator = $this->getServiceLocator()->get('translator');

        return array(
            'price_netto' => array(
                'required' => false,
                'filters' => array(
                    new NumberFormat("en_US")
                ),
                'validators' => array(
                    array(
                        'name' => 'Float',
                        'options' => array(
                            'locale' => 'en_US',
                        ),
                    ),
                    array (
                        'name' => 'Between',
                        'options' => array (
                            'min' => 0,
                            'max' => 10000,
                            'inclusive' => false,
                            'messages' => array(
                                BetweenValidator::NOT_BETWEEN_STRICT => $translator->translate(
                                    'Price must be between %min% and %max%', 'evl-core'
                                ),
                            )
                        )
                    ),
                ),
            ),
            'price_brutto' => array(
                'required' => false,
                'filters' => array(
                    new NumberFormat("en_US")
                ),
                'validators' => array(
                    array(
                        'name' => 'Float',
                        'options' => array(
                            'locale' => 'en_US',
                        ),
                    ),
                    array (
                        'name' => 'Between',
                        'options' => array (
                            'min' => 0,
                            'max' => 10000,
                            'inclusive' => false,
                            'messages' => array(
                                BetweenValidator::NOT_BETWEEN_STRICT => $translator->translate(
                                    'Price must be between %min% and %max%', 'evl-core'
                                ),
                            )
                        )
                    ),
                ),
            ),
        );
    }


    /**
     * Method used to inject ServiceLocator.
     *
     * @param ServiceLocatorInterface $serviceLocator
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
