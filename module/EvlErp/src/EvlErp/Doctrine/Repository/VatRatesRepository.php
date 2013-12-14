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
use EvlErp\Entity\VatRate;

/**
 * Class VatRatesRepository - orders repository. Provides additional database query methods.
 *
 * @package EvlErp\Doctrine\Repository
 */
class VatRatesRepository extends EntityRepository
  implements VatRatesRepositoryInterface
{
    /**
     * Method used to obtain available VAT rates from the database
     *
     * @param array $criteria - additional criteria
     * @param int $hydrate - result hydration mode
     * @return array - available VAT rates
     */
    public function getVatRates($criteria, $hydrate = Query::HYDRATE_OBJECT)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('vr')
            ->from('EvlErp\Entity\VatRate', 'vr');

        if (isset($criteria['order_by']) && $criteria['order_by']) {
            switch ($criteria['order_by']) {
                case 'value':
                    $qb->orderBy('vr.' . $criteria['order_by']);
                    break;
            }
        } else {
            $qb->orderBy('vr.value', 'ASC');
        }
        if (isset($criteria['limit']) && $criteria['limit']) {
            $qb->setMaxResults($criteria['limit']);
        }

        return $qb->getQuery()->getResult($hydrate);
    }
}
