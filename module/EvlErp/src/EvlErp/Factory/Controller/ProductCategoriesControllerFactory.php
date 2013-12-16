<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Factory\Controller;

use EvlErp\Controller\ProductCategoriesController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ProductCategoriesFactory - factory used to create ProductCategoriesController.
 *
 * @package EvlErp\Factory\Controller
 */
class ProductCategoriesControllerFactory implements FactoryInterface
{
    /**
     * Factory method.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ProductCategoriesController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $productCategoryForm = $serviceLocator->getServiceLocator()->get('FormElementManager')->get('EvlErp\Form\ProductCategoryForm');

        $ctr = new ProductCategoriesController();
        $ctr->setProductCategoryForm($productCategoryForm);
        $ctr->setProductCategoriesService($serviceLocator->getServiceLocator()->get('ProductCategoriesService'));

        return $ctr;
    }
}
