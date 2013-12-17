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

class ProductForm extends Form implements ServiceLocatorAwareInterface
{
    /**
     * Form behaviour mode: add (insert)
     * @var string
     */
    const MODE_ADD  = 'add';
    /**
     * Form behaviour mode: edit (update)
     * @var string
     */
    const MODE_EDIT = 'edit';

    /**
     * Form behaviour mode: default mode is edit (update), because it contains all fields
     *
     * @var string $_mode
     */
    private $_mode = self::MODE_EDIT;


    public function init()
    {
        // we want to ignore the name passed
        parent::__construct('product-form');
        $this->setAttribute('method', 'post');

        // translator
        $translator = $this->getServiceLocator()->get('translator');

        // base fieldset
        $this->add(array(
            'type' => 'EvlErp\Form\Fieldset\ProductFieldset',
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
            'product' => array(
                'name',
                'category',
                'suppliers',
                'vat_rate',
                'price_netto',
                'price_brutto',
                'unit',
                'description',
            )
        ));
    }


    /**
     * Attaches Object-Exists validator from DoctrineModule to not add the same product twice
     *
     * @param ProductsRepository $repository
     */
    public function attachObjectExistsValidator(ProductsRepository $repository)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        $firephp->info(get_class($repository));

        $validator = new NoObjectExistsValidator(array(
            'object_repository' => $repository,
            'fields' => array(
                'product' => array ('name'),
            ),
            'messages' => array(
                NoObjectExistsValidator::ERROR_OBJECT_FOUND => 'Specified product is already present in the database'
            )
        ));

        $firephp->info($this->getInputFilter()->get('name')->getValidatorChain()->getValidators(), 'validators before');

        $this->getInputFilter()->get('name')->getValidatorChain()->attach($validator);

        $firephp->info($this->getInputFilter()->get('name')->getValidatorChain()->getValidators(), 'validators after');

        $firephp->groupEnd();
    }

    /**
     * Sets form behaviour mode: add (insert) or edit (update)
     *
     * @param string $mode
     * @return ProductForm
     */
    public function setMode($mode)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        switch ($mode) {
            case self::MODE_ADD:
            case self::MODE_EDIT:
                $this->_mode = $mode;
                // set mode in all included fieldsets
                foreach ($this->getFieldsets() as $fieldset) {
                    if (method_exists($fieldset, 'setMode')) {
                        $fieldset->setMode($mode);
                    }
                }
                break;
        }

        switch ($this->_mode) {
            case self::MODE_ADD:
                $this->_setAddMode();
                break;
            case self::MODE_EDIT:
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
        $this->getInputFilter()->get('product')->remove('code');
        $this->getInputFilter()->get('product')->remove('created_at');
        $this->getInputFilter()->get('product')->remove('updated_at');
    }

    /**
     * Internal method, which removes some fields not used while product editing
     */
    private function _setEditMode()
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        $firephp->info(is_resource($this->getInputFilter()->get('product')->get('name')), 'is_resource');
        $firephp->info(is_object($this->getInputFilter()->get('product')->get('name')), 'is_object');
        $firephp->info(get_class($this->getInputFilter()->get('product')->get('name')), 'get_class');
        \ChromePhp::log($this->getInputFilter()->get('product')->get('name'));
        \ChromePhp::log($this->getInputFilter()->get('product')->get('name')->getValidatorChain()->getValidators());

        // create new validator chain
        $newValidatorChain = new ValidatorChain;

        foreach ($this->getInputFilter()->get('product')->get('name')->getValidatorChain()->getValidators() as $validator) {
            $instance = $validator['instance'];
            if (!($instance instanceof NoObjectExistsValidator)) {
                $newValidatorChain->addValidator($instance, $validator['breakChainOnFailure']);
            }
        }
        // replace the old validator chain on the element
        $this->getInputFilter()->get('product')->get('name')->setValidatorChain($newValidatorChain);

        $firephp->groupEnd();
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
        $firephp = \FirePHP::getInstance();
        $firephp->info(__METHOD__);
        $firephp->info(is_object($this->serviceLocator), '$this->serviceLocator');
        if (is_object($this->serviceLocator)) {
            $firephp->info(get_class($this->serviceLocator), 'get_class($this->serviceLocator)');
            $firephp->info(is_object($this->serviceLocator->getServiceLocator()), '$this->serviceLocator->getServiceLocator()');
            if (is_object($this->serviceLocator->getServiceLocator())) {
                $firephp->info(get_class($this->serviceLocator->getServiceLocator()), 'get_class($this->serviceLocator->getServiceLocator())');
            }
        }

      return $this->serviceLocator->getServiceLocator();
    }
}
