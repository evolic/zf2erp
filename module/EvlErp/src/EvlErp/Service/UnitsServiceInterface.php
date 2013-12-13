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

interface UnitsServiceInterface
{
    /**
     * Method used to obtain units repository.
     *
     * @return UnitsRepository
     */
    public function getUnitsRepository();

    /**
     * Method used to persist new unit in database
     *
     * @param Unit $unit
     * @return boolean
     */
    public function addUnit(Unit $unit);


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
