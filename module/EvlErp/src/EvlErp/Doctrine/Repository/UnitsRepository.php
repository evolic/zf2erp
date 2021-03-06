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
use EvlErp\Entity\Unit;

/**
 * Class UnitsRepository - orders repository. Provides additional database query methods.
 *
 * @package EvlErp\Doctrine\Repository
 */
class UnitsRepository extends EntityRepository
  implements UnitsRepositoryInterface
{
    /**
     * Method used to obtain available Units from the database
     *
     * @param array $criteria - additional criteria
     * @param int $hydrate - result hydration mode
     * @return array - available units
     */
    public function getUnits($criteria, $hydrate = Query::HYDRATE_OBJECT)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from('EvlErp\Entity\Unit', 'u');

        if (isset($criteria['order_by']) && $criteria['order_by']) {
            switch ($criteria['order_by']) {
                case 'name':
                case 'description':
                    $qb->orderBy('u.' . $criteria['order_by']);
                    break;
            }
        } else {
            $qb->orderBy('u.name', 'ASC');
        }
        if (isset($criteria['limit']) && $criteria['limit']) {
            $qb->setMaxResults($criteria['limit']);
        }

        return $qb->getQuery()->getResult($hydrate);
    }
}
