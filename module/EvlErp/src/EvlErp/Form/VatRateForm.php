<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Form;

use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use EvlErp\Doctrine\Repository\VatRatesRepository;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class VatRateForm extends Form implements InputFilterProviderInterface
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('vat-rate-form');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'value',
            'attributes' => array(
                'value' => '23',
            ),
            'options' => array(
                'label' => 'VAT rate',
                'label_attributes' => array(
                    'class' => 'value'
                ),
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit',
                'id' => 'submit',
                'class' => 'btn btn-primary'
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
     * Attaches Object-Exists validator from DoctrineModule to not add the same VAT rate twice
     *
     * @param VatRatesRepository $repository
     * @return VatRateForm
     */
    public function attachObjectExistsValidator(VatRatesRepository $repository)
    {
        $validator = new NoObjectExistsValidator(array(
            'object_repository' => $repository,
            'fields' => array('value'),
            'messages' => array(
                NoObjectExistsValidator::ERROR_OBJECT_FOUND => 'Specified VAT rate is already present in the database'
            )
        ));

        $this->getInputFilter()->get('value')->getValidatorChain()->attach($validator);

        return $this;
    }
}
