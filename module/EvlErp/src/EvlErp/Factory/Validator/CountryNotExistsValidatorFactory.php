<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Factory\Validator;

use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use EvlErp\Validator\CountryNotExists as CountryNotExistsValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CountryNotExistsValidatorFactory - factory used to create CountryNotExists validator.
 *
 * @package EvlErp\Factory\Validator
 */
class CountryNotExistsValidatorFactory implements FactoryInterface
{
    /**
     * Factory method.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return OrdersService|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $repository = $serviceLocator->getServiceLocator()->get('CountriesService')->getCountriesRepository();
        $validator = new CountryNotExistsValidator(array(
            'object_repository' => $repository,
            'fields' => array('name'),
            'messages' => array(
                NoObjectExistsValidator::ERROR_OBJECT_FOUND => 'Country with specified name is already present in the database'
            )
        ));

        return $validator;
    }
}
