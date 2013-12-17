<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Controller;

use EvlErp\Form\ProductForm;
use EvlErp\Service\ProductsService;
use Zend\View\Model\JsonModel as JsonViewModel;
use Zend\View\Model\ViewModel;

interface ProductsControllerInterface
{
    const DEFAULT_LIMIT_PER_PAGE = 20;


    /**
     * Lists products
     *
     * @return ViewModel
     */
    public function indexAction();

    /**
     * Gets products list via ajax call from jQuery Data Table
     *
     * @return JsonViewModel
     */
    public function ajaxListingAction();

    /**
     * Handles adding new products
     *
     * @return ViewModel
     */
    public function addAction();

    /**
     * Handles products editing
     *
     * @return ViewModel
     */
    public function editAction();


    /**
     * Method used to inject form handling adding new product.
     *
     * @param ProductForm $form
     */
    public function setProductForm(ProductForm $form);

    /**
     * Method used to obtain form handling adding new product.
     *
     * @return ProductForm
     */
    public function getProductForm();

    /**
     * Method used to inject products service.
     *
     * @param ProductsService $service
     */
    public function setProductsService(ProductsService $service);

    /**
     * Method used to obtain products service.
     *
     * @return ProductsService
     */
    public function getProductsService();
}
