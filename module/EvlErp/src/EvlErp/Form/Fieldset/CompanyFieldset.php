<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Form\Fieldset;

use EvlErp\Entity\Company;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\NotEmpty as NotEmptyValidator;
use Zend\Validator\StringLength as StringLengthValidator;

/**
 * Class CompanyFieldset - company fieldset
 * @package EvlErp\Form\Fieldset
 */
class CompanyFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
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
        $firephp = \FirePHP::getInstance();
        $firephp->info(__METHOD__);

        parent::__construct('company');

        // You will get the application wide service manager
        $sm = $this->getFormFactory()->getFormElementManager()->getServiceLocator();
        $firephp->info(is_object($sm));
        $firephp->info(get_class($sm));
//         $firephp->info(get_class($sm->get('Doctrine\ORM\EntityManager')));

        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        // Doctrine hydrator
        $this->setHydrator(new DoctrineHydrator($entityManager, 'EvlErp\Entity\Company'))
            ->setObject(new Company());
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
            'name' => 'address',
            'attributes' => array(
                'type' => 'text',
                'id' => 'address',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => $translator->translate('Address'),
                'label_attributes' => array(
                    'class' => 'address'
                ),
            ),
        ));

        $this->add(array(
            'name' => 'postcode',
            'attributes' => array(
                'type' => 'text',
                'id' => 'postcode',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => $translator->translate('Postcode'),
                'label_attributes' => array(
                    'class' => 'postcode'
                ),
            ),
        ));

        $this->add(array(
            'name' => 'city',
            'attributes' => array(
                'type' => 'text',
                'id' => 'city',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => $translator->translate('Place'),
                'label_attributes' => array(
                    'class' => 'city'
                ),
            ),
        ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'country',
            'options' => array(
                'label' => $translator->translate('Country'),
                'object_manager' => $entityManager,
                'target_class' => 'EvlErp\Entity\Country',
                'property' => 'name',
                'empty_option' => $translator->translate('Choose country'),
//                 'find_method' => array(
//                     'name' => 'findBy',
//                     'params' => array(
// //                         'criteria' => array('locale' => $translator->getLocale()),
//                         'criteria' => array('locale' => 'pl-PL'),
//                         'orderBy'  => array('name' => 'ASC'),
//                     ),
//                 ),
            ),
            'attributes' => array(
                'id' => 'country',
                'class' => 'form-control',
            ),
        ));

        // VAT Identification Number (e.g. NIP in Poland)
        $this->add(array(
            'name' => 'vatin',
            'attributes' => array(
                'type' => 'text',
                'id' => 'vatin',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => $translator->translate('VAT Identification Number', 'evl-erp'),
                'label_attributes' => array(
                    'class' => 'vatin'
                ),
            ),
        ));

        // Enterprise Identification Number (e.g. REGON in Poland)
        $this->add(array(
            'name' => 'ein',
            'attributes' => array(
                'type' => 'text',
                'id' => 'ein',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => $translator->translate('Enterprise Identification Number', 'evl-erp'),
                'label_attributes' => array(
                    'class' => 'ein'
                ),
            ),
        ));

        // Business Entity Identification Number (e.g. KRS in Poland)
        $this->add(array(
            'name' => 'bein',
            'attributes' => array(
                'type' => 'text',
                'id' => 'bein',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => $translator->translate('Business Entity Identification Number', 'evl-erp'),
                'label_attributes' => array(
                    'class' => 'bein'
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
            'address' => array(
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
                            'min'      => 2,
                            'max'      => 63,
                        ),
                    ),
                ),
            ),
            'postcode' => array(
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
                            'min'      => 5,
                            'max'      => 7,
                        ),
                    ),
                  ),
              ),
            'city' => array(
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
                            'min'      => 2,
                            'max'      => 63,
                        ),
                    ),
                ),
            ),
            'country' => array(
                'required' => true,
                'filters' => array(
//                     array('name' => 'Int'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmptyValidator::IS_EMPTY => $translator->translate('Please choose one of available countires', 'evl-erp'),
                            ),
                        ),
                    ),
                ),
            ),
            'vatin' => array(
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
                            'min'      => 10,
                            'max'      => 13,
                            'messages' => array(
                                StringLengthValidator::TOO_SHORT => $translator->translate('Please enter valid VAT Identification Number', 'evl-erp'),
                                StringLengthValidator::TOO_LONG => $translator->translate('Please enter valid VAT Identification Number', 'evl-erp'),
                            ),
                        ),
                    ),
                ),
            ),
            'ein' => array(
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
                            'min'      => 9,
                            'max'      => 9,
                            'messages' => array(
                                StringLengthValidator::TOO_SHORT => $translator->translate('Please enter valid Enterprise Identification Number', 'evl-erp'),
                                StringLengthValidator::TOO_LONG => $translator->translate('Please enter valid Enterprise Identification Number', 'evl-erp'),
                            ),
                        ),
                    ),
                ),
            ),
            'bein' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 6,
                            'max'      => 10,
                            'messages' => array(
                                StringLengthValidator::TOO_SHORT => $translator->translate('Please enter valid Business Entity Identification Number', 'evl-erp'),
                                StringLengthValidator::TOO_LONG => $translator->translate('Please enter valid Business Entity Identification Number', 'evl-erp'),
                            ),
                        ),
                    ),
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
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        // translator
        $translator = $this->getServiceLocator()->get('translator');
        // repository
        $repository = $this->getServiceLocator()->get('CompaniesService')->getCompaniesRepository();

        $firephp->info(get_class($repository));

        $validator = new NoObjectExistsValidator(array(
            'object_repository' => $repository,
            'fields' => array('name'),
            'messages' => array(
                NoObjectExistsValidator::ERROR_OBJECT_FOUND => $translator->translate('Specified company is already present in the database', 'EvlErp'),
            )
        ));

        $firephp->groupEnd();

        return $validator;
    }


    /**
     * Method used to inject ServiceLocator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->info(__METHOD__);
        $firephp->info(get_class($serviceLocator), 'get_class($serviceLocator)');
        $firephp->info($serviceLocator, '$serviceLocator');
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
