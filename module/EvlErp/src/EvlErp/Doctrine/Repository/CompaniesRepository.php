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
use EvlErp\Entity\Company;

/**
 * Class CompaniesRepository - orders repository. Provides additional database query methods.
 *
 * @package EvlErp\Doctrine\Repository
 */
class CompaniesRepository extends EntityRepository
  implements CompaniesRepositoryInterface
{
    /**
     * Method used to obtain available Companies from the database
     *
     * @param array $criteria - additional criteria
     * @param int $hydrate - result hydration mode
     * @return array - available companies
     */
    public function getCompanies($criteria, $hydrate = Query::HYDRATE_OBJECT)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('c1, c2')
            ->from('EvlErp\Entity\Company', 'c1')
            ->join('c1.country', 'c2')
        ;

        if (isset($criteria['order_by']) && $criteria['order_by']) {
            switch ($criteria['order_by']) {
                case 'name':
                    $qb->orderBy('c1.' . $criteria['order_by']);
                    break;
                case 'city':
                    $qb->addOrderBy('c1.' . $criteria['order_by'], 'ASC')
                    ->orderBy('c1.name', 'ASC')
                    ;
                    break;
                case 'country':
                    $qb->addOrderBy('c2.name', 'ASC')
                        ->orderBy('c1.name', 'ASC')
                    ;
                    break;
            }
        } else {
            $qb->orderBy('c1.name', 'ASC');
        }
        if (isset($criteria['limit']) && $criteria['limit']) {
            $qb->setMaxResults($criteria['limit']);
        }

        return $qb->getQuery()->getResult($hydrate);
    }
}
