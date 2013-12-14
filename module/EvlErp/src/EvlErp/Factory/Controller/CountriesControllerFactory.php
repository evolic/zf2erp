<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Factory\Controller;

use EvlErp\Controller\CountriesController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CountriesFactory - factory used to create CountriesController.
 *
 * @package EvlErp\Factory\Controller
 */
class CountriesControllerFactory implements FactoryInterface
{
    /**
     * Factory method.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CountriesController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        $countryForm = $serviceLocator->getServiceLocator()->get('FormElementManager')->get('EvlErp\Form\CountryForm');

        $ctr = new CountriesController();
        $ctr->setCountryForm($countryForm);
        $ctr->setCountriesService($serviceLocator->getServiceLocator()->get('CountriesService'));

        $firephp->groupEnd();

        return $ctr;
    }
}
