<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Factory\Controller;

use EvlErp\Controller\UnitsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UnitsFactory - factory used to create UnitsController.
 *
 * @package EvlErp\Factory\Controller
 */
class UnitsControllerFactory implements FactoryInterface
{
    /**
     * Factory method.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return UnitsController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        $unitForm = $serviceLocator->getServiceLocator()->get('FormElementManager')->get('EvlErp\Form\UnitForm');

        $ctr = new UnitsController();
        $ctr->setUnitForm($unitForm);
        $ctr->setUnitsService($serviceLocator->getServiceLocator()->get('UnitsService'));

        $firephp->groupEnd();

        return $ctr;
    }
}
