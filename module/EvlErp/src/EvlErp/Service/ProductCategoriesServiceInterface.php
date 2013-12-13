<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Service;

use Doctrine\ORM\EntityManager;
use EvlErp\Doctrine\Repository\ProductCategoriesRepository;
use EvlErp\Entity\ProductCategory;

interface ProductCategoriesServiceInterface
{
    /**
     * Method used to obtain product categories repository.
     *
     * @return ProductCategoriesRepository
     */
    public function getProductCategoriesRepository();

    /**
     * Method used to persist new product category in database
     *
     * @param ProductCategory $productCategory
     * @return boolean
     */
    public function addProductCategory(ProductCategory $productCategory);


    /**
     * Method used to obtain EntityManager.
     *
     * @return EntityManager - entity manager object
     */
    public function getEntityManager();

    /**
     * Method used to inject EntityManager.
     *
     * @param EntityManager $entityManager
     * @return void
     */
    public function setEntityManager(EntityManager $entityManager);
}
