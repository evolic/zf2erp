<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Service;

use Doctrine\ORM\EntityManager;
use EvlErp\Doctrine\Repository\ProductsRepository;
use EvlErp\Entity\Product;

interface ProductsServiceInterface
{
    /**
     * Method used to obtain products repository.
     *
     * @return ProductsRepository
     */
    public function getProductsRepository();

    /**
     * Method used to persist new product in database
     *
     * @param Product $product
     * @return boolean
     */
    public function addProduct(Product $product);

    /**
     * Method used to update product data in database
     *
     * @param Product $product
     * @return boolean
     */
    public function updateProduct(Product $product);


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
