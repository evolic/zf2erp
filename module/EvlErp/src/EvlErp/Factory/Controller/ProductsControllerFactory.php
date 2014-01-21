<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Factory\Controller;

use EvlErp\Controller\ProductsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ProductsFactory - factory used to create ProductsController.
 *
 * @package EvlErp\Factory\Controller
 */
class ProductsControllerFactory implements FactoryInterface
{
    /**
     * Factory method.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ProductsController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
      $formElementManager = $serviceLocator->getServiceLocator()->get('FormElementManager');
        $productForm = $formElementManager->get('EvlErp\Form\ProductForm');
        $productPriceForm = $formElementManager->get('EvlErp\Form\ProductPriceForm');

        $ctr = new ProductsController();
        $ctr->setProductForm($productForm);
        $ctr->setProductPriceForm($productPriceForm);
        $ctr->setProductsService($serviceLocator->getServiceLocator()->get('ProductsService'));

        return $ctr;
    }
}
