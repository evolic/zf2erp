<?php
/**
 * ERP System
 *
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 * @copyright Copyright (c) 2013 Tomasz Kuter (http://www.tomaszkuter.com)
 */

namespace EvlErp\Service;

use Doctrine\ORM\EntityManager;
use EvlErp\Doctrine\Repository\VatRatesRepository;
use EvlErp\Entity\VatRate;

/**
 * Class VatRatesService - service used to perform basic logic operations on VAT rates.
 *
 * @package EvlErp\Service
 */
class VatRatesService implements VatRatesServiceInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;


    /**
     * Method used to obtain orders repository.
     *
     * @return VatRatesRepository
     */
    public function getVatRatesRepository()
    {
        return $this->getEntityManager()->getRepository('EvlErp\Entity\VatRate');
    }

    /**
     * Method used to persist new VAT rate in database
     *
     * @param VatRate $vatRate
     * @return boolean
     */
    public function addVatRate(VatRate $vatRate)
    {
        $this->getEntityManager()->beginTransaction();
        try {
            $this->getEntityManager()->persist($vatRate);
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
