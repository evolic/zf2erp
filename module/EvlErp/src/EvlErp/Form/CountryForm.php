<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Form;

use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use EvlErp\Doctrine\Repository\CountriesRepository;
use EvlErp\Validator\CountryNotExists as CountryNotExistsValidator;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class CountryForm extends Form implements InputFilterProviderInterface
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('country-form');
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
     * Attaches Country-Not-Exists validator to not add the same country twice
     *
     * @param CountryNotExistsValidator $validator
     */
    public function attachObjectExistsValidator(CountryNotExistsValidator $validator)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        $firephp->info($this->getInputFilter()->get('name')->getValidatorChain()->getValidators(), 'validators before');

        $this->getInputFilter()->get('name')->getValidatorChain()->attach($validator);

        $firephp->info($this->getInputFilter()->get('name')->getValidatorChain()->getValidators(), 'validators after');

        $firephp->groupEnd();
    }
}
