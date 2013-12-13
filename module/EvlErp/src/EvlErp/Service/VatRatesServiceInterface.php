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

interface VatRatesServiceInterface
{
    /**
     * Method used to obtain orders repository.
     *
     * @return VatRatesRepository
     */
    public function getVatRatesRepository();

    /**
     * Method used to persist new VAT rate in database
     *
     * @param VatRate $vatRate
     * @return boolean
     */
    public function addVatRate(VatRate $vatRate);


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
