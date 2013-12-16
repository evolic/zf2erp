<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Form;

use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use EvlErp\Doctrine\Repository\ProductCategoriesRepository;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class ProductCategoryForm extends Form implements InputFilterProviderInterface
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('product-category-form');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'name',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'class' => 'form-control',
                'label' => 'Name',
                'label_attributes' => array(
                    'class' => 'name'
                ),
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit',
                'id' => 'submit',
                'class' => 'btn btn-default',
            ),
        ));
    }


    /**
     * Input filter and converter specification.
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
        );
    }

    /**
     * Attaches Object-Exists validator from DoctrineModule to not add the same product category twice
     *
     * @param ProductCategoriesRepository $repository
     * @return ProductCategoryForm
     */
    public function attachObjectExistsValidator(ProductCategoriesRepository $repository)
    {
        $validator = new NoObjectExistsValidator(array(
            'object_repository' => $repository,
            'fields' => array('name'),
            'messages' => array(
                NoObjectExistsValidator::ERROR_OBJECT_FOUND => 'Category with specified name is already present in the database'
            )
        ));

        $this->getInputFilter()->get('name')->getValidatorChain()->attach($validator);

        return $this;
    }
}
