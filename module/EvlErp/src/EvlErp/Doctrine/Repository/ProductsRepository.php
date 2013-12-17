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
use EvlErp\Entity\Product;

/**
 * Class ProductsRepository - orders repository. Provides additional database query methods.
 *
 * @package EvlErp\Doctrine\Repository
 */
class ProductsRepository extends EntityRepository
  implements ProductsRepositoryInterface
{
    /**
     * Method used to obtain available Products from the database
     *
     * @param array $criteria - additional criteria
     * @param int $hydrate - result hydration mode
     * @return array - available products
     */
    public function getProducts($criteria, $hydrate = Query::HYDRATE_OBJECT)
    {
        $firephp = \FirePHP::getInstance();
        $firephp->group(__METHOD__);
        $firephp->info($criteria, '$criteria');

        $qb = $this->_em->createQueryBuilder();
        $qb->select('p, c')
            ->from('EvlErp\Entity\Product', 'p')
            ->join('p.category', 'c')
//             ->join('p.unit', 'u')
//             ->join('p.vatRate', 'cr')
//             ->join('p.suppliers', 's')
        ;

        if (isset($criteria['keyword']) && $criteria['keyword']) {
            $qb->where("lower(p.name) LIKE lower('%{$criteria['keyword']}%')");
        }

        if (isset($criteria['order_by']) && $criteria['order_by']) {
            $orderBy = $criteria['order_by'];
            $sortDir = $criteria['sort_dir'];

            switch ($orderBy) {
                case 'name':
                    $qb->orderBy('p.' . $orderBy, $sortDir);
                    break;
                case 'category_name':
                    $qb->orderBy('c.name', $sortDir);
                    break;
                case 'price':
                    $qb->orderBy('p.price_netto', $sortDir);
                    break;
            }
        } else {
            $qb->orderBy('p.name', $criteria['sort_dir']);
        }
        if (isset($criteria['limit']) && $criteria['limit']) {
            $qb->setMaxResults($criteria['limit']);
        }

        $firephp->info($qb->getDQL(), 'DQL');
        $firephp->info($qb->getQuery()->getSQL(), 'SQL');

        $firephp->groupEnd();

        return $qb->getQuery()->getResult($hydrate);
    }
}
