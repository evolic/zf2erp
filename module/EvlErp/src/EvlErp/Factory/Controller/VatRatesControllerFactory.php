<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Factory\Controller;

use EvlErp\Controller\VatRatesController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class VatRatesFactory - factory used to create VatRatesController.
 *
 * @package EvlErp\Factory\Controller
 */
class VatRatesControllerFactory implements FactoryInterface
{
    /**
     * Factory method.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return OrdersController|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        $vatRateForm = $serviceLocator->getServiceLocator()->get('FormElementManager')->get('EvlErp\Form\VatRateForm');

        $ctr = new VatRatesController();
        $ctr->setVatRateForm($vatRateForm);
        $ctr->setVatRatesService($serviceLocator->getServiceLocator()->get('VatRatesService'));

        $firephp->groupEnd();

        return $ctr;
    }
}