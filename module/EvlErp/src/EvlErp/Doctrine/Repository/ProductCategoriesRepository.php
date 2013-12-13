<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use EvlErp\Entity\ProductCategory;

/**
 * Class ProductCategoriesRepository - orders repository. Provides additional database query methods.
 *
 * @package EvlErp\Doctrine\Repository
 */
class ProductCategoriesRepository extends EntityRepository
  implements ProductCategoriesRepositoryInterface
{
    /**
     * Method used to obtain available ProductCategories from the database
     *
     * @param array $criteria - additional criteria
     * @param int $hydrate - result hydration mode
     * @return array - available product categories
     */
    public function getProductCategories($criteria, $hydrate = Query::HYDRATE_OBJECT)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('c')
            ->from('EvlErp\Entity\ProductCategory', 'c');

        if (isset($criteria['order_by']) && $criteria['order_by']) {
            $qb->orderBy('c.' . $criteria['order_by']);
        } else {
            $qb->orderBy('c.name', 'ASC');
        }
        if (isset($criteria['limit']) && $criteria['limit']) {
            $qb->setMaxResults($criteria['limit']);
        }

        return $qb->getQuery()->getResult($hydrate);
    }
}
