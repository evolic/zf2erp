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

/**
 * Class ProductCategoriesService - service used to perform basic logic operations on product categories.
 *
 * @package EvlErp\Service
 */
class ProductCategoriesService implements ProductCategoriesServiceInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;


    /**
     * Method used to obtain orders repository.
     *
     * @return ProductCategoriesRepository
     */
    public function getProductCategoriesRepository()
    {
        return $this->getEntityManager()->getRepository('EvlErp\Entity\ProductCategory');
    }

    /**
     * Method used to persist new product category in database
     *
     * @param ProductCategory $productCategory
     * @return boolean
     */
    public function addProductCategory(ProductCategory $productCategory)
    {
        $this->getEntityManager()->beginTransaction();
        try {
            $this->getEntityManager()->persist($productCategory);
            // commit transaction
            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();

            return true;
        } catch (\Exception $e) {
            $this->getEntityManager()->rollback();
            $this->getEntityManager()->close();

            return false;
        }
    }


    /**
     * Method used to obtain EntityManager.
     *
     * @return EntityManager - entity manager object
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Method used to inject EntityManager.
     *
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
