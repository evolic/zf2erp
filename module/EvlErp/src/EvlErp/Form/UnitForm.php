<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Form;

use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use EvlErp\Doctrine\Repository\UnitsRepository;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class UnitForm extends Form implements InputFilterProviderInterface
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('unit-form');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'name',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Name',
                'label_attributes' => array(
                    'class' => 'name'
                ),
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'description',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Description',
                'label_attributes' => array(
                    'class' => 'description'
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
     * Attaches Object-Exists validator from DoctrineModule to not add the same unit twice
     *
     * @param UnitsRepository $repository
     */
    public function attachObjectExistsValidator(UnitsRepository $repository)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        $firephp->info(get_class($repository));

        $validator = new NoObjectExistsValidator(array(
            'object_repository' => $repository,
            'fields' => array('name'),
            'messages' => array(
                NoObjectExistsValidator::ERROR_OBJECT_FOUND => 'Specified unit is already present in the database'
            )
        ));

        $firephp->info($this->getInputFilter()->get('name')->getValidatorChain()->getValidators(), 'validators before');

        $this->getInputFilter()->get('name')->getValidatorChain()->attach($validator);

        $firephp->info($this->getInputFilter()->get('name')->getValidatorChain()->getValidators(), 'validators after');

        $firephp->groupEnd();
    }
}
