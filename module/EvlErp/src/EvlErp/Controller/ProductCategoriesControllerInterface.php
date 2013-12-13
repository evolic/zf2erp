<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Controller;

use EvlErp\Form\ProductCategoryForm;
use EvlErp\Service\ProductCategoriesService;

interface ProductCategoriesControllerInterface
{
    const DEFAULT_LIMIT_PER_PAGE = 20;


    public function indexAction();


    /**
     * Method used to inject form handling adding new product category.
     *
     * @param ProductCategoryForm $form
     */
    public function setProductCategoryForm(ProductCategoryForm $form);

    /**
     * Method used to obtain form handling adding new product category.
     *
     * @return ProductCategoryForm
     */
    public function getProductCategoryForm();

    /**
     * Method used to inject product categories service.
     *
     * @param ProductCategoriesService $service
     */
    public function setProductCategoriesService(ProductCategoriesService $service);

    /**
     * Method used to obtain product categories service.
     *
     * @return ProductCategoriesService
     */
    public function getProductCategoriesService();
}
