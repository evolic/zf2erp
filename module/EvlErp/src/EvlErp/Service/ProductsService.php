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

/**
 * Class ProductsService - service used to perform basic logic operations on products.
 *
 * @package EvlErp\Service
 */
class ProductsService implements ProductsServiceInterface
{
    /**
     * Service behaviour mode: insert
     * @var string
     */
    const MODE_INSERT  = 'insert';
    /**
     * Service behaviour mode: update
     * @var string
     */
    const MODE_UPDATE = 'update';


    /**
     * @var EntityManager
     */
    private $entityManager;


    /**
     * Method used to obtain orders repository.
     *
     * @return ProductsRepository
     */
    public function getProductsRepository()
    {
        return $this->getEntityManager()->getRepository('EvlErp\Entity\Product');
    }

    /**
     * Internal methods, which saves product in the database (insert/update mode)
     *
     * @param Product $product
     * @param string $mode
     * @return boolean
     */
    private function _saveProduct(Product $product, $mode = self::MODE_INSERT)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);

        $this->getEntityManager()->beginTransaction();
        try {
            if ($mode === self::MODE_INSERT) {
                $this->getEntityManager()->persist($product);
            }

            $firephp->info($product, '$product');

            // commit transaction
            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();

            $firephp->groupEnd();

            return true;
        } catch (\Exception $e) {
            $this->getEntityManager()->rollback();
            $this->getEntityManager()->close();

            $firephp->error($e->getMessage());
            $firephp->error($e->getTraceAsString());
            $firephp->groupEnd();

            return false;
        }
    }

    /**
     * Method used to persist new product in database
     *
     * @param Product $product
     * @return boolean
     */
    public function addProduct(Product $product)
    {
      $firephp = \FirePHP::getInstance();
      $firephp->info(__METHOD__);

        return $this->_saveProduct($product, self::MODE_INSERT);
    }

    /**
     * Method used to update product data in database
     *
     * @param Product $product
     * @return boolean
     */
    public function updateProduct(Product $product)
    {
      $firephp = \FirePHP::getInstance();
      $firephp->info(__METHOD__);

        return $this->_saveProduct($product, self::MODE_UPDATE);
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
