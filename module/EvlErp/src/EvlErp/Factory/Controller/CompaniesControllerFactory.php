<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Factory\Controller;

use EvlErp\Controller\CompaniesController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CompaniesFactory - factory used to create CompaniesController.
 *
 * @package EvlErp\Factory\Controller
 */
class CompaniesControllerFactory implements FactoryInterface
{
    /**
     * Factory method.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CompaniesController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        $form = $serviceLocator->getServiceLocator()->get('FormElementManager')->get('EvlErp\Form\CompanyForm');

        $ctr = new CompaniesController();
        $ctr->setCompanyForm($form);
        $ctr->setCompaniesService($serviceLocator->getServiceLocator()->get('CompaniesService'));

        $firephp->groupEnd();

        return $ctr;
    }
}
