<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Factory\Service;

use EvlErp\Service\VatRatesService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class VatRatesServiceFactory - factory used to create VatRatesService.
 *
 * @package EvlErp\Factory\Service
 */
class VatRatesServiceFactory implements FactoryInterface
{
    /**
     * Factory method.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return OrdersService|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        $service = new VatRatesService();
        $service->setEntityManager($serviceLocator->get('Doctrine\ORM\EntityManager'));

        $firephp->groupEnd();

        return $service;
    }
}