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
use EvlErp\Entity\ProductCategory;
use EvlErp\Entity\Company;
use EvlErp\Entity\Unit;
use EvlErp\Entity\VatRate;
use EvlErp\Form\ProductForm;
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\NotEmpty as NotEmptyValidator;
use Zend\Validator\StringLength as StringLengthValidator;

/**
 * Class ProductFieldset - product fieldset
 * @package EvlErp\Form\Fieldset
 */
class ProductFieldset extends Fieldset
  implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * Form behaviour mode: default mode is edit (update), because it contains all fields
     *
     * @var string $_mode
     */
    private $_mode = ProductForm::MODE_EDIT;


    /**
     * Fieldset building
     */
    public function init()
    {
        parent::__construct('product');

        // You will get the application wide service manager
        $sm = $this->getFormFactory()->getFormElementManager()->getServiceLocator();

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
            'name' => 'name',
            'attributes' => array(
                'type' => 'text',
                'id' => 'name',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => $translator->translate('Name'),
                'label_attributes' => array(
                    'class' => 'name'
                ),
            ),
        ));

        $this->add(array(
            'name' => 'code',
            'attributes' => array(
                'type' => 'text',
                'id' => 'code',
                'class' => 'form-control',
                'disabled' => 'disabled',
            ),
            'options' => array(
                'label' => $translator->translate('Code'),
                'label_attributes' => array(
                    'class' => 'code'
                ),
            ),
        ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'category',
            'options' => array(
                'label' => $translator->translate('Category'),
                'object_manager' => $entityManager,
                'target_class' => 'EvlErp\Entity\ProductCategory',
                'property' => 'name',
                'empty_option' => $translator->translate('Choose category'),
            ),
            'attributes' => array(
                'id' => 'category',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'type'    => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'name' => 'suppliers',
            'options' => array(
                'label' => $translator->translate('Suppliers'),
                'object_manager' => $entityManager,
                'should_create_template' => true,
                'target_class' => 'EvlErp\Entity\Company',
//                 'property' => 'name',
                'label_generator' => function(Company $targetEntity) {
                    return $targetEntity->getName();
                },
            ),
        ));

//         $this->add(array(
//             'type' => 'DoctrineModule\Form\Element\ObjectSelect',
//             'name' => 'suppliers',
//             'options' => array(
//                 'label' => $translator->translate('Suppliers'),
//                 'object_manager' => $entityManager,
//                 'target_class' => 'EvlErp\Entity\Company',
//                 'property' => 'name',
//                 'empty_option' => $translator->translate('Choose supplier'),
//             ),
//             'attributes' => array(
//                 'id' => 'suppliers',
//                 'class' => 'form-control',
//             ),
//         ));

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

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'unit',
            'options' => array(
                'label' => $translator->translate('Unit', 'evl-erp'),
                'object_manager' => $entityManager,
                'target_class' => 'EvlErp\Entity\Unit',
                'property' => 'name',
                'is_method'      => true,
                'find_method'    => array(
                    'name'   => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy'  => array('id' => 'ASC'),
                    ),
                ),
            ),
            'attributes' => array(
                'id' => 'unit',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type' => 'textarea',
                'id' => 'description',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => $translator->translate('Description'),
                'label_attributes' => array(
                    'class' => 'description'
                ),
            ),
        ));

        $this->add(array(
            'name' => 'created_at',
            'attributes' => array(
                'type' => 'text',
                'id' => 'created_at',
                'class' => 'form-control',
                'disabled' => 'disabled',
            ),
            'options' => array(
                'label' => $translator->translate('Created at'),
                'label_attributes' => array(
                    'class' => 'created_at'
                ),
            ),
        ));

        $this->add(array(
            'name' => 'updated_at',
            'attributes' => array(
                'type' => 'text',
                'id' => 'updated_at',
                'class' => 'form-control',
                'disabled' => 'disabled',
            ),
            'options' => array(
                'label' => $translator->translate('Updated at'),
                'label_attributes' => array(
                    'class' => 'updated_at'
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
            'id' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ),
            'name' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 127,
                        ),
                    ),
                    $this->getUniqueNameValidator(),
                ),
            ),
        );
    }

    /**
     * Attaches Object-Exists validator from DoctrineModule to not add the same company twice
     *
     * @return NoObjectExistsValidator
     */
    private function getUniqueNameValidator()
    {
        // translator
        $translator = $this->getServiceLocator()->get('translator');
        // repository
        $repository = $this->getServiceLocator()->get('ProductsService')->getProductsRepository();

        $validator = new NoObjectExistsValidator(array(
            'object_repository' => $repository,
            'fields' => array('name'),
            'messages' => array(
                NoObjectExistsValidator::ERROR_OBJECT_FOUND => $translator->translate(
                    'There is already product with specified name in the database', 'evl-erp'
                ),
            )
        ));

        return $validator;
    }


    /**
     * Sets form behaviour mode: add (insert) or edit (update)
     *
     * @param string $mode
     * @return ProductFieldset
     */
    public function setMode($mode)
    {
        switch ($mode) {
            case ProductForm::MODE_ADD:
            case ProductForm::MODE_EDIT:
                $this->_mode = $mode;
                break;
        }

        switch ($this->_mode) {
            case ProductForm::MODE_ADD:
                $this->_setAddMode();
                break;
            case ProductForm::MODE_EDIT:
                $this->_setEditMode();
                break;
        }

        return $this;
    }

    /**
     * Internal method, which removes some fields not used while product adding
     */
    private function _setAddMode()
    {
    }

    /**
     * Internal method, which removes some fields not used while product editing
     */
    private function _setEditMode()
    {
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
