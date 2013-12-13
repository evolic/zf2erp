<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Service;

use Doctrine\ORM\EntityManager;
use EvlErp\Doctrine\Repository\UnitsRepository;
use EvlErp\Entity\Unit;

/**
 * Class UnitsService - service used to perform basic logic operations on units.
 *
 * @package EvlErp\Service
 */
class UnitsService implements UnitsServiceInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;


    /**
     * Method used to obtain orders repository.
     *
     * @return UnitsRepository
     */
    public function getUnitsRepository()
    {
        return $this->getEntityManager()->getRepository('EvlErp\Entity\Unit');
    }

    /**
     * Method used to persist new unit in database
     *
     * @param Unit $unit
     * @return boolean
     */
    public function addUnit(Unit $unit)
    {
        $this->getEntityManager()->beginTransaction();
        try {
            $this->getEntityManager()->persist($unit);
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
